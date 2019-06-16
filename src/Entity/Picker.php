<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PickerRepository")
 */
class Picker extends User
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\SaleOffer", mappedBy="owner")
     */
    private $saleOffers;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Purchase", mappedBy="seller")
     */
    private $acceptedOffers;

    public function __construct()
    {
        parent::__construct();
        $this->saleOffers = new ArrayCollection();
        $this->acceptedOffers = new ArrayCollection();
    }

    /**
     * @return Collection|SaleOffer[]
     */
    public function getSaleOffers(): Collection
    {
        return $this->saleOffers;
    }

    public function addSaleOffer(SaleOffer $saleOffer): self
    {
        if (!$this->saleOffers->contains($saleOffer)) {
            $this->saleOffers[] = $saleOffer;
            $saleOffer->setOwner($this);
        }

        return $this;
    }

    public function removeSaleOffer(SaleOffer $saleOffer): self
    {
        if ($this->saleOffers->contains($saleOffer)) {
            $this->saleOffers->removeElement($saleOffer);
            // set the owning side to null (unless already changed)
            if ($saleOffer->getOwner() === $this) {
                $saleOffer->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Purchase[]
     */
    public function getAcceptedOffers(): Collection
    {
        return $this->acceptedOffers;
    }

    public function addAcceptedOffer(Purchase $acceptedOffer): self
    {
        if (!$this->acceptedOffers->contains($acceptedOffer)) {
            $this->acceptedOffers[] = $acceptedOffer;
            $acceptedOffer->setSeller($this);
        }

        return $this;
    }

    public function removeAcceptedOffer(Purchase $acceptedOffer): self
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
}
