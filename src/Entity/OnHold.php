<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpKernel\Excetpion\HttpException;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OnHoldRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class OnHold
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
	private $paid;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $refunded;

	/**
	 * @ORM\Column(type="datetime")
	 */
	private $date;

	/**
	 * @ORM\OneToOne(targetEntity="App\Entity\Offer", inversedBy="onHold", cascade={"persist", "remove"})
	 */
	private $offer;

	/**
	 * @ORM\OneToOne(targetEntity="App\Entity\Bid", inversedBy="onHold", cascade={"persist", "remove"})
	 */
	private $bid;

	/**
	 * @ORM\OneToOne(targetEntity="App\Entity\Gain", mappedBy="gainFrom", cascade={"persist", "remove"})
	 */
	private $gain;

	/**
	 * @ORM\Column(type="float")
	 */
	private $fees;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="onHolds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

	/**
	 * @ORM\PrePersist
	 * @ORM\PreUpdate
	 */
	public function checkAssociations()
         	{
         		if ($this->offer !== null && $this->bid !== null)
         			throw new HttpException(406, 'Could not create this transaction.');
         	}

	/**
	 * @ORM\PrePersist
	 */
	public function onPrePersist()
         	{
         		$this->setDate(new \DateTime());
         		$this->paid = false;
         		$this->refunded = false;
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

	public function getDate(): ?\DateTimeInterface
         	{
         		return $this->date;
         	}

	public function setDate(\DateTimeInterface $date): self
         	{
         		$this->date = $date;
         
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

	public function getBid(): ?Bid
         	{
         		return $this->bid;
         	}

	public function setBid(?Bid $bid): self
         	{
         		$this->bid = $bid;
         
         		return $this;
         	}

	public function getGain(): ?Gain
         	{
         		return $this->gain;
         	}

	public function setGain(Gain $gain): self
         	{
         		$this->gain = $gain;
         
         		// set the owning side of the relation if necessary
         		if ($this !== $gain->getGainFrom()) {
         			$gain->setGainFrom($this);
         		}
         
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}