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
	protected $withTransport;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $startDate;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $endDate;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="offers")
	 * @ORM\JoinColumn(nullable=false)
	 */
	protected $category;

	/**
	 * @ORM\Column(type="bigint")
	 */
	protected $weight;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $creationDate;

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
		$this->creactionDate = new \DateTime();
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
		return $this->withTransport;
	}

	public function setWithTransport(bool $withTransport): self
	{
		$this->withTransport = $withTransport;

		return $this;
	}

	public function getStartDate(): ?\DateTimeInterface
	{
		return $this->startDate;
	}

	public function setStartDate(\DateTimeInterface $startDate): self
	{
		$this->startDate = $startDate;

		return $this;
	}

	public function getEndDate(): ?\DateTimeInterface
	{
		return $this->endDate;
	}

	public function setEndDate(\DateTimeInterface $endDate): self
	{
		$this->endDate = $endDate;

		return $this;
	}

	public function getCategory(): ?Category
	{
		return $this->category;
	}

	public function setCategory(?Category $category): self
	{
		$this->category = $category;

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
		return $this->creationDate;
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
