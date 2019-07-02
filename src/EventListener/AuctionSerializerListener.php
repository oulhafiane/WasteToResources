<?php

namespace App\EventListener;

use App\Entity\Bid;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\StaticPropertyMetadata;
use Doctrine\ORM\EntityManagerInterface;

class AuctionSerializerListener implements EventSubscriberInterface
{
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
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
			)
		);
	}

	public function onPostSerialize(ObjectEvent $event)
	{
		$offer = $event->getObject();
		$top_bid = $this->em->getRepository(Bid::class)->findOneBy([
			'offer' => $offer,
			'isActive' => true,
		], ['price' => 'DESC']);
		$start_price = $offer->getPrice() * $offer->getWeight();
		if (null === $top_bid)
			$top_price = $start_price;
		else
			$top_price = $top_bid->getPrice();
		$visitor = $event->getVisitor();
		$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\AuctionBid', 'start_price', null), $start_price);
		$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\AuctionBid', 'top_price', null), $top_price);
	}
}
