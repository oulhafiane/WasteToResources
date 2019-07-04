<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\HasLifeCycleCallBacks
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRepository")
 */
class Notification
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 * @Serializer\Groups({"notifications"})
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Serializer\Groups({"notifications"})
	 */
	private $message;

	/**
	 * @ORM\Column(type="smallint")
	 * @Serializer\Groups({"notifications"})
	 */
	private $type;

	/**
	 * @ORM\Column(type="datetime")
	 * @Serializer\Groups({"notifications"})
	 */
	private $date;

	/**
	 * @ORM\Column(type="boolean")
	 * @Serializer\Groups({"notifications"})
	 */
	private $seen;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="notifications")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;

	/**
	 * @ORM\Column(type="bigint", nullable=true)
	 * @Serializer\Groups({"notifications"})
	 */
	private $reference;

	/**
	 * @ORM\PrePersist
	 */
	public function onPrePersist()
	{
		$this->date = new \DateTime();
		$this->seen = false;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getMessage(): ?string
	{
		return $this->message;
	}

	public function setMessage(string $message): self
	{
		$this->message = $message;

		return $this;
	}

	public function getType(): ?int
	{
		return $this->type;
	}

	public function setType(int $type): self
	{
		$this->type = $type;

		return $this;
	}

	public function getDate(): ?\DateTimeInterface
	{
		return $this->date;
	}

	public function getSeen(): ?bool
	{
		return $this->seen;
	}

	public function setSeen(): self
	{
		$this->seen = true;

		return $this;
	}

	public function getUser(): ?User
	{
		return $this->user;
	}

	public function setUser(?User $user): self
	{
		$this->user = $user;

		return $this;
	}

	public function getReference(): ?int
	{
		return $this->reference;
	}

	public function setReference(?int $reference): self
	{
		$this->reference = $reference;

		return $this;
	}
}
