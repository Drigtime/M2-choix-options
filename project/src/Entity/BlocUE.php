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

    #[ORM\Column(length: 45)]
    private ?string $label = null;

    #[ORM\ManyToMany(targetEntity: Parcours::class, mappedBy: 'blocUEs')]
    private Collection $parcours;

    #[ORM\OneToMany(mappedBy: 'blocUE', targetEntity: UE::class)]
    private Collection $UEs;

    #[ORM\OneToMany(mappedBy: 'blocUE', targetEntity: BlocOption::class)]
    private Collection $blocOptions;

    public function __construct()
    {
        $this->parcours = new ArrayCollection();
        $this->UEs = new ArrayCollection();
        $this->blocOptions = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->label;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, Parcours>
     */
    public function getParcours(): Collection
    {
        return $this->parcours;
    }

    public function addParcour(Parcours $parcour): self
    {
        if (!$this->parcours->contains($parcour)) {
            $this->parcours->add($parcour);
            $parcour->addBlocUE($this);
        }

        return $this;
    }

    public function removeParcour(Parcours $parcour): self
    {
        if ($this->parcours->removeElement($parcour)) {
            $parcour->removeBlocUE($this);
        }

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
            $UE->setBlocUE($this);
        }

        return $this;
    }

    public function removeUE(UE $UE): self
    {
        if ($this->UEs->removeElement($UE)) {
            // set the owning side to null (unless already changed)
            if ($UE->getBlocUE() === $this) {
                $UE->setBlocUE(null);
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
