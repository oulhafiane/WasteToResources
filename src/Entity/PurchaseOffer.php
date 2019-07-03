<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints AS Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PurchaseOfferRepository")
 */
class PurchaseOffer extends Offer
{
	/**
	 * @ORM\Column(type="bigint")
	 * @Groups({"new-offer", "list-offers"})
	 * @Assert\LessThanOrEqual(propertyPath="weight", groups={"new-offer"})
	 */
	private $min;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Reseller", inversedBy="purchaseOffers")
     * @ORM\JoinColumn(nullable=false)
	 * @Groups("list-offers")
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Purchase", mappedBy="offer")
     */
    private $purchases;

    public function __construct()
    {
        parent::__construct();
        $this->purchases = new ArrayCollection();
    }

	public function getMin(): ?int
	{
		return $this->min;
	}

	public function setMin(?int $min): self
	{
		$this->min = $min;

		return $this;
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
     * @return Collection|Purchase[]
     */
    public function getPurchases(): Collection
    {
        return $this->purchases;
    }

    public function addPurchase(Purchase $purchase): self
    {
        if (!$this->purchases->contains($purchase)) {
            $this->purchases[] = $purchase;
            $purchase->setOffer($this);
        }

        return $this;
    }

    public function removePurchase(Purchase $purchase): self
    {
        if ($this->purchases->contains($purchase)) {
            $this->purchases->removeElement($purchase);
            // set the owning side to null (unless already changed)
            if ($purchase->getOffer() === $this) {
                $purchase->setOffer(null);
            }
        }

        return $this;
    }
}
