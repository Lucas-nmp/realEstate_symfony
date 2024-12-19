<?php

namespace App\Entity;

use App\Repository\PropertyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PropertyRepository::class)]
class Property
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 15)]
    private ?string $reference = null;

    #[ORM\Column(length: 100)]
    private ?string $title = null;

    #[ORM\Column(length: 150)]
    private ?string $area = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $shortDescription = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $longDescription = null;

    #[ORM\Column]
    private ?bool $outstanding = null;

    #[ORM\Column(length: 30)]
    private ?string $operationType = null;

    #[ORM\Column(length: 60)]
    private ?string $priceObservation = null;

    /**
     * @var Collection<int, FeatureProperty>
     */
    #[ORM\OneToMany(targetEntity: FeatureProperty::class, mappedBy: 'property', orphanRemoval: true)]
    private Collection $featureProperties;

    public function __construct()
    {
        $this->featureProperties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getArea(): ?string
    {
        return $this->area;
    }

    public function setArea(string $area): static
    {
        $this->area = $area;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): static
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function getLongDescription(): ?string
    {
        return $this->longDescription;
    }

    public function setLongDescription(string $longDescription): static
    {
        $this->longDescription = $longDescription;

        return $this;
    }

    public function isOutstanding(): ?bool
    {
        return $this->outstanding;
    }

    public function setOutstanding(bool $outstanding): static
    {
        $this->outstanding = $outstanding;

        return $this;
    }

    public function getOperationType(): ?string
    {
        return $this->operationType;
    }

    public function setOperationType(string $operationType): static
    {
        $this->operationType = $operationType;

        return $this;
    }

    public function getPriceObservation(): ?string
    {
        return $this->priceObservation;
    }

    public function setPriceObservation(string $priceObservation): static
    {
        $this->priceObservation = $priceObservation;

        return $this;
    }

    /**
     * @return Collection<int, FeatureProperty>
     */
    public function getFeatureProperties(): Collection
    {
        return $this->featureProperties;
    }

    public function addFeatureProperty(FeatureProperty $featureProperty): static
    {
        if (!$this->featureProperties->contains($featureProperty)) {
            $this->featureProperties->add($featureProperty);
            $featureProperty->setProperty($this);
        }

        return $this;
    }

    public function removeFeatureProperty(FeatureProperty $featureProperty): static
    {
        if ($this->featureProperties->removeElement($featureProperty)) {
            // set the owning side to null (unless already changed)
            if ($featureProperty->getProperty() === $this) {
                $featureProperty->setProperty(null);
            }
        }

        return $this;
    }
}
