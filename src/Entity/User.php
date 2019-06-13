<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * ORM\DiscriminatorMap({"picker" = "Picker", "reseller" = "Resseller", "buyer" = "Buyer"})
 */
class User implements UserInterface
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=180, unique=true)
	 * @Assert\NotBlank
	 */
	protected $email;

	/**
	 * @ORM\Column(type="json")
	 */
	protected $roles = [];

	/**
	 * @var string The hashed password
	 * @ORM\Column(type="string")
	 * @Assert\NotBlank
	 * @Assert\Length(min=6, max=4096)
	 */
	protected $password;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 */
	protected $first_name;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Assert\NotBlank
	 */
	protected $last_name;

	/**
	 * @ORM\Column(type="string", length=50)
	 * @Assert\NotBlank
	 */
	protected $city;

	/**
	 * @ORM\Column(type="text")
	 * @Assert\NotBlank
	 */
	protected $address;

	/**
	 * @ORM\Column(type="string", length=50)
	 * @Assert\NotBlank
	 */
	protected $country;

	/**
	 * @ORM\Column(type="string", length=20)
	 * @Assert\NotBlank
	 */
	protected $phone;

	/**
	 * @ORM\Column(type="bigint")
	 */
	protected $balance;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $loyalty_points;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $picture;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $subscription_date;

	/**
	 * @ORM\Column(type="boolean")
	 */
	protected $is_active;

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
	protected $feedbacks_sent;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="buyer")
	 */
	protected $purchases_transactions;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Transaction", mappedBy="seller")
	 */
	protected $sales_transactions;

	public function __construct()
	{
		$this->subscription_date = new \DateTime();
		$this->setBalance(0);
		$this->setLoyaltyPoints(0);
		$this->setIsActive(1);
		$this->inbox = new ArrayCollection();
		$this->sent = new ArrayCollection();
		$this->feedbacks = new ArrayCollection();
		$this->feedbacks_sent = new ArrayCollection();
		$this->purchases_transactions = new ArrayCollection();
		$this->sales_transactions = new ArrayCollection();
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
		// $this->plainPassword = null;
	}

	public function getFirstName(): ?string
	{
		return $this->first_name;
	}

	public function setFirstName(string $first_name): self
	{
		$this->first_name = $first_name;

		return $this;
	}

	public function getLastName(): ?string
	{
		return $this->last_name;
	}

	public function setLastName(string $last_name): self
	{
		$this->last_name = $last_name;

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
		return $this->loyalty_points;
	}

	public function setLoyaltyPoints(?int $loyalty_points): self
	{
		$this->loyalty_points = $loyalty_points;

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
		return $this->subscription_date;
	}

	public function getIsActive(): ?bool
	{
		return $this->is_active;
	}

	public function setIsActive(bool $is_active): self
	{
		$this->is_active = $is_active;

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
		return $this->feedbacks_sent;
	}

	public function addFeedbacksSent(Feedback $feedbacksSent): self
	{
		if (!$this->feedbacks_sent->contains($feedbacksSent)) {
			$this->feedbacks_sent[] = $feedbacksSent;
			$feedbacksSent->setSender($this);
		}

		return $this;
	}

	public function removeFeedbacksSent(Feedback $feedbacksSent): self
	{
		if ($this->feedbacks_sent->contains($feedbacksSent)) {
			$this->feedbacks_sent->removeElement($feedbacksSent);
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
		return $this->purchases_transactions;
	}

	public function addPurchaseTransaction(Transaction $purchase): self
	{
		if (!$this->purchases_transactions->contains($purchase)) {
			$this->purchases_transactions[] = $purchase;
			$purchase->setBuyer($this);
		}

		return $this;
	}

	public function removePurchaseTransaction(Transaction $purchase): self
	{
		if ($this->purchases_transactions->contains($purchase)) {
			$this->purchases_transactions->removeElement($purchase);
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
		return $this->sales_transactions;
	}

	public function addSaleTransaction(Transaction $sale): self
	{
		if (!$this->sales_transactions->contains($sale)) {
			$this->sales_transactions[] = $sale;
			$sale->setSeller($this);
		}

		return $this;
	}

	public function removeSaleTransaction(Transaction $sale): self
	{
		if ($this->sales_transactions->contains($sale)) {
			$this->sales_transactions->removeElement($sale);
			// set the owning side to null (unless already changed)
			if ($sale->getSeller() === $this) {
				$sale->setSeller(null);
			}
		}

		return $this;
	}
}
