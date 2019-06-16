<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ResellerRepository")
 * @Serializer\ExclusionPolicy("ALL")
 */
class Reseller extends User
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SaleOffer", mappedBy="buyer")
     */
    private $purchases;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PurchaseOffer", mappedBy="owner")
     */
    private $purchaseOffers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BulkPurchase", mappedBy="seller")
     */
    private $acceptedOffers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AuctionBid", mappedBy="owner")
     */
    private $auctionBids;

    public function __construct()
    {
        parent::__construct();
        $this->purchases = new ArrayCollection();
        $this->purchaseOffers = new ArrayCollection();
        $this->acceptedOffers = new ArrayCollection();
        $this->auctionBids = new ArrayCollection();
    }

    /**
     * @return Collection|SaleOffer[]
     */
    public function getPurchases(): Collection
    {
        return $this->purchases;
    }

    public function addPurchase(SaleOffer $purchase): self
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases[] = $purchase;
            $purchase->setBuyer($this);
        }

        return $this;
    }

    public function removePurchase(SaleOffer $purchase): self
    {
        if ($this->purchases->contains($purchase)) {
            $this->purchases->removeElement($purchase);
            // set the owning side to null (unless already changed)
            if ($purchase->getBuyer() === $this) {
                $purchase->setBuyer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|PurchaseOffer[]
     */
    public function getPurchaseOffers(): Collection
    {
        return $this->purchaseOffers;
    }

    public function addPurchaseOffer(PurchaseOffer $purchaseOffer): self
    {
        if (!$this->purchaseOffers->contains($purchaseOffer)) {
            $this->purchaseOffers[] = $purchaseOffer;
            $purchaseOffer->setOwner($this);
        }

        return $this;
    }

    public function removePurchaseOffer(PurchaseOffer $purchaseOffer): self
    {
        if ($this->purchaseOffers->contains($purchaseOffer)) {
            $this->purchaseOffers->removeElement($purchaseOffer);
            // set the owning side to null (unless already changed)
            if ($purchaseOffer->getOwner() === $this) {
                $purchaseOffer->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BulkPurchase[]
     */
    public function getAcceptedOffers(): Collection
    {
        return $this->acceptedOffers;
    }

    public function addAcceptedOffer(BulkPurchase $acceptedOffer): self
    {
        if (!$this->acceptedOffers->contains($acceptedOffer)) {
            $this->acceptedOffers[] = $acceptedOffer;
            $acceptedOffer->setSeller($this);
        }

        return $this;
    }

    public function removeAcceptedOffer(BulkPurchase $acceptedOffer): self
    {
        if ($this->acceptedOffers->contains($acceptedOffer)) {
            $this->acceptedOffers->removeElement($acceptedOffer);
            // set the owning side to null (unless already changed)
            if ($acceptedOffer->getSeller() === $this) {
                $acceptedOffer->setSeller(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AuctionBid[]
     */
    public function getAuctionBids(): Collection
    {
        return $this->auctionBids;
    }

    public function addAuctionBid(AuctionBid $auctionBid): self
    {
        if (!$this->auctionBids->contains($auctionBid)) {
            $this->auctionBids[] = $auctionBid;
            $auctionBid->setOwner($this);
        }

        return $this;
    }

    public function removeAuctionBid(AuctionBid $auctionBid): self
    {
        if ($this->auctionBids->contains($auctionBid)) {
            $this->auctionBids->removeElement($auctionBid);
            // set the owning side to null (unless already changed)
            if ($auctionBid->getOwner() === $this) {
                $auctionBid->setOwner(null);
            }
        }

        return $this;
    }
}
