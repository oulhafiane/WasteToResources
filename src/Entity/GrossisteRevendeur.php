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
    private $offresAchat;

    /*
     * @ORM\OneToMany(targetEntity="App\Entity\OffreEnchere", mappedBy="proprietaire")
     */
    private $offresEnchere;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AchatGros", mappedBy="accepteur")
     */
    private $offresAcceptees;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OffreVente", mappedBy="acheteur")
     */
    private $achats;

    public function __construct()
    {
        parent::__construct();
        $this->offresAchat = new ArrayCollection();
	$this->offresEnchere = new ArrayCollection();
        $this->offresAcceptees = new ArrayCollection();
        $this->achats = new ArrayCollection();
    }

    /**
     * @return Collection|OffreAchat[]
     */
    public function getOffresAchat(): Collection
    {
        return $this->offresAchat;
    }

    public function addOffreAchat(OffreAchat $offreAchat): self
    {
        if (!$this->offresAchat->contains($offreAchat)) {
            $this->offresAchat[] = $offreAchat;
            $offreAchat->setProprietaire($this);
        }

        return $this;
    }

    public function removeOffreAchat(OffreAchat $offreAchat): self
    {
        if ($this->offresAchat->contains($offreAchat)) {
            $this->offresAchat->removeElement($offreAchat);
            // set the owning side to null (unless already changed)
            if ($offreAchat->getProprietaire() === $this) {
                $offreAchat->setProprietaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OffreEnchere[]
     */
    public function getOffresEnchere(): Collection
    {
	return $this->offresEnchere;
    }

    public function addOffreEnchere(OffreEnchere $offreEnchere): self
    {
	if (!$this->offresEnchere->contains($offreEnchere)) {
	    $this->offresEnchere[] = $offreEnchere;
	    $offreEnchere->setProprietaire($this);
	}

	return $this;
    }

    public function removeOffreEnchere(OffreEnchere $offreEnchere):self
    {
	if ($this->offresEnchere->contains($offreEnchere)) {
	    $this->offresEnchere->removeElement($offreEnchere);
	    // set the owning side to null (unless already changed)
	    if ($offreEnchere->getProprietaire() === $this) {
		$offreEnchere->setProprietaire(null);
	    }
	}

	return $this;
    }

    /**
     * @return Collection|AchatGros[]
     */
    public function getOffresAcceptees(): Collection
    {
        return $this->offresAcceptees;
    }

    public function addOffreAcceptee(AchatGros $offreAcceptee): self
    {
        if (!$this->offresAcceptees->contains($offreAcceptee)) {
            $this->offresAcceptees[] = $offreAcceptee;
            $offreAcceptee->setAccepteur($this);
        }

        return $this;
    }

    public function removeOffreAcceptees(AchatGros $offreAcceptee): self
    {
        if ($this->offresAcceptees->contains($offreAcceptee)) {
            $this->offresAcceptees->removeElement($offreAcceptee);
            // set the owning side to null (unless already changed)
            if ($offreAcceptee->getAccepteur() === $this) {
                $offreAcceptee->setAccepteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|OffreVente[]
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(OffreVente $achat): self
    {
        if (!$this->achats->contains($achat)) {
            $this->achats[] = $achat;
            $achat->setAcheteur($this);
        }

        return $this;
    }

    public function removeAchat(OffreVente $achat): self
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
