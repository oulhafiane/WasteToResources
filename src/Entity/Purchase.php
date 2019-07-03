<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="App\Repository\PurchaseRepository")
 */
class Purchase extends MappedPurchase
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\PurchaseOffer", inversedBy="purchases")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $offer;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Picker", inversedBy="acceptedOffers")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $seller;

    public function getId(): ?int
    {
        return $this->id;
    }

	public function getOffer(): ?PurchaseOffer
	{
		return $this->offer;
	}

	public function setOffer(?PurchaseOffer $offer): self
	{
		$this->offer = $offer;

		return $this;
	}

	public function getSeller(): ?Picker
	{
		return $this->seller;
	}

	public function setSeller(?Picker $seller): self
	{
		$this->seller = $seller;

		return $this;
	}
}
