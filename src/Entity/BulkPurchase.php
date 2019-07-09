<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="App\Repository\BulkPurchaseRepository")
 */
class BulkPurchase extends MappedPurchase
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\BulkPurchaseOffer", inversedBy="purchases")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $offer;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Reseller", inversedBy="acceptedOffers")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $seller;

	public function __toString()
	{
		return "Offer: ".$this->getOffer();
	}

    public function getId(): ?int
    {
        return $this->id;
    }

	public function getOffer(): ?BulkPurchaseOffer
	{
		return $this->offer;
	}

	public function setOffer(?BulkPurchaseOffer $offer): self
	{
		$this->offer = $offer;

		return $this;
	}

	public function getSeller(): ?Reseller
	{
		return $this->seller;
	}

	public function setSeller(?Reseller $seller): self
	{
		$this->seller = $seller;

		return $this;
	}
}
