<?php

namespace App\Entity\Main;

use App\Repository\Main\BlocOptionUeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlocOptionUeRepository::class)]
class BlocOptionUe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'blocOptionUes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UE $ue = null;

    #[ORM\Column]
    private ?int $effectif = null;

    #[ORM\ManyToMany(targetEntity: BlocOption::class, inversedBy: 'blocOptionUes')]
    private Collection $blocOption;

    public function __construct()
    {
        $this->blocOption = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUe(): ?UE
    {
        return $this->ue;
    }

    public function setUe(?UE $ue): self
    {
        $this->ue = $ue;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getEffectif(): ?int
    {
        return $this->effectif;
    }

    /**
     * @param int|null $effectif
     */
    public function setEffectif(?int $effectif): void
    {
        $this->effectif = $effectif;
    }

    /**
     * @return Collection<int, BlocOption>
     */
    public function getBlocOption(): Collection
    {
        return $this->blocOption;
    }

    public function addBlocOption(BlocOption $blocOption): self
    {
        if (!$this->blocOption->contains($blocOption)) {
            $this->blocOption->add($blocOption);
        }

        return $this;
    }

    public function removeBlocOption(BlocOption $blocOption): self
    {
        $this->blocOption->removeElement($blocOption);

        return $this;
    }
}
