<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransactionRepository")
 */
class Transaction
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $status;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $start_date;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $end_date;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="purchases")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $buyer;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sales")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $seller;

	/**
	 * @ORM\Column(type="bigint")
	 */
	private $total;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Offer", inversedBy="transactions")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $offer;

	/**
	 * @ORM\Column(type="uuid", unique=true)
	 * @ORM\GeneratedValue(strategy="CUSTOM")
	 * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
	 */
	private $seller_key;

	/**
	 * @ORM\Column(type="uuid", unique=true)
	 * @ORM\GeneratedValue(strategy="CUSTOM")
	 * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
	 */
	private $buyer_key;

	public function __construct()
	{
		$this->setStatus(0);
		$this->start_date = new \DateTime();
		$this->end_date = 0;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getStatus(): ?bool
	{
		return $this->status;
	}

	public function getStartDate(): ?\DateTimeInterface
	{
		return $this->start_date;
	}

	public function getEndDate(): ?\DateTimeInterface
	{
		return $this->end_date;
	}

	public function getBuyer(): ?User
	{
		return $this->buyer;
	}

	public function setBuyer(?User $buyer): self
	{
		$this->buyer = $buyer;

		return $this;
	}

	public function getSeller(): ?User
	{
		return $this->seller;
	}

	public function setSeller(?User $seller): self
	{
		$this->seller = $seller;

		return $this;
	}

	public function endTransaction(): self
	{
		//Remember to verify keys
		if ($this->state === 0)
		{
			$this->end_date = new \DateTime();
			$this->state = 1;
		}

		return $this;
	}

	public function getTotal(): ?int
	{
		return $this->total;
	}

	public function setTotal(int $total): self
	{
		$this->total = $total;

		return $this;
	}

	public function getOffer(): ?Offer
	{
		return $this->offer;
	}

	public function setOffer(?Offer $offer): self
	{
		$this->offer = $offer;

		return $this;
	}

	public function getSellerKey(): ?string
	{
		return $this->seller_key;
	}

	public function setSellerKey(string $seller_key): self
	{
		$this->seller_key = $seller_key;

		return $this;
	}

	public function getBuyerKey(): ?string
	{
		return $this->buyer_key;
	}

	public function setBuyerKey(string $buyer_key): self
	{
		$this->buyer_key = $buyer_key;

		return $this;
	}
}
