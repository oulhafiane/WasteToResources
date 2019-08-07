<?php

namespace App\EventListener;

use App\Entity\Transaction;
use App\Service\CurrentUser;
use App\Service\Helper;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TransactionSerializerListener extends AbstractController implements EventSubscriberInterface
{
	private $em;
	private $cr;
	private $helper;

	public function __construct(EntityManagerInterface $em, CurrentUser $cr, Helper $helper)
	{
		$this->em = $em;
		$this->cr = $cr;
		$this->helper = $helper;
	}

	public static function getSubscribedEvents()
	{
		return array(
			array(
				'event' => 'serializer.post_serialize',
				'method' => 'onPostSerialize',
				'class' => 'App\Entity\Transaction',
				'format' => 'json',
				'priority' => 0
			)
		);
	}

	public function onPostSerialize(ObjectEvent $event)
	{
		$current = $this->cr->getCurrentUser($this);
		$transaction = $event->getObject();
		$gain = $transaction->getGain();
		$buyer = $transaction->getBuyer();
		$seller = $transaction->getSeller();

		if ($current->getId() === $buyer->getId()) {
			//Current user is a BUYER
			$with = $seller;
			$youAre = 'buyer';
		} else if ($current->getId() === $seller->getId()) {
			//Current user is a SELLER
			$with = $buyer;
			$youAre = 'seller';
		} else
			throw new HttpException(403, 'Forbidden.');

		if (null === $gain)
			$fees = $this->helper->getOfferFees($transaction->getOffer());
		else
			$fees = $gain->getFees();

		$etat = $this->helper->getTransactionEtat($transaction);

		$withInfos = array(
			'email' => $with->getEmail(),
			'firstName' => $with->getFirstName(),
			'lastName' => $with->getLastName()
		);

		$visitor = $event->getVisitor();
		$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\Transaction', 'with', null), $withInfos);
		$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\Transaction', 'etat', null), $etat);
		$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\Transaction', 'role', null), $youAre);
		$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\Transaction', 'fees', null), $fees);

		$groups = $event->getContext()->getAttribute('groups');
		if ($etat === 1 && in_array('specific', $groups)) {
			switch ($youAre) {
				case 'buyer':
					$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\Transaction', 'key', null), $transaction->getBuyerKey());
					break;
				case 'seller':
					$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\Transaction', 'key', null), $transaction->getSellerKey());
					break;
			}
		}

	}
}
