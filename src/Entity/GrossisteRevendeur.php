<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GrossisteRevendeurRepository")
 */
class GrossisteRevendeur extends Utilisateur
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OffreAchat", mappedBy="proprietaire")
     */
    private $offreAchats;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AchatGros", mappedBy="acheteur")
     */
    private $achats;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OffreVente", mappedBy="acheteur")
     */
    private $offreVentes;

    public function __construct()
    {
        parent::__construct();
        $this->offreAchats = new ArrayCollection();
        $this->achats = new ArrayCollection();
        $this->offreVentes = new ArrayCollection();
    }

    /**
     * @return Collection|OffreAchat[]
     */
    public function getOffreAchats(): Collection
    {
        return $this->offreAchats;
    }

    public function addOffreAchat(OffreAchat $offreAchat): self
    {
        if (!$this->offreAchats->contains($offreAchat)) {
            $this->offreAchats[] = $offreAchat;
            $offreAchat->setProprietaire($this);
        }

        return $this;
    }

    public function removeOffreAchat(OffreAchat $offreAchat): self
    {
        if ($this->offreAchats->contains($offreAchat)) {
            $this->offreAchats->removeElement($offreAchat);
            // set the owning side to null (unless already changed)
            if ($offreAchat->getProprietaire() === $this) {
                $offreAchat->setProprietaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AchatGros[]
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(AchatGros $achat): self
    {
        if (!$this->achats->contains($achat)) {
            $this->achats[] = $achat;
            $achat->setAcheteur($this);
        }

        return $this;
    }

    public function removeAchat(AchatGros $achat): self
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
            $offreVente->setAcheteur($this);
        }

        return $this;
    }

    public function removeOffreVente(OffreVente $offreVente): self
    {
        if ($this->offreVentes->contains($offreVente)) {
            $this->offreVentes->removeElement($offreVente);
            // set the owning side to null (unless already changed)
            if ($offreVente->getAcheteur() === $this) {
                $offreVente->setAcheteur(null);
            }
        }

        return $this;
    }
}
