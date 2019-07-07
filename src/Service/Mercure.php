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
}
