<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OffreAchatRepository")
 */
class OffreAchat extends Offre
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\GrossisteRevendeur", inversedBy="offreAchats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $proprietaire;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Achat", mappedBy="offre")
     */
    private $achats;

    public function __construct()
    {
        $this->achats = new ArrayCollection();
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

    /**
     * @return Collection|Achat[]
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(Achat $achat): self
    {
        if (!$this->achats->contains($achat)) {
            $this->achats[] = $achat;
            $achat->setOffre($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): self
    {
        if ($this->achats->contains($achat)) {
            $this->achats->removeElement($achat);
            // set the owning side to null (unless already changed)
            if ($achat->getOffre() === $this) {
                $achat->setOffre(null);
            }
        }

        return $this;
    }
}
