<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="App\Repository\FeedbackRepository")
 */
class Feedback
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="text")
	 * @Serializer\Groups({"feedbacks"})
	 */
	private $text;

	/**
	 * @ORM\Column(type="datetime")
	 * @Serializer\Groups({"feedbacks"})
	 */
	private $date;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="feedbacks")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $receiver;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="feedbacksSent")
	 * @ORM\JoinColumn(nullable=false)
	 * @Serializer\Groups({"feedbacks"})
	 */
	private $sender;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 * @Serializer\Groups({"feedbacks"})
	 */
	private $rate;

	public function __toString()
	{
		return $this->getText();
	}

	/**
	 * @ORM\PrePersist
	 */
	public function onPrePersist()
	{
		$this->date = new \DateTime();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getText(): ?string
	{
		return $this->text;
	}

	public function setText(string $text): self
	{
		$this->text = $text;

		return $this;
	}

	public function getDate(): ?\DateTimeInterface
	{
		return $this->date;
	}

	public function getReceiver(): ?User
	{
		return $this->receiver;
	}

	public function setReceiver(?User $receiver): self
	{
		$this->receiver = $receiver;

		return $this;
	}

	public function getSender(): ?User
	{
		return $this->sender;
	}

	public function setSender(?User $sender): self
	{
		$this->sender = $sender;

		return $this;
	}

	public function getRate(): ?int
	{
		return $this->rate;
	}

	public function setRate(?int $rate): self
	{
		$this->rate = $rate;

		return $this;
	}
}
