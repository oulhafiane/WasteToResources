<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AuctionBidRepository")
 */
class AuctionBid extends Offer
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Reseller", inversedBy="auctionBids")
     * @ORM\JoinColumn(nullable=false)
	 * @Serializer\Groups("list-offers")
     */
    private $owner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bid", mappedBy="offer")
     * @Serializer\Groups({"new-offer", "specific-offer"})
	 * @ORM\OrderBy({"price" = "DESC"})
     */
    private $bids;

    /**
     * @ORM\Column(type="bigint", nullable=true)
	 * @Serializer\Type("integer")
	 * @Serializer\Groups({"new-offer", "list-offers"})
	 * @Assert\Positive(groups={"new-offer"})
     */
    private $end_price;

	/**
	 * @ORM\Column(type="smallint", nullable=true)
	 * @Serializer\Type("integer")
	 * @Serializer\Groups({"new-offer", "list-offers"})
	 * @Assert\Choice({0, 1, 2}, groups={"new-offer"})
	 */
	private $period;

	/**
	 * @ORM\Column(type="bigint")
	 * @Serializer\Type("integer")
	 * @Serializer\Groups({"new-offer", "list-offers"})
	 * @Assert\Positive(groups={"new-offer"})
	 */
	private $warranty;

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

    public function getPeriod(): ?int
    {
        return $this->period;
    }

    public function setPeriod(int $period): self
    {
        $this->period = $period;

        return $this;
    }

    public function getWarranty(): ?int
    {
        return $this->warranty;
    }

    public function setWarranty(int $warranty): self
    {
        $this->warranty = $warranty;

        return $this;
    }
}
