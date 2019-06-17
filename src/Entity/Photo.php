<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 * @Vich\Uploadable
 */
class Photo
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 * @Serializer\ReadOnly
	 */
	private $id;

	/**
	 * @Vich\UploadableField(mapping="offer_photo", fileNameProperty="name", size="size")
	 * @Serializer\Type("string")
	 * @Serializer\Groups({"offer"})
	 */
	private $file;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $name;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	private $size;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 * @Serializer\Groups({"offer"})
	 */
	private $link;

	/**
	 * @ORM\Column(type="datetime", nullable=true)
	 */
	private $uploadAt;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Offer", inversedBy="photos")
	 */
	private $offer;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getFile()
	{
		return $this->file;
	}

	/**
	 * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
	 */

	public function setFile(?File $file = null): void
	{
		$this->file = $file;

		if (null !== $file)
			$this->uploadAt = new \DateTimeImmutable();
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	public function getSize(): ?int
	{
		return $this->size;
	}

	public function setSize(int $size): self
	{
		$this->size = $size;

		return $this;
	}

	public function getLink(): ?string
	{
		return $this->link;
	}

	public function setLink(string $link): self
	{
		$this->link = $link;

		return $this;
	}

	public function getUploadAt(): ?\DateTimeInterface
	{
		return $this->uploadAt;
	}

	public function setUploadAt(\DateTimeInterface $uploadAt): self
	{
		$this->uploadAt = $uploadAt;

		return $this;
	}

	public function getOffer(): ?Offer
	{
		return $this->offer;
	}

	public function setOffer(?Offer $offer): self
	{
		$this->offer = $offer;

		return $this;
	}
}
