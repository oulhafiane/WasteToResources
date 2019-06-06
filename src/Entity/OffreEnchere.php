<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OffreEnchereRepository")
 */
class OffreEnchere extends Offre
{
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $prixFin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Enchere", mappedBy="OffreEnchere")
     */
    private $encheres;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GrossisteRevendeur", inversedBy="offreEncheres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $proprietaire;

    public function __construct()
    {
        $this->encheres = new ArrayCollection();
    }

    public function getPrixFin(): ?int
    {
        return $this->prixFin;
    }

    public function setPrixFin(?int $prixFin): self
    {
        $this->prixFin = $prixFin;

        return $this;
    }

    /**
     * @return Collection|Enchere[]
     */
    public function getEncheres(): Collection
    {
        return $this->encheres;
    }

    public function addEnchere(Enchere $enchere): self
    {
        if (!$this->encheres->contains($enchere)) {
            $this->encheres[] = $enchere;
            $enchere->setOffreEnchere($this);
        }

        return $this;
    }

    public function removeEnchere(Enchere $enchere): self
    {
        if ($this->encheres->contains($enchere)) {
            $this->encheres->removeElement($enchere);
            // set the owning side to null (unless already changed)
            if ($enchere->getOffreEnchere() === $this) {
                $enchere->setOffreEnchere(null);
            }
        }

        return $this;
    }

    public function getProprietaire(): ?GrossisteRevendeur
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?GrossisteRevendeur $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }
}
