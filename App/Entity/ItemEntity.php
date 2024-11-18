<?php

namespace App\Entity;

use App\Repository\ItemEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemEntityRepository::class)]
class ItemEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $active = true;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?int $currentAmount = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxAmount = null;

    #[ORM\Column(nullable: true)]
    private ?int $currentPrice = null;

    #[ORM\Column(nullable: true)]
    private ?int $maxPrice = null;

    #[ORM\Column(nullable: true)]
    private ?int $marketPrice = null;

    #[ORM\Column(nullable: true)]
    private ?bool $controllPrice = null;

    #[ORM\Column(nullable: true)]
    private ?int $controllPriceStep = null;

    /**
     * @var Collection<int, TargetEntity>
     */
    #[ORM\OneToMany(targetEntity: TargetEntity::class, mappedBy: 'item', orphanRemoval: true)]
    private Collection $target;

    #[ORM\Column(length: 255)]
    private ?string $gameId = null;

    #[ORM\Column(length: 255)]
    private ?string $categoryPath = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    

    

    public function __construct()
    {
        $this->target = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

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

    public function getCurrentAmount(): ?int
    {
        return $this->currentAmount;
    }

    public function setCurrentAmount(?int $currentAmount): static
    {
        $this->currentAmount = $currentAmount;

        return $this;
    }

    public function getMaxAmount(): ?int
    {
        return $this->maxAmount;
    }

    public function setMaxAmount(?int $maxAmount): static
    {
        $this->maxAmount = $maxAmount;

        return $this;
    }

    public function getCurrentPrice(): ?int
    {
        return $this->currentPrice;
    }

    public function setCurrentPrice(?int $currentPrice): static
    {
        $this->currentPrice = $currentPrice;

        return $this;
    }

    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    public function setMaxPrice(?int $maxPrice): static
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    public function getMarketPrice(): ?int
    {
        return $this->marketPrice;
    }

    public function setMarketPrice(?int $marketPrice): static
    {
        $this->marketPrice = $marketPrice;

        return $this;
    }

    public function isControllPrice(): ?bool
    {
        return $this->controllPrice;
    }

    public function setControllPrice(?bool $controllPrice): static
    {
        $this->controllPrice = $controllPrice;

        return $this;
    }

    public function getControllPriceStep(): ?int
    {
        return $this->controllPriceStep;
    }

    public function setControllPriceStep(int $controllPriceStep): static
    {
        $this->controllPriceStep = $controllPriceStep;

        return $this;
    }

    /**
     * @return Collection<int, TargetEntity>
     */
    public function getTarget(): Collection
    {
        return $this->target;
    }

    public function addTarget(TargetEntity $target): static
    {
        if (!$this->target->contains($target)) {
            $this->target->add($target);
            $target->setItem($this);
        }

        return $this;
    }

    public function removeTarget(TargetEntity $target): static
    {
        if ($this->target->removeElement($target)) {
            // set the owning side to null (unless already changed)
            if ($target->getItem() === $this) {
                $target->setItem(null);
            }
        }

        return $this;
    }

    public function getGameId(): ?string
    {
        return $this->gameId;
    }

    public function setGameId(string $gameId): static
    {
        $this->gameId = $gameId;

        return $this;
    }

    public function getCategoryPath(): ?string
    {
        return $this->categoryPath;
    }

    public function setCategoryPath(string $categoryPath): static
    {
        $this->categoryPath = $categoryPath;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
