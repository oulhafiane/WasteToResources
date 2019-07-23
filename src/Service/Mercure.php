<?php

namespace App\Service;

use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;

class Mercure
{
	private $publisher;

	public function __construct(Publisher $publisher)
	{
		$this->publisher = $publisher;
	}

	public function publish($topic, $data, $targets = [])
	{
		try {
			$update = new Update(
				$topic,
				$data,
				$targets
			);
			$this->publisher->__invoke($update);
		} catch (\Exception $ex) {}
	}

	public function publishNotification($notifiaction, $user)
	{
		$this->publish(
			'waste_to_resources/notifications',
			json_encode([
				'message' => $notification->getMessage(),
				'type' => $notification->getType(),
				'reference' => $notification->getReference()
			]),
			['waste_to_resources/user/'.$user->getEmail()]
		);
	}

	public function publishMessage($message, $user)
	{
		$this->publish(
			'waste_to_resources/messages',
			json_encode([
				'message' => $message->getText(),
				'sender' => $message->getSender()->getEmail()
			]),
			['waste_to_resources/user/'.$user->getEmail()]
		);
	}

	public function updateLiveAuction($offer, $user, $bid_price, $percentage)
	{
		$this->publish(
				'waste_to_resources/offers/'.$offer->getId(),
				json_encode([
					'next_bid' => (int)($bid_price + ($bid_price * $percentage)),
					'price' => $bid_price,
					'first_name' => $user->getFirstName(),
					'last_name' => $user->getLastName(),
					'email' => $user->getEmail()
				])
		);

	}
}
