<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PurchaseRepository")
 */
class Purchase
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $weight;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

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

	public function __construct()
	{
		$this->date = new \DateTime();
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
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
