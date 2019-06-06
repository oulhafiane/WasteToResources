<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OffreVenteRepository")
 */
class OffreVente extends Offre
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Collecteur", inversedBy="offresVente")
     * @ORM\JoinColumn(nullable=false)
     */
    private $proprietaire;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GrossisteRevendeur", inversedBy="achats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $acheteur;

    public function getProprietaire(): ?Collecteur
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?Collecteur $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

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
