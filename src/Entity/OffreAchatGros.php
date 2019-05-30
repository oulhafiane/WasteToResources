<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OffreAchatGrosRepository")
 */
class OffreAchatGros extends Offre
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GrossisteAcheteur", inversedBy="offreAchatGros")
     * @ORM\JoinColumn(nullable=false)
     */
    private $proprietaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AchatGros", mappedBy="offre")
     */
    private $achatsGros;

    public function __construct()
    {
        $this->achatsGros = new ArrayCollection();
    }

    public function getProprietaire(): ?GrossisteAcheteur
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?GrossisteAcheteur $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    /**
     * @return Collection|AchatGros[]
     */
    public function getAchatsGros(): Collection
    {
        return $this->achatsGros;
    }

    public function addAchatsGro(AchatGros $achatsGro): self
    {
        if (!$this->achatsGros->contains($achatsGro)) {
            $this->achatsGros[] = $achatsGro;
            $achatsGro->setOffre($this);
        }

        return $this;
    }

    public function removeAchatsGro(AchatGros $achatsGro): self
    {
        if ($this->achatsGros->contains($achatsGro)) {
            $this->achatsGros->removeElement($achatsGro);
            // set the owning side to null (unless already changed)
            if ($achatsGro->getOffre() === $this) {
                $achatsGro->setOffre(null);
            }
        }

        return $this;
    }
}
