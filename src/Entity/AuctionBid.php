<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuctionBidRepository")
 * @Serializer\ExclusionPolicy("ALL")
 */
class AuctionBid extends Offer
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Reseller", inversedBy="auctionBids")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bid", mappedBy="offer")
     */
    private $bids;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $end_price;

    public function __construct()
    {
        parent::__construct();
        $this->bids = new ArrayCollection();
    }

    public function getOwner(): ?Reseller
    {
        return $this->owner;
    }

    public function setOwner(?Reseller $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Bid[]
     */
    public function getBids(): Collection
    {
        return $this->bids;
    }

    public function addBid(Bid $bid): self
    {
        if (!$this->bids->contains($bid)) {
            $this->bids[] = $bid;
            $bid->setOffer($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        if ($this->bids->contains($bid)) {
            $this->bids->removeElement($bid);
            // set the owning side to null (unless already changed)
            if ($bid->getOffer() === $this) {
                $bid->setOffer(null);
            }
        }

        return $this;
    }

    public function getEndPrice(): ?int
    {
        return $this->end_price;
    }

    public function setEndPrice(int $end_price): self
    {
        $this->end_price = $end_price;

        return $this;
    }
}
