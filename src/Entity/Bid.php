<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

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
	 */
	private $price;

	/**
	 * @ORM\Column(type="datetime")
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
	 */
	private $bidder;

	/**
	 * @ORM\OneToOne(targetEntity="App\Entity\OnHold", mappedBy="bid", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $onHold;

	/**
	 * @ORM\PrePersist
	 */
	public function onPrePersist()
	{
		$this->date = new \DateTime();
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

	public function getOnHold(): ?OnHold
	{
		return $this->onHold;
	}

	public function setOnHold(?OnHold $onHold): self
	{
		$this->onHold = $onHold;

		// set (or unset) the owning side of the relation if necessary
		$newBid = $onHold === null ? null : $this;
		if ($newBid !== $onHold->getBid()) {
			$onHold->setBid($newBid);
		}

		return $this;
	}
}
