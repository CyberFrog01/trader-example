<?php

namespace App\Entity\MarketName;

use App\Repository\TargetEntityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TargetEntityRepository::class)]
class TargetEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $amount = null;

    #[ORM\Column]
    private ?int $price = null;

    #[ORM\ManyToOne(inversedBy: 'target')]
    #[ORM\JoinColumn(nullable: false)]
    private ?MarketNameEntity $marketName = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): static
    {
        $this->amount = $amount;

        return $this;
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
