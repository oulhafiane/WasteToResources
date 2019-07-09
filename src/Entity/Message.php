<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="App\Repository\MessageRepository")
 */
class Message
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
	 * @Serializer\Groups({"messages"})
     */
    private $text;

    /**
     * @ORM\Column(type="datetime")
	 * @Serializer\Groups({"messages"})
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
	 * @Serializer\Groups({"messages"})
     */
    private $seen;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="inbox")
     * @ORM\JoinColumn(nullable=false)
     */
    private $receiver;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="sent")
     * @ORM\JoinColumn(nullable=false)
	 * @Serializer\Groups({"messages"})
     */
    private $sender;

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
		$this->seen = 0;
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

    public function getSeen(): ?bool
    {
        return $this->seen;
    }

    public function setSeen(): self
    {
        $this->seen = true;

        return $this;
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
}
