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
    private $offresVente;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Achat", mappedBy="acheteur")
     */
    private $offresAcceptees;

    public function __construct()
    {
        parent::__construct();
        $this->offreVentes = new ArrayCollection();
        $this->offresAcceptees = new ArrayCollection();
    }

    /**
     * @return Collection|OffreVente[]
     */
    public function getOffresVente(): Collection
    {
        return $this->offresVente;
    }

    public function addOffreVente(OffreVente $offreVente): self
    {
        if (!$this->offresVente->contains($offreVente)) {
            $this->offresVente[] = $offreVente;
            $offreVente->setProprietaire($this);
        }

        return $this;
    }

    public function removeOffreVente(OffreVente $offreVente): self
    {
        if ($this->offresVente->contains($offreVente)) {
            $this->offresVente->removeElement($offreVente);
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
    public function getOffresAcceptees(): Collection
    {
        return $this->offresAcceptees;
    }

    public function addOffreAcceptee(Achat $offreAcceptee): self
    {
        if (!$this->offresAcceptees->contains($offreAcceptee)) {
            $this->offresAcceptees[] = $offreAcceptee;
            $offreAcceptee->setAccepteur($this);
        }

        return $this;
    }

    public function removeAchat(Achat $offreAcceptee): self
    {
        if ($this->offreAcceptees->contains($offreAcceptee)) {
            $this->offreAcceptees->removeElement($offreAcceptee);
            // set the owning side to null (unless already changed)
            if ($offreAcceptee->getAccepteur() === $this) {
                $offreAcceptee->setAccepteur(null);
            }
        }

        return $this;
    }
}
