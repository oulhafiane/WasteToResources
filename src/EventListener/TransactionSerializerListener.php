<?php

namespace App\EventListener;

use App\Entity\Transaction;
use App\Service\CurrentUser;
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

	public function __construct(EntityManagerInterface $em, CurrentUser $cr)
	{
		$this->em = $em;
		$this->cr = $cr;
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

		if (true === $transaction->isCompleted() && false === $transaction->isCanceled())
			$etat = 2;
		else if (false === $transaction->isCompleted() && true === $transaction->isCanceled())
			$etat = -1;
		else if (true === $transaction->isPaid())
			$etat = 1;
		else if (false === $transaction->isPaid())
			$etat = 0;
		else
			$etat = -2;

		$visitor = $event->getVisitor();
		$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\Transaction', 'with', null), $with->getEmail());
		$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\Transaction', 'etat', null), $etat);
		$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\Transaction', 'role', null), $youAre);

		$groups = $event->getContext()->getAttribute('groups');
		if (in_array('specific', $groups)) {
			switch ($youAre) {
				case 'buyer':
					$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\Transaction', 'your_key', null), $transaction->getBuyerKey());
					break;
				case 'seller':
					$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\Transaction', 'your_key', null), $transaction->getSellerKey());
					break;
			}
		}

	}
}
