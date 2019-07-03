<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class MappedPurchase
{
	/**
	 * @ORM\Column(type="bigint")
	 */
	private $weight;

	/**
	 * @ORM\Column(type="bigint")
	 */
	private $price;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $date;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $accepted;

	/**
	 * @ORM\PrePersist
	 */
	public function onPrePersist()
	{
		$this->date = new \DateTime();
		$this->accepted = false;
	}

	public function getWeight(): ?int
	{
		return $this->weight;
	}

	public function setWeight(int $weight): self
	{
		$this->weight = $weight;

		return $this;
	}

	public function getPrice(): ?int
	{
		return $this->price;
	}

	public function setPrice(int $price): self
	{
		$this->price = $price;

		return $this;
	}

	public function getDate(): ?\DateTimeInterface
	{
		return $this->date;
	}

	public function isAccepted(): ?bool
	{
		return $this->accepted;
	}

	public function setAccepted(): self
	{
		$this->accepted = true;

		return $this;
	}
}
