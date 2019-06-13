<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
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

	public function __construct()
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
}
