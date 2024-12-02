<?php

namespace App\Entity\MarketName;

use App\Entity\MarketName\HistoryParsing;
use App\Entity\MarketName\TargetEntity;
use App\Repository\MarketName\MarketNameEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MarketNameEntityRepository::class)]
class MarketNameEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $lastTimeStamp = 0;

    /**
     * @var Collection<int, TargetEntity>
     */
    #[ORM\OneToMany(targetEntity: TargetEntity::class, mappedBy: 'marketName', orphanRemoval: true)]
    private Collection $target;

    /**
     * @var Collection<int, HistoryCsParsing>
     */
    #[ORM\OneToMany(targetEntity: HistoryParsing::class, mappedBy: 'marketName', cascade:['persist'])]
    private Collection $historyParsing;




    public function __construct()
    {
        $this->target = new ArrayCollection();
        $this->historyParsing = new ArrayCollection();
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

    /**
     * @return Collection<int, HistoryCsParsing>
     */
    public function getHistoryParsing(): Collection
    {
        return $this->historyParsing;
    }

    public function addHistoryParsing(HistoryParsing $historyParsing): static
    {
        if (!$this->historyParsing->contains($historyParsing)) {
            $this->historyParsing->add($historyParsing);
            $historyParsing->setItem($this);
        }

        return $this;
    }

    public function removeHistoryParsing(HistoryParsing $historyParsing): static
    {
        if ($this->historyParsing->removeElement($historyParsing)) {
            // set the owning side to null (unless already changed)
            if ($historyParsing->getItem() === $this) {
                $historyParsing->setItem(null);
            }
        }

        return $this;
    }
}
