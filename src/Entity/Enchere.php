<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EnchereRepository")
 */
class Enchere
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
    private $prix;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OffreEnchere", inversedBy="encheres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $OffreEnchere;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GrossisteAcheteur", inversedBy="encheres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $GrossisteAcheteur;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

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

    public function getOffreEnchere(): ?OffreEnchere
    {
        return $this->OffreEnchere;
    }

    public function setOffreEnchere(?OffreEnchere $OffreEnchere): self
    {
        $this->OffreEnchere = $OffreEnchere;

        return $this;
    }

    public function getGrossisteAcheteur(): ?GrossisteAcheteur
    {
        return $this->GrossisteAcheteur;
    }

    public function setGrossisteAcheteur(?GrossisteAcheteur $GrossisteAcheteur): self
    {
        $this->GrossisteAcheteur = $GrossisteAcheteur;

        return $this;
    }
}
