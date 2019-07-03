<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"picker" = "Picker", "reseller" = "Reseller", "buyer" = "Buyer"})
 * @Serializer\Discriminator(field = "type", disabled = false, map = {"picker" = "App\Entity\Picker", "reseller": "App\Entity\Reseller", "buyer": "App\Entity\Buyer"})
 */
abstract class User implements UserInterface
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 * @Assert\IsNull(groups={"new-user"})
	 * @Serializer\ReadOnly
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=180, unique=true)
	 * @Assert\NotBlank(groups={"new-user"})
	 * @Assert\Email(groups={"new-user"})
	 * @Serializer\Groups({"new-user", "list-offers", "infos"})
	 */
	protected $email;

	/**
	 * @ORM\Column(type="json")
	 */
	protected $roles = [];

	/**
	 * @var string The hashed password
	 * @ORM\Column(type="string")
	 * @Serializer\ReadOnly
	 * @Assert\IsNull(groups={"new-user"})
	 */
	protected $password;

	/**
	 * @Assert\NotBlank(groups={"new-user"})
	 * @Assert\Length(min=6, max=4096, groups={"new-user"})
	 * @Assert\NotCompromisedPassword(groups={"new-user"})
	 * @Serializer\SerializedName("password")
	 * @Serializer\Type("string")
	 * @Serializer\Groups({"new-user"})
	 */
	protected $plainPassword;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank(groups={"new-user"})
	 * @Serializer\SerializedName("firstName")
	 * @Serializer\Groups({"new-user", "list-offers", "infos"})
	 */
	protected $firstName;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank(groups={"new-user"})
	 * @Serializer\SerializedName("lastName")
	 * @Serializer\Groups({"new-user", "list-offers", "infos"})
	 */
	protected $lastName;

	/**
	 * @ORM\Column(type="string", length=50)
	 * @Assert\NotBlank(groups={"new-user"})
	 * @Serializer\Groups({"new-user"})
	 */
	protected $city;

	/**
	 * @ORM\Column(type="text")
	 * @Assert\NotBlank(groups={"new-user"})
	 * @Serializer\Groups({"new-user"})
	 */
	protected $address;

	/**
	 * @ORM\Column(type="string", length=50)
	 * @Assert\NotBlank(groups={"new-user"})
	 * @Assert\Country(groups={"new-user"})
	 * @Serializer\Groups({"new-user"})
	 */
	protected $country;

	/**
	 * @ORM\Column(type="string", length=20)
	 * @Assert\NotBlank(groups={"new-user"})
	 * @Serializer\Groups({"new-user"})
	 */
	protected $phone;

	/**
	 * @ORM\Column(type="bigint")
	 * @Serializer\Groups({"infos"})
	 */
	protected $balance;

	/**
	 * @ORM\Column(type="integer")
	 * @Serializer\Groups({"infos"})
	 */
	protected $loyaltyPoints;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 * @Serializer\Groups({"new-user", "infos"})
	 */
	protected $picture;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $subscriptionDate;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $isActive;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="receiver")
	 */
	protected $inbox;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="sender")
	 */
	protected $sent;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Feedback", mappedBy="receiver")
	 */
	protected $feedbacks;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Feedback", mappedBy="sender")
	 */
	protected $feedbacksSent;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="buyer")
	 */
	protected $purchasesTransactions;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="seller")
	 */
	protected $salesTransactions;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Notification", mappedBy="user")
	 * @Serializer\Groups({"infos"})
	 */
	private $notifications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OnHold", mappedBy="user")
     */
    private $onHolds;

	public function __construct()
               	{
               		$this->inbox = new ArrayCollection();
               		$this->sent = new ArrayCollection();
               		$this->feedbacks = new ArrayCollection();
               		$this->feedbacksSent = new ArrayCollection();
               		$this->purchasesTransactions = new ArrayCollection();
               		$this->salesTransactions = new ArrayCollection();
               		$this->notifications = new ArrayCollection();
                 $this->onHolds = new ArrayCollection();
               	}

	/**
	 * @ORM\PrePersist
	 */
	public function onPrePersist()
               	{
               		if ($this instanceOf Picker)
               			$this->roles[] = 'ROLE_PICKER';
               		else if ($this instanceOf Reseller)
               			$this->roles[] = 'ROLE_RESELLER';
               		else if ($this instanceOf Buyer)
               			$this->roles[] = 'ROLE_BUYER';
               		$this->subscriptionDate = new \DateTime();
               		$this->setBalance(0);
               		$this->setLoyaltyPoints(0);
               		$this->setIsActive(True);
               	}

	public function getId(): ?int
               	{
               		return $this->id;
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

	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUsername(): string
               	{
               		return (string) $this->email;
               	}

	/**
	 * @see UserInterface
	 */
	public function getRoles(): array
               	{
               		$roles = $this->roles;
               		// guarantee every user at least has ROLE_USER
               		$roles[] = 'ROLE_USER';
               
               		return array_unique($roles);
               	}

	public function setRoles(array $roles): self
               	{
               		$this->roles = $roles;
               
               		return $this;
               	}

	/**
	 * @see UserInterface
	 */
	public function getPassword(): string
               	{
               		return (string) $this->password;
               	}

	public function setPassword(string $password): self
               	{
               		$this->password = $password;
               
               		return $this;
               	}

	public function getPlainPassword(): string
               	{
               		return (string) $this->plainPassword;
               	}

	public function setPlainPassword(string $password): self
               	{
               		$this->plainPassword = $password;
               
               		return $this;
               	}

	/**
	 * @see UserInterface
	 */
	public function getSalt()
               	{
               		// not needed when using the "bcrypt" algorithm in security.yaml
               	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials()
               	{
               		// If you store any temporary, sensitive data on the user, clear it here
               		$this->plainPassword = null;
               	}

	public function getFirstName(): ?string
               	{
               		return $this->firstName;
               	}

	public function setFirstName(string $firstName): self
               	{
               		$this->firstName = $firstName;
               
               		return $this;
               	}

	public function getLastName(): ?string
               	{
               		return $this->lastName;
               	}

	public function setLastName(string $lastName): self
               	{
               		$this->lastName = $lastName;
               
               		return $this;
               	}

	public function getCity(): ?string
               	{
               		return $this->city;
               	}

	public function setCity(string $city): self
               	{
               		$this->city = $city;
               
               		return $this;
               	}

	public function getAddress(): ?string
               	{
               		return $this->address;
               	}

	public function setAddress(string $address): self
               	{
               		$this->address = $address;
               
               		return $this;
               	}

	public function getCountry(): ?string
               	{
               		return $this->country;
               	}

	public function setCountry(string $country): self
               	{
               		$this->country = $country;
               
               		return $this;
               	}

	public function getPhone(): ?string
               	{
               		return $this->phone;
               	}

	public function setPhone(?string $phone): self
               	{
               		$this->phone = $phone;
               
               		return $this;
               	}

	public function getBalance(): ?int
               	{
               		return $this->balance;
               	}

	public function setBalance(?int $balance): self
               	{
               		$this->balance = $balance;
               
               		return $this;
               	}

	public function getLoyaltyPoints(): ?int
               	{
               		return $this->loyaltyPoints;
               	}

	public function setLoyaltyPoints(?int $loyaltyPoints): self
               	{
               		$this->loyaltyPoints = $loyaltyPoints;
               
               		return $this;
               	}

	public function getPicture(): ?string
               	{
               		return $this->picture;
               	}

	public function setPicture(?string $picture): self
               	{
               		$this->picture = $picture;
               
               		return $this;
               	}

	public function getSubscriptionDate(): ?\DateTimeInterface
               	{
               		return $this->subscriptionDate;
               	}

	public function getIsActive(): ?bool
               	{
               		return $this->isActive;
               	}

	public function setIsActive(bool $isActive): self
               	{
               		$this->isActive = $isActive;
               
               		return $this;
               	}

	/**
	 * @return Collection|Message[]
	 */
	public function getInbox(): Collection
               	{
               		return $this->inbox;
               	}

	public function addInbox(Message $inbox): self
               	{
               		if (!$this->inbox->contains($inbox)) {
               			$this->inbox[] = $inbox;
               			$inbox->setReceiver($this);
               		}
               
               		return $this;
               	}

	public function removeInbox(Message $inbox): self
               	{
               		if ($this->inbox->contains($inbox)) {
               			$this->inbox->removeElement($inbox);
               			// set the owning side to null (unless already changed)
               			if ($inbox->getReceiver() === $this) {
               				$inbox->setReceiver(null);
               			}
               		}
               
               		return $this;
               	}

	/**
	 * @return Collection|Message[]
	 */
	public function getSent(): Collection
               	{
               		return $this->sent;
               	}

	public function addSent(Message $sent): self
               	{
               		if (!$this->sent->contains($sent)) {
               			$this->sent[] = $sent;
               			$sent->setSender($this);
               		}
               
               		return $this;
               	}

	public function removeSent(Message $sent): self
               	{
               		if ($this->sent->contains($sent)) {
               			$this->sent->removeElement($sent);
               			// set the owning side to null (unless already changed)
               			if ($sent->getSender() === $this) {
               				$sent->setSender(null);
               			}
               		}
               
               		return $this;
               	}

	/**
	 * @return Collection|Feedback[]
	 */
	public function getFeedbacks(): Collection
               	{
               		return $this->feedbacks;
               	}

	public function addFeedback(Feedback $feedback): self
               	{
               		if (!$this->feedbacks->contains($feedback)) {
               			$this->feedbacks[] = $feedback;
               			$feedback->setReceiver($this);
               		}
               
               		return $this;
               	}

	public function removeFeedback(Feedback $feedback): self
               	{
               		if ($this->feedbacks->contains($feedback)) {
               			$this->feedbacks->removeElement($feedback);
               			// set the owning side to null (unless already changed)
               			if ($feedback->getReceiver() === $this) {
               				$feedback->setReceiver(null);
               			}
               		}
               
               		return $this;
               	}

	/**
	 * @return Collection|Feedback[]
	 */
	public function getFeedbacksSent(): Collection
               	{
               		return $this->feedbacksSent;
               	}

	public function addFeedbacksSent(Feedback $feedbacksSent): self
               	{
               		if (!$this->feedbacksSent->contains($feedbacksSent)) {
               			$this->feedbacksSent[] = $feedbacksSent;
               			$feedbacksSent->setSender($this);
               		}
               
               		return $this;
               	}

	public function removeFeedbacksSent(Feedback $feedbacksSent): self
               	{
               		if ($this->feedbacksSent->contains($feedbacksSent)) {
               			$this->feedbacksSent->removeElement($feedbacksSent);
               			// set the owning side to null (unless already changed)
               			if ($feedbacksSent->getSender() === $this) {
               				$feedbacksSent->setSender(null);
               			}
               		}
               
               		return $this;
               	}

	/**
	 * @return Collection|Transaction[]
	 */
	public function getpurchasesTransactions(): Collection
               	{
               		return $this->purchasesTransactions;
               	}

	public function addPurchaseTransaction(Transaction $purchase): self
               	{
               		if (!$this->purchasesTransactions->contains($purchase)) {
               			$this->purchasesTransactions[] = $purchase;
               			$purchase->setBuyer($this);
               		}
               
               		return $this;
               	}

	public function removePurchaseTransaction(Transaction $purchase): self
               	{
               		if ($this->purchasesTransactions->contains($purchase)) {
               			$this->purchasesTransactions->removeElement($purchase);
               			// set the owning side to null (unless already changed)
               			if ($purchase->getBuyer() === $this) {
               				$purchase->setBuyer(null);
               			}
               		}
               
               		return $this;
               	}

	/**
	 * @return Collection|Transaction[]
	 */
	public function getsalesTransactions(): Collection
               	{
               		return $this->salesTransactions;
               	}

	public function addSaleTransaction(Transaction $sale): self
               	{
               		if (!$this->salesTransactions->contains($sale)) {
               			$this->salesTransactions[] = $sale;
               			$sale->setSeller($this);
               		}
               
               		return $this;
               	}

	public function removeSaleTransaction(Transaction $sale): self
               	{
               		if ($this->salesTransactions->contains($sale)) {
               			$this->salesTransactions->removeElement($sale);
               			// set the owning side to null (unless already changed)
               			if ($sale->getSeller() === $this) {
               				$sale->setSeller(null);
               			}
               		}
               
               		return $this;
               	}

	/**
	 * @return Collection|Notification[]
	 */
	public function getNotifications(): Collection
               	{
               		return $this->notifications;
               	}

	public function addNotification(Notification $notification): self
               	{
               		if (!$this->notifications->contains($notification)) {
               			$this->notifications[] = $notification;
               			$notification->setUser($this);
               		}
               
               		return $this;
               	}

	public function removeNotification(Notification $notification): self
               	{
               		if ($this->notifications->contains($notification)) {
               			$this->notifications->removeElement($notification);
               			// set the owning side to null (unless already changed)
               			if ($notification->getUser() === $this) {
               				$notification->setUser(null);
               			}
               		}
               
               		return $this;
               	}

    /**
     * @return Collection|OnHold[]
     */
    public function getOnHolds(): Collection
    {
        return $this->onHolds;
    }

    public function addOnHold(OnHold $onHold): self
    {
        if (!$this->onHolds->contains($onHold)) {
            $this->onHolds[] = $onHold;
            $onHold->setUser($this);
        }

        return $this;
    }

    public function removeOnHold(OnHold $onHold): self
    {
        if ($this->onHolds->contains($onHold)) {
            $this->onHolds->removeElement($onHold);
            // set the owning side to null (unless already changed)
            if ($onHold->getUser() === $this) {
                $onHold->setUser(null);
            }
        }

        return $this;
    }
}
