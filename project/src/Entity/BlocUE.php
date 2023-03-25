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
    private ?BlocUECategory $category = null;

    #[ORM\ManyToOne(targetEntity: Parcours::class, inversedBy: 'blocUEs')]
    #[ORM\JoinColumn(name: 'parcours_id', referencedColumnName: 'id', nullable: false)]
    private Parcours $parcours;

    #[ORM\OneToMany(mappedBy: 'blocUE', targetEntity: BlocOption::class)]
    private Collection $blocOptions;

    #[ORM\OneToMany(mappedBy: 'blocUE', targetEntity: BlocUeUe::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $blocUeUes;

    #[ORM\Column]
    private ?int $nbUEsOptional = null;

    public function __construct()
    {
        $this->blocOptions = new ArrayCollection();
        $this->blocUeUes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->category->getLabel();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?BlocUECategory
    {
        return $this->category;
    }

    public function setCategory(BlocUECategory $category): self
    {
        $this->category = $category;

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

    /**
     * @return Collection<int, BlocUeUe>
     */
    public function getBlocUeUes(): Collection
    {
        return $this->blocUeUes;
    }

    public function addBlocUeUe(BlocUeUe $blocUeUe): self
    {
        if (!$this->blocUeUes->contains($blocUeUe)) {
            $this->blocUeUes->add($blocUeUe);
            $blocUeUe->setBlocUE($this);
        }

        return $this;
    }

    public function removeBlocUeUe(BlocUeUe $blocUeUe): self
    {
        if ($this->blocUeUes->removeElement($blocUeUe)) {
            // set the owning side to null (unless already changed)
            if ($blocUeUe->getBlocUE() === $this) {
                $blocUeUe->setBlocUE(null);
            }
        }

        return $this;
    }

    public function getNbUEsOptional(): ?int
    {
        return $this->nbUEsOptional;
    }

    public function setNbUEsOptional(int $nbUEsOptional): self
    {
        $this->nbUEsOptional = $nbUEsOptional;

        return $this;
    }
}