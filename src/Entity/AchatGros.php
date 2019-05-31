<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AchatGrosRepository")
 */
class AchatGros
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
     * @ORM\ManyToOne(targetEntity="App\Entity\OffreAchatGros", inversedBy="achatsGros")
     * @ORM\JoinColumn(nullable=false)
     */
    private $offre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GrossisteRevendeur", inversedBy="achats")
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

    public function getOffre(): ?OffreAchatGros
    {
        return $this->offre;
    }

    public function setOffre(?OffreAchatGros $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getAcheteur(): ?GrossisteRevendeur
    {
        return $this->acheteur;
    }

    public function setAcheteur(?GrossisteRevendeur $acheteur): self
    {
        $this->acheteur = $acheteur;

        return $this;
    }
}
