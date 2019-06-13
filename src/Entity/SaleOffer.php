<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SaleOfferRepository")
 */
class SaleOffer extends Offer
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Picker", inversedBy="saleOffers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $owner;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Reseller", inversedBy="purchases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $buyer;

    public function getOwner(): ?Picker
    {
        return $this->owner;
    }

    public function setOwner(?Picker $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getBuyer(): ?Reseller
    {
        return $this->buyer;
    }

    public function setBuyer(?Reseller $buyer): self
    {
        $this->buyer = $buyer;

        return $this;
    }
}
