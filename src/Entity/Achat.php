<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AchatRepository")
 */
class Achat
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
    private $qte;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OffreAchat", inversedBy="achats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $offre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Collecteur", inversedBy="achats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $acheteur;

    public function __construct()
    {
	$this->date = new \DateTime();
	$this->status = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): self
    {
        $this->qte = $qte;

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

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getOffre(): ?OffreAchat
    {
        return $this->offre;
    }

    public function setOffre(?OffreAchat $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getAcheteur(): ?Collecteur
    {
        return $this->acheteur;
    }

    public function setAcheteur(?Collecteur $acheteur): self
    {
        $this->acheteur = $acheteur;

        return $this;
    }
}
