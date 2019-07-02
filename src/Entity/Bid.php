<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="App\Repository\BidRepository")
 */
class Bid
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="bigint")
	 * @Serializer\Groups({"list-offers"})
     * @Serializer\Exclude(if="object.getIsActive() === false")
	 */
	private $price;

	/**
	 * @ORM\Column(type="datetime")
	 * @Serializer\Groups({"list-offers"})
     * @Serializer\Exclude(if="object.getIsActive() === false")
	 */
	private $date;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\AuctionBid", inversedBy="bids")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $offer;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Buyer", inversedBy="bids")
	 * @ORM\JoinColumn(nullable=false)
	 * @Serializer\Groups({"list-offers"})
     * @Serializer\Exclude(if="object.getIsActive() === false")
	 */
	private $bidder;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $isActive;

	/**
	 * @ORM\PrePersist
	 */
	public function onPrePersist()
	{
		$this->date = new \DateTime();
		$this->isActive = true;
	}

	public function getId(): ?int
	{
		return $this->id;
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

	public function getOffer(): ?AuctionBid
	{
		return $this->offer;
	}

	public function setOffer(?AuctionBid $offer): self
	{
		$this->offer = $offer;

		return $this;
	}

	public function getBidder(): ?Buyer
	{
		return $this->bidder;
	}

	public function setBidder(?Buyer $bidder): self
	{
		$this->bidder = $bidder;

		return $this;
	}

	public function getIsActive(): ?bool
	{
		return $this->isActive;
	}

	public function setIsActive(bool $isActive): self
	{
		$this->isActive = $isActive;

		return $this;
	}
}
