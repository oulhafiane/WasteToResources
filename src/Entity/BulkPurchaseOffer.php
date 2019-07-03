<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints AS Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BulkPurchaseOfferRepository")
 */
class BulkPurchaseOffer extends Offer
{
	/**
	 * @ORM\Column(type="bigint")
	 * @Groups({"new-offer", "list-offers"})
	 * @Assert\LessThanOrEqual(propertyPath="weight", groups={"new-offer"})

	 */
	private $min;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BulkPurchase", mappedBy="offer")
     */
    private $bulkPurchases;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Buyer", inversedBy="bulkPurchaseOffers")
     * @ORM\JoinColumn(nullable=false)
	 * @Groups({"list-offers"})
     */
    private $owner;

    public function __construct()
    {
        parent::__construct();
        $this->bulkPurchases = new ArrayCollection();
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

    /**
     * @return Collection|BulkPurchase[]
     */
    public function getBulkPurchases(): Collection
    {
        return $this->bulkPurchases;
    }

    public function addBulkPurchase(BulkPurchase $bulkPurchase): self
    {
        if (!$this->bulkPurchases->contains($bulkPurchase)) {
            $this->bulkPurchases[] = $bulkPurchase;
            $bulkPurchase->setOffer($this);
        }

        return $this;
    }

    public function removeBulkPurchase(BulkPurchase $bulkPurchase): self
    {
        if ($this->bulkPurchases->contains($bulkPurchase)) {
            $this->bulkPurchases->removeElement($bulkPurchase);
            // set the owning side to null (unless already changed)
            if ($bulkPurchase->getOffer() === $this) {
                $bulkPurchase->setOffer(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?Buyer
    {
        return $this->owner;
    }

    public function setOwner(?Buyer $owner): self
    {
        $this->owner = $owner;

        return $this;
    }
}
