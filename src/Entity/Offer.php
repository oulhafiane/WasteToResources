<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class Offer
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $price;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $with_transport;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $start_date;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $end_date;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\TypeOffer", inversedBy="offers")
	 * @ORM\JoinColumn(nullable=false)
	 */
	protected $type_offer;

	/**
	 * @ORM\Column(type="bigint")
	 */
	protected $weight;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $creation_date;

	/**
	 * @ORM\Column(type="array", nullable=true)
	 */
	protected $pictures = [];

	/**
	 * @ORM\Column(type="array")
	 */
	protected $locations = [];

	public function __construct()
	{
		$this->creaction_date = new \DateTime();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getPrice(): ?int
	{
		return $this->price;
	}

	public function setPrice(int $price): self
	{
		$this->price = $price;

		return $this;
	}

	public function getWithTransport(): ?bool
	{
		return $this->with_transport;
	}

	public function setWithTransport(bool $with_transport): self
	{
		$this->with_transport = $with_transport;

		return $this;
	}

	public function getStartDate(): ?\DateTimeInterface
	{
		return $this->start_date;
	}

	public function setStartDate(\DateTimeInterface $start_date): self
	{
		$this->start_date = $start_date;

		return $this;
	}

	public function getEndDate(): ?\DateTimeInterface
	{
		return $this->end_date;
	}

	public function setEndDate(\DateTimeInterface $end_date): self
	{
		$this->end_date = $end_date;

		return $this;
	}

	public function getTypeOffer(): ?TypeOffer
	{
		return $this->type_offer;
	}

	public function setTypeOffer(?TypeOffer $type_offer): self
	{
		$this->type_offer = $type_offer;

		return $this;
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

	public function getCreationDate(): ?\DateTimeInterface
	{
		return $this->creation_date;
	}

	public function getPictures(): ?array
	{
		return $this->pictures;
	}

	public function setPictures(?array $pictures): self
	{
		$this->pictures = $pictures;

		return $this;
	}

	public function getLocations(): ?array
	{
		return $this->locations;
	}

	public function setLocations(array $locations): self
	{
		$this->locations = $locations;

		return $this;
	}
}
