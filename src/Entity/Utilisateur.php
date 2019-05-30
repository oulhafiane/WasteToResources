<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class Utilisateur
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ville;

    /**
     * @ORM\Column(type="text")
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $solde;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pointsFidelite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAbonnement;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="destinataire")
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="expediteur")
     */
    private $messagesEnvoyes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reaction", mappedBy="destinataire")
     */
    private $reactions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reaction", mappedBy="expediteur")
     */
    private $reactionsEnvoyes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="acheteur")
     */
    private $achats;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="vendeur")
     */
    private $ventes;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->messagesEnvoyes = new ArrayCollection();
        $this->reactions = new ArrayCollection();
        $this->reactionsEnvoyes = new ArrayCollection();
        $this->achats = new ArrayCollection();
        $this->ventes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getSolde(): ?int
    {
        return $this->solde;
    }

    public function setSolde(?int $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getPointsFidelite(): ?int
    {
        return $this->pointsFidelite;
    }

    public function setPointsFidelite(?int $pointsFidelite): self
    {
        $this->pointsFidelite = $pointsFidelite;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateAbonnement(): ?\DateTimeInterface
    {
        return $this->dateAbonnement;
    }

    public function setDateAbonnement(\DateTimeInterface $dateAbonnement): self
    {
        $this->dateAbonnement = $dateAbonnement;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setDestinataire($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getDestinataire() === $this) {
                $message->setDestinataire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessagesEnvoyes(): Collection
    {
        return $this->messagesEnvoyes;
    }

    public function addMessagesEnvoye(Message $messagesEnvoye): self
    {
        if (!$this->messagesEnvoyes->contains($messagesEnvoye)) {
            $this->messagesEnvoyes[] = $messagesEnvoye;
            $messagesEnvoye->setExpediteur($this);
        }

        return $this;
    }

    public function removeMessagesEnvoye(Message $messagesEnvoye): self
    {
        if ($this->messagesEnvoyes->contains($messagesEnvoye)) {
            $this->messagesEnvoyes->removeElement($messagesEnvoye);
            // set the owning side to null (unless already changed)
            if ($messagesEnvoye->getExpediteur() === $this) {
                $messagesEnvoye->setExpediteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reaction[]
     */
    public function getReactions(): Collection
    {
        return $this->reactions;
    }

    public function addReaction(Reaction $reaction): self
    {
        if (!$this->reactions->contains($reaction)) {
            $this->reactions[] = $reaction;
            $reaction->setDestinataire($this);
        }

        return $this;
    }

    public function removeReaction(Reaction $reaction): self
    {
        if ($this->reactions->contains($reaction)) {
            $this->reactions->removeElement($reaction);
            // set the owning side to null (unless already changed)
            if ($reaction->getDestinataire() === $this) {
                $reaction->setDestinataire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reaction[]
     */
    public function getReactionsEnvoyes(): Collection
    {
        return $this->reactionsEnvoyes;
    }

    public function addReactionsEnvoye(Reaction $reactionsEnvoye): self
    {
        if (!$this->reactionsEnvoyes->contains($reactionsEnvoye)) {
            $this->reactionsEnvoyes[] = $reactionsEnvoye;
            $reactionsEnvoye->setExpediteur($this);
        }

        return $this;
    }

    public function removeReactionsEnvoye(Reaction $reactionsEnvoye): self
    {
        if ($this->reactionsEnvoyes->contains($reactionsEnvoye)) {
            $this->reactionsEnvoyes->removeElement($reactionsEnvoye);
            // set the owning side to null (unless already changed)
            if ($reactionsEnvoye->getExpediteur() === $this) {
                $reactionsEnvoye->setExpediteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getAchats(): Collection
    {
        return $this->achats;
    }

    public function addAchat(Transaction $achat): self
    {
        if (!$this->achats->contains($achat)) {
            $this->achats[] = $achat;
            $achat->setAcheteur($this);
        }

        return $this;
    }

    public function removeAchat(Transaction $achat): self
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
     * @return Collection|Transaction[]
     */
    public function getVentes(): Collection
    {
        return $this->ventes;
    }

    public function addVente(Transaction $vente): self
    {
        if (!$this->ventes->contains($vente)) {
            $this->ventes[] = $vente;
            $vente->setVendeur($this);
        }

        return $this;
    }

    public function removeVente(Transaction $vente): self
    {
        if ($this->ventes->contains($vente)) {
            $this->ventes->removeElement($vente);
            // set the owning side to null (unless already changed)
            if ($vente->getVendeur() === $this) {
                $vente->setVendeur(null);
            }
        }

        return $this;
    }
}
