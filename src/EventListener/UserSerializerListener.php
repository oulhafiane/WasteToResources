<?php

namespace App\EventListener;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\Metadata\StaticPropertyMetadata;

class UserSerializerListener implements EventSubscriberInterface
{
	public static function getSubscribedEvents()
	{
		return array(
			array(
				'event' => 'serializer.post_serialize',
				'method' => 'onPostSerialize',
				'class' => 'App\Entity\Picker', // if no class, subscribe to every serialization
				'format' => 'json', // optional format
				'priority' => 0, // optional priority
			),
			array(
				'event' => 'serializer.post_serialize',
				'method' => 'onPostSerialize',
				'class' => 'App\Entity\Reseller', // if no class, subscribe to every serialization
				'format' => 'json', // optional format
				'priority' => 0, // optional priority
			),
			array(
				'event' => 'serializer.post_serialize',
				'method' => 'onPostSerialize',
				'class' => 'App\Entity\Buyer', // if no class, subscribe to every serialization
				'format' => 'json', // optional format
				'priority' => 0, // optional priority
			)
		);
	}

	public function onPostSerialize(ObjectEvent $event)
	{
		try {
			$groups = $event->getContext()->getAttribute('groups');
		}catch (\Exception $ex) {
			$groups = [];
		}
		if (!in_array('infos', $groups))
			return ;

		$user = $event->getObject();
		$onholds = $user->getGains();
		$total = 0;
		foreach ($onholds as $onhold) {
			if ($onhold->isPaid() === false && $onhold->isRefunded() === false)
				$total += $onhold->getFees();
		}
		$visitor = $event->getVisitor();
		$visitor->visitProperty(new StaticPropertyMetadata('App\Entity\User', 'onhold', null), $total);
	}
}
