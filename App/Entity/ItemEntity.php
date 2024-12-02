<?php

namespace App\Entity;

use App\Entity\MarketName\HistoryParsing;
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

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column]
    private ?int $hashId = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $categoryPath = null;

    #[ORM\Column(length: 255, nullable:true)]
    private ?string $image = null;

    #[ORM\Column]
    private ?int $lastTimeStamp = 0;

  
    /**
     * @var Collection<int, HistoryCsParsing>
     */
    #[ORM\OneToOne(targetEntity: MarketNameEntity::class, mappedBy: 'item', cascade:['persist'])]
    private Collection $marketName;

    

    

    public function getId(): ?int
    {
        return $this->id;
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

    public function getHashId(): ?int
    {
        return $this->hashId;
    }

    public function setHashId(string $hashId): static
    {
        $this->hashId = $hashId;

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

    public function getMarketName()
    {
        return $this->marketName;
    }

    public function setMarketName($marketName)
    {
        $this->marketName = $marketName;

        return $this;
    }
}
