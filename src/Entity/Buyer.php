<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BuyerRepository")
 */
class Buyer extends User
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BulkPurchaseOffer", mappedBy="owner")
     */
    private $bulkPurchaseOffers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bid", mappedBy="bidder")
     */
    private $bids;

    public function __construct()
    {
        parent::__construct();
        $this->bulkPurchaseOffers = new ArrayCollection();
        $this->bids = new ArrayCollection();
    }

    /**
     * @return Collection|BulkPurchaseOffer[]
     */
    public function getBulkPurchaseOffers(): Collection
    {
        return $this->bulkPurchaseOffers;
    }

    public function addBulkPurchaseOffer(BulkPurchaseOffer $bulkPurchaseOffer): self
    {
        if (!$this->bulkPurchaseOffers->contains($bulkPurchaseOffer)) {
            $this->bulkPurchaseOffers[] = $bulkPurchaseOffer;
            $bulkPurchaseOffer->setOwner($this);
        }

        return $this;
    }

    public function removeBulkPurchaseOffer(BulkPurchaseOffer $bulkPurchaseOffer): self
    {
        if ($this->bulkPurchaseOffers->contains($bulkPurchaseOffer)) {
            $this->bulkPurchaseOffers->removeElement($bulkPurchaseOffer);
            // set the owning side to null (unless already changed)
            if ($bulkPurchaseOffer->getOwner() === $this) {
                $bulkPurchaseOffer->setOwner(null);
            }
        }

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
            $bid->setBidder($this);
        }

        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        if ($this->bids->contains($bid)) {
            $this->bids->removeElement($bid);
            // set the owning side to null (unless already changed)
            if ($bid->getBidder() === $this) {
                $bid->setBidder(null);
            }
        }

        return $this;
    }
}
