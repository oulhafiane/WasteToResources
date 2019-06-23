<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\HasLifecycleCallbacks
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
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	private $completed;

	/**
	 * @ORM\Column(type="boolean", nullable=true)
	 */
	private $canceled;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $startDate;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $endDate;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="purchasesTransactions")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $buyer;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="salesTransactions")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $seller;

	/**
	 * @ORM\Column(type="bigint")
	 */
	private $total;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Offer")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $offer;

	/**
	 * @ORM\Column(type="uuid", unique=true)
	 */
	private $sellerKey;

	/**
	 * @ORM\Column(type="uuid", unique=true)
	 */
	private $buyerKey;

	/**
	 * @ORM\PrePersist
	 */
	public function onPrePersist()
	{
		$this->startDate = new \DateTime();
		$this->endDate = null;
		$this->setSellerKey(Uuid::uuid4()->toString());
		$this->setBuyerKey(Uuid::uuid4()->toString());
	}

	public function endTransaction(): self
	{
		//Remember to verify keys
		if ($this->completed === False && $this->canceld === False)
		{
			$this->endDate = new \DateTime();
			$this->completed = True;
			$this->canceled = False;
		}

		return $this;
	}

	public function cancelTransaction(): self
	{
		//Remember to verify keys
		if ($this->completed === False && $this->canceld === False)
		{
			$this->endDate = new \DateTime();
			$this->completed = False;
			$this->canceled = True;
		}

		return $this;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function isCompleted(): ?bool
	{
		return $this->completed;
	}

	public function isCanceled(): ?bool
	{
		return $this->completed;
	}

	public function getStartDate(): ?\DateTimeInterface
	{
		return $this->startDate;
	}

	public function getEndDate(): ?\DateTimeInterface
	{
		return $this->endDate;
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
		return $this->sellerKey;
	}

	public function setSellerKey(string $sellerKey): self
	{
		$this->sellerKey = $sellerKey;

		return $this;
	}

	public function getBuyerKey(): ?string
	{
		return $this->buyerKey;
	}

	public function setBuyerKey(string $buyerKey): self
	{
		$this->buyerKey = $buyerKey;

		return $this;
	}
}
