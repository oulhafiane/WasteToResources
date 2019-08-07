<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpKernel\Excetpion\HttpException;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GainRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Gain
{
	const CREATOR = 2;
	const NOTCREATOR = 1;

	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Offer", inversedBy="gains")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $offer;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="gains")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $paid;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $refunded;

	/**
	 * @ORM\Column(type="smallint")
	 */
	private $type = null;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $date;

	/**
	 * @ORM\Column(type="float")
	 */
	private $fees;

	public function __toString() : string
	{
		return $this->getId()." : ".$this->getFees()."MAD";
	}

	/**
	 * @ORM\PrePersist
	 */
	public function onPrePersist()
	{
		$this->setDate(new \DateTime());
		$this->paid = false;
		$this->refunded = false;
		if (null == $this->type)
			$this->type = self::NOTCREATOR;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function isPaid(): ?bool
	{
		return $this->paid;
	}

	public function setPaid(): self
	{
		if ($this->paid === False && $this->refunded === False)
		{
			$this->paid = True;
			$this->refunded = False;
		}

		return $this;
	}

	public function isRefunded(): ?bool
	{
		return $this->refunded;
	}

	public function setRefunded(): self
	{
		if ($this->paid === False && $this->refunded === False)
		{
			$this->paid = False;
			$this->refunded = True;
		}

		return $this;
	}

	public function getType(): ?int
	{
		return $this;
	}

	public function setType(?int $type): ?self
	{
		$this->type = $type;

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

	public function getUser(): ?User
	{
		return $this->user;
	}

	public function setUser(?User $user): self
	{
		$this->user = $user;

		return $this;
	}

	public function getDate(): ?\DateTimeInterface
	{
		return $this->date;
	}

	public function setDate(\DateTimeInterface $date): self
	{
		$this->date = $date;

		return $this;
	}

	public function getFees(): ?float
	{
		return $this->fees;
	}

	public function setFees(float $fees): self
	{
		$this->fees = $fees;

		return $this;
	}
}
