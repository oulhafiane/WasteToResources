<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\GrossisteAcheteurRepository")
 */
class GrossisteAcheteur extends Utilisateur
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Enchere", mappedBy="GrossisteAcheteur")
     */
    private $encheres;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OffreEnchere", mappedBy="proprietaire")
     */
    private $offreEncheres;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OffreAchatGros", mappedBy="proprietaire")
     */
    private $offreAchatGros;

    public function __construct()
    {
        parent::__construct();
        $this->encheres = new ArrayCollection();
        $this->offreEncheres = new ArrayCollection();
        $this->offreAchatGros = new ArrayCollection();
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
            $enchere->setGrossisteAcheteur($this);
        }

        return $this;
    }

    public function removeEnchere(Enchere $enchere): self
    {
        if ($this->encheres->contains($enchere)) {
            $this->encheres->removeElement($enchere);
            // set the owning side to null (unless already changed)
            if ($enchere->getGrossisteAcheteur() === $this) {
                $enchere->setGrossisteAcheteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OffreEnchere[]
     */
    public function getOffreEncheres(): Collection
    {
        return $this->offreEncheres;
    }

    public function addOffreEnchere(OffreEnchere $offreEnchere): self
    {
        if (!$this->offreEncheres->contains($offreEnchere)) {
            $this->offreEncheres[] = $offreEnchere;
            $offreEnchere->setProprietaire($this);
        }

        return $this;
    }

    public function removeOffreEnchere(OffreEnchere $offreEnchere): self
    {
        if ($this->offreEncheres->contains($offreEnchere)) {
            $this->offreEncheres->removeElement($offreEnchere);
            // set the owning side to null (unless already changed)
            if ($offreEnchere->getProprietaire() === $this) {
                $offreEnchere->setProprietaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OffreAchatGros[]
     */
    public function getOffreAchatGros(): Collection
    {
        return $this->offreAchatGros;
    }

    public function addOffreAchatGro(OffreAchatGros $offreAchatGro): self
    {
        if (!$this->offreAchatGros->contains($offreAchatGro)) {
            $this->offreAchatGros[] = $offreAchatGro;
            $offreAchatGro->setProprietaire($this);
        }

        return $this;
    }

    public function removeOffreAchatGro(OffreAchatGros $offreAchatGro): self
    {
        if ($this->offreAchatGros->contains($offreAchatGro)) {
            $this->offreAchatGros->removeElement($offreAchatGro);
            // set the owning side to null (unless already changed)
            if ($offreAchatGro->getProprietaire() === $this) {
                $offreAchatGro->setProprietaire(null);
            }
        }

        return $this;
    }
}
