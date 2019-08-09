<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use JMS\Serializer\Annotation as Serializer;

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
	 * @Serializer\Groups({"transactions"})
	 */
	private $id;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $completed;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $paid;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $canceled;

	/**
	 * @ORM\Column(type="datetime")
	 * @Serializer\Groups({"transactions"})
	 */
	private $startDate;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 * @Serializer\Groups({"transactions"})
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
	 * @Serializer\Groups({"transactions"})
	 */
	private $total;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Offer")
	 * @ORM\JoinColumn(nullable=false)
	 * @Serializer\Groups({"transactions"})
	 */
	private $offer;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Gain")
	 * @ORM\JoinColumn(nullable=true)
	 */
	private $gain;

	/**
	 * @ORM\Column(type="uuid", unique=true)
	 */
	private $sellerKey;

	/**
	 * @ORM\Column(type="uuid", unique=true)
	 */
	private $buyerKey;

	public function __toString() : string
	{
		return $this->getId()." : ".$this->getTotal()."MAD";
	}

	/**
	 * @ORM\PrePersist
	 */
	public function onPrePersist()
	{
		$this->startDate = new \DateTime();
		$this->endDate = null;
		$this->sellerKey = Uuid::uuid4()->toString();
		$this->buyerKey = Uuid::uuid4()->toString();
		if (null === $this->paid)
			$this->paid = false;
		if (null === $this->completed)
			$this->completed = false;
		if (null === $this->canceled)
			$this->canceled = false;
	}

	public function endTransaction(): self
	{
		//Remember to verify keys
		if ($this->completed === false && $this->canceled === false)
		{
			$this->endDate = new \DateTime();
			$this->completed = true;
			$this->canceled = false;
		}

		return $this;
	}

	public function cancelTransaction(): self
	{
		//Remember to verify keys
		if ($this->completed === false && $this->canceld === false)
		{
			$this->endDate = new \DateTime();
			$this->completed = false;
			$this->canceled = true;
		}

		return $this;
	}

	public function setPaid(): self
	{
		$this->paid = true;

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

	public function isPaid(): ?bool
	{
		return $this->paid;
	}

	public function isCanceled(): ?bool
	{
		return $this->canceled;
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

	public function getGain(): ?Gain
	{
		return $this->gain;
	}

	public function setGain(?Gain $gain): self
	{
		$this->gain = $gain;

		return $this;
	}

	public function getSellerKey(): ?string
	{
		return $this->sellerKey;
	}

	public function getBuyerKey(): ?string
	{
		return $this->buyerKey;
	}
}
