<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints AS Assert;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"sale" = "SaleOffer", "purchase" = "PurchaseOffer", "BulkPurchaseOffer" = "BulkPurchaseOffer", "auction" = "AuctionBid"})
 * @Serializer\Discriminator(field = "type", disabled = false, map = {"sale" = "App\Entity\SaleOffer", "purchase": "App\Entity\PurchaseOffer", "BulkPurchaseOffer": "App\Entity\BulkPurchaseOffer", "auction": "App\Entity\AuctionBid"}, groups = {"new-offer"})
 */
abstract class Offer
{
	/**
	 * @ORM\Id()
	 * @ORM\GeneratedValue()
	 * @ORM\Column(type="integer")
	 * @Serializer\ReadOnly
	 * @Serializer\Groups({"list-offers"})
	 * @Assert\IsNull(groups={"new-offer"})
	 */
	protected $id;

	/**
	 * @ORM\Column(type="string", length=25)
	 * @Assert\NotBlank(groups={"new-offer"})
	 * @Assert\Length(
	 *	min = 5,
	 *	max = 20,
	 *	groups={"new-offer"}
	 * )
	 * @Serializer\Groups({"new-offer", "list-offers"})
	 */
	protected $title;

	/**
	 * @ORM\Column(type="text")
	 * @Assert\NotBlank(groups={"new-offer"})
	 * @Serializer\Groups({"new-offer", "list-offers"})
	 */
	protected $description;

	/**
	 * @ORM\Column(type="integer")
	 * @Assert\NotBlank(groups={"new-offer"})
	 * @Assert\Positive(groups={"new-offer"})
	 * @Serializer\Type("integer")
	 * @Serializer\Groups({"new-offer", "list-offers"})
	 */
	protected $price;

	/**
	 * @ORM\Column(type="boolean")
	 * @Serializer\Type("bool")
	 * @Serializer\SerializedName("withTransport")
	 * @Serializer\Groups({"new-offer", "list-offers"})
	 */
	protected $withTransport;

	/**
	 * @ORM\Column(type="datetime")
	 * @Serializer\Groups({"list-offers"})
	 */
	protected $startDate;

	/**
	 * @ORM\Column(type="datetime")
	 * @Serializer\Groups({"list-offers"})
	 */
	protected $endDate;

	/**
	 * @ORM\Column(type="bigint")
	 * @Assert\NotBlank(groups={"new-offer"})
	 * @Assert\Positive(groups={"new-offer"})
	 * @Serializer\Type("integer")
	 * @Serializer\Groups({"new-offer", "list-offers"})
	 */
	protected $weight;

	/**
	 * @ORM\Column(type="array", nullable=true)
	 * @Serializer\Type("array")
	 * @Serializer\Groups({"new-offer", "list-offers"})
	 */
	protected $locations = [];

	/**
	 * @ORM\Column(type="array", nullable=true)
	 * @Serializer\Type("array")
	 * @Serializer\Groups({"new-offer", "list-offers"})
	 */
	protected $keywords = [];

	/**
	 * @ORM\OneToMany(targetEntity="App\Entity\Photo", mappedBy="offer", cascade={"persist"})
	 * @Serializer\Type("ArrayCollection<App\Entity\Photo>")
	 * @Serializer\Groups({"new-offer", "list-offers"})
	 * @Assert\Valid(groups={"new-photo"})
	 */
	protected $photos;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="offers")
	 * @ORM\JoinColumn(nullable=false)
	 * @Serializer\Type("App\Entity\Category")
	 * @Serializer\Groups({"new-offer", "list-offers"})
	 */
	protected $category;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $isActive;

	/**
	 * @ORM\OneToOne(targetEntity="App\Entity\OnHold", mappedBy="offer", cascade={"persist", "remove"})
	 */
	private $onHold;

	/**
	 * @ORM\PrePersist
	 */
	public function onPrePersist()
	{
		$date = new \DateTime();
		$this->startDate = $date;
		$this->setIsActive(True);
		if ($this instanceOf AuctionBid)
			$this->endDate = new \DateTime(date("Y-m-d h:i:s", strtotime("+7 day")));
		else
			$this->endDate = new \DateTime(date("Y-m-d h:i:s", strtotime("+30 day")));
		//	$this->photos = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getPrice(): ?int
	{
		return $this->price;
	}

	public function setPrice(int $price): self
	{
		$this->price = $price;

		return $this;
	}

	public function getWithTransport(): ?bool
	{
		return $this->withTransport;
	}

	public function setWithTransport(bool $withTransport): self
	{
		$this->withTransport = $withTransport;

		return $this;
	}

	public function getStartDate(): ?\DateTimeInterface
	{
		return $this->startDate;
	}

	public function getEndDate(): ?\DateTimeInterface
	{
		return $this->endDate;
	}

	public function getWeight(): ?int
	{
		return $this->weight;
	}

	public function setWeight(int $weight): self
	{
		$this->weight = $weight;

		return $this;
	}

	public function getLocations(): ?array
	{
		return $this->locations;
	}

	public function setLocations(?array $locations): self
	{
		$this->locations = $locations;

		return $this;
	}

	public function getTitle(): ?string
	{
		return $this->title;
	}

	public function setTitle(string $title): self
	{
		$this->title = $title;

		return $this;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(string $description): self
	{
		$this->description = $description;

		return $this;
	}

	public function getKeywords(): ?array
	{
		return $this->keywords;
	}

	public function setKeywords(?array $keywords): self
	{
		$this->keywords = $keywords;

		return $this;
	}

	/**
	 * @return Collection|Photo[]
	 */
	public function getPhotos()
	{
		return $this->photos;
	}

	public function addPhoto(Photo $photo): self
	{
		if (!$this->photos->contains($photo)) {
			$this->photos[] = $photo;
			$photo->setOffer($this);
		}

		return $this;
	}

	public function removePhoto(Photo $photo): self
	{
		if ($this->photos->contains($photo)) {
			$this->photos->removeElement($photo);
			// set the owning side to null (unless already changed)
			if ($photo->getOffer() === $this) {
				$photo->setOffer(null);
			}
		}

		return $this;
	}

	public function getCategory(): ?Category
	{
		return $this->category;
	}

	public function setCategory(?Category $category): self
	{
		$this->category = $category;

		return $this;
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

	public function getOnHold(): ?OnHold
	{
		return $this->onHold;
	}

	public function setOnHold(?OnHold $onHold): self
	{
		$this->onHold = $onHold;

		// set (or unset) the owning side of the relation if necessary
		$newOffer = $onHold === null ? null : $this;
		if ($newOffer !== $onHold->getOffer()) {
			$onHold->setOffer($newOffer);
		}

		return $this;
	}
}
