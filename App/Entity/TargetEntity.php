<?php

namespace App\Entity;

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
    private ?ItemEntity $item = null;

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

    public function getItem(): ?ItemEntity
    {
        return $this->item;
    }

    public function setItem(?ItemEntity $item): static
    {
        $this->item = $item;

        return $this;
    }
}
