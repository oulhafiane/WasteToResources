<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 * @Serializer\Groups({"offer"})
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255)
	 * @Serializer\Groups({"offer"})
	 */
	private $label;

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Offer", mappedBy="category")
	 */
	private $offers;

	public function __construct()
	{
		$this->offers = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getLabel(): ?string
	{
		return $this->label;
	}

	public function setLabel(string $label): self
	{
		$this->label = $label;

		return $this;
	}

	/**
	 * @return Collection|Offer[]
	 */
	public function getOffers(): Collection
	{
		return $this->offers;
	}

	public function addOffer(Offer $offer): self
	{
		if (!$this->offers->contains($offer)) {
			$this->offers[] = $offer;
			$offer->setCategory($this);
		}

		return $this;
	}

	public function removeOffer(Offer $offer): self
	{
		if ($this->offers->contains($offer)) {
			$this->offers->removeElement($offer);
			// set the owning side to null (unless already changed)
			if ($offer->getCategory() === $this) {
				$offer->setCategory(null);
			}
		}

		return $this;
	}
}
