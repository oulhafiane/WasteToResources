<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CollecteurRepository")
 */
class Collecteur extends Utilisateur
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OffreVente", mappedBy="proprietaire")
     */
    private $offreVentes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Achat", mappedBy="acheteur")
     */
    private $achats;

    public function __construct()
    {
        parent::__construct();
        $this->offreVentes = new ArrayCollection();
        $this->achats = new ArrayCollection();
    }

    /**
     * @return Collection|OffreVente[]
     */
    public function getOffreVentes(): Collection
    {
        return $this->offreVentes;
    }

    public function addOffreVente(OffreVente $offreVente): self
    {
        if (!$this->offreVentes->contains($offreVente)) {
            $this->offreVentes[] = $offreVente;
            $offreVente->setProprietaire($this);
        }

        return $this;
    }

    public function removeOffreVente(OffreVente $offreVente): self
    {
        if ($this->offreVentes->contains($offreVente)) {
            $this->offreVentes->removeElement($offreVente);
            // set the owning side to null (unless already changed)
            if ($offreVente->getProprietaire() === $this) {
                $offreVente->setProprietaire(null);
            }
        }

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
            $achat->setAcheteur($this);
        }

        return $this;
    }

    public function removeAchat(Achat $achat): self
    {
        if ($this->achats->contains($achat)) {
            $this->achats->removeElement($achat);
            // set the owning side to null (unless already changed)
            if ($achat->getAcheteur() === $this) {
                $achat->setAcheteur(null);
            }
        }

        return $this;
    }
}
