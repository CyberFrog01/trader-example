<?php

namespace App\Entity\MarketName;

use App\Repository\MarketName\HistoryParsingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoryParsingRepository::class)]
class HistoryParsing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\Column(length: 255)]
    private ?string $date = null;

    #[ORM\ManyToOne(inversedBy: 'historyParsing')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MarketNameEntity $marketName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getMarketName(): ?MarketNameEntity
    {
        return $this->marketName;
    }

    public function setMarketName(?MarketNameEntity $marketName): static
    {
        $this->marketName = $marketName;

        return $this;
    }
}
