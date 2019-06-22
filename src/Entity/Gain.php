<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="App\Repository\GainRepository")
 */
class Gain
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="bigint")
     */
    private $total;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\OnHold", inversedBy="gain", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $gainFrom;

	/**
	 * @ORM\PrePersist
	 */
	public function onPrePersist()
	{
		$this->setDate(new \DateTime());
	}

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getGainFrom(): ?OnHold
    {
        return $this->gainFrom;
    }

    public function setGainFrom(OnHold $gainFrom): self
    {
        $this->gainFrom = $gainFrom;

        return $this;
    }
}
