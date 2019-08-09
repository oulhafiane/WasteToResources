<?php

namespace App\EventListener;

use App\Entity\Bid;
use App\Entity\AuctionBid;
use App\Entity\Parameter;
use App\Service\Helper;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use Doctrine\ORM\EntityManagerInterface;

class OfferSerializerListener implements EventSubscriberInterface
{
	private $em;
	private $helper;

	public function __construct(EntityManagerInterface $em, Helper $helper)
	{
		$this->em = $em;
		$this->helper = $helper;
	}

	public static function getSubscribedEvents()
	{
		return array(
			array(
				'event' => 'serializer.post_serialize',
				'method' => 'onPostSerialize',
				'class' => 'App\Entity\AuctionBid', // if no class, subscribe to every serialization
				'format' => 'json', // optional format
				'priority' => 0 // optional priority
			),
			array(
				'event' => 'serializer.post_serialize',
				'method' => 'onPostSerialize',
				'class' => 'App\Entity\SaleOffer', // if no class, subscribe to every serialization
				'format' => 'json', // optional format
				'priority' => 0 // optional priority
			),
			array(
				'event' => 'serializer.post_serialize',
				'method' => 'onPostSerialize',
				'class' => 'App\Entity\PurchaseOffer', // if no class, subscribe to every serialization
				'format' => 'json', // optional format
				'priority' => 0 // optional priority
			),
			array(
				'event' => 'serializer.post_serialize',
				'method' => 'onPostSerialize',
				'class' => 'App\Entity\BulkPurchaseOffer', // if no class, subscribe to every serialization
				'format' => 'json', // optional format
				'priority' => 0 // optional priority
			)
		);
	}

	public function onPostSerialize(ObjectEvent $event)
	{
		$offer = $event->getObject();
		$visitor = $event->getVisitor();
		$total = $offer->getPrice() * $offer->getWeight();
		if ($offer instanceof AuctionBid) {
			$top_bid = $this->em->getRepository(Bid::class)->findOneBy([
				'offer' => $offer,
				'isActive' => true,
			], ['price' => 'DESC']);
			$percentage = $this->em->getRepository(Parameter::class)->get('percentageNextBid')->getValue();
			$start_price = $total;
			if (null === $top_bid)
				$top_price = $start_price;
			else
				$top_price = $top_bid->getPrice();
			$next_bid = $top_price + ($top_price * $percentage);
			$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\Offer', 'start_price', null), $start_price);
			$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\Offer', 'top_price', null), $top_price);
			$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\Offer', 'next_bid', null), (int)$next_bid);
		}
		$fees = $this->helper->getFees($total, 'feesTransactionStatic', 'feesTransactionDynamic');
		$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\Offer', 'fees', null), $fees);
	}
}
