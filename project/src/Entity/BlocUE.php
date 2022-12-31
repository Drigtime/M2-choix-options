<?php

namespace App\Entity;

use App\Repository\BlocUERepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlocUERepository::class)]
class BlocUE
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?BlocUECategory $blocUECategory = null;

    #[ORM\ManyToOne(targetEntity: Parcours::class, inversedBy: 'blocUEs')]
    private Parcours $parcours;

    #[ORM\ManyToMany(targetEntity: UE::class, inversedBy: 'blocUEs')]
    private Collection $UEs;

    #[ORM\OneToMany(mappedBy: 'blocUE', targetEntity: BlocOption::class)]
    private Collection $blocOptions;

    public function __construct()
    {
        $this->UEs = new ArrayCollection();
        $this->blocOptions = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->blocUECategory->getLabel();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBlocUECategory(): ?BlocUECategory
    {
        return $this->blocUECategory;
    }

    public function setBlocUECategory(BlocUECategory $blocUECategory): self
    {
        $this->blocUECategory = $blocUECategory;

        return $this;
    }

    public function getParcours(): Parcours
    {
        return $this->parcours;
    }

    public function setParcours(Parcours $parcours): self
    {
        $this->parcours = $parcours;

        return $this;
    }

    /**
     * @return Collection<int, UE>
     */
    public function getUEs(): Collection
    {
        return $this->UEs;
    }

    public function addUE(UE $UE): self
    {
        if (!$this->UEs->contains($UE)) {
            $this->UEs->add($UE);
            $UE->setBlocUEs($this);
        }

        return $this;
    }

    public function removeUE(UE $UE): self
    {
        if ($this->UEs->removeElement($UE)) {
            // set the owning side to null (unless already changed)
            if ($UE->getBlocUEs() === $this) {
                $UE->setBlocUEs(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BlocOption>
     */
    public function getBlocOptions(): Collection
    {
        return $this->blocOptions;
    }

    public function addBlocOption(BlocOption $blocOption): self
    {
        if (!$this->blocOptions->contains($blocOption)) {
            $this->blocOptions->add($blocOption);
            $blocOption->setBlocUE($this);
        }

        return $this;
    }

    public function removeBlocOption(BlocOption $blocOption): self
    {
        if ($this->blocOptions->removeElement($blocOption)) {
            // set the owning side to null (unless already changed)
            if ($blocOption->getBlocUE() === $this) {
                $blocOption->setBlocUE(null);
            }
        }

        return $this;
    }
}
