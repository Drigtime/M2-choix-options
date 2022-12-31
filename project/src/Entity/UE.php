<?php

namespace App\Entity;

use App\Repository\UERepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UERepository::class)]
class UE
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $label = null;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private ?bool $active = true;

    #[ORM\ManyToMany(targetEntity: BlocUE::class, mappedBy: 'UEs')]
    private Collection $blocUEs;

    #[ORM\ManyToMany(targetEntity: BlocOption::class, mappedBy: 'UE')]
    private Collection $blocOptions;

    #[ORM\OneToMany(mappedBy: 'UE', targetEntity: Choix::class)]
    private Collection $choixes;

    #[ORM\ManyToOne(inversedBy: 'uEs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BlocUECategory $blocUECategory = null;

    public function __construct()
    {
        $this->blocUEs = new ArrayCollection();
        $this->blocOptions = new ArrayCollection();
        $this->choixes = new ArrayCollection();
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

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection|BlocUE[]
     */
    public function getBlocUEs(): Collection
    {
        return $this->blocUEs;
    }

    public function addBlocUE(BlocUE $blocUE): self
    {
        if (!$this->blocUEs->contains($blocUE)) {
            $this->blocUEs[] = $blocUE;
            $blocUE->addUE($this);
        }

        return $this;
    }

    public function removeBlocUE(BlocUE $blocUE): self
    {
        if ($this->blocUEs->removeElement($blocUE)) {
            $blocUE->removeUE($this);
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
            $blocOption->addUE($this);
        }

        return $this;
    }

    public function removeBlocOption(BlocOption $blocOption): self
    {
        if ($this->blocOptions->removeElement($blocOption)) {
            $blocOption->removeUE($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Choix>
     */
    public function getChoixes(): Collection
    {
        return $this->choixes;
    }

    public function addChoix(Choix $choix): self
    {
        if (!$this->choixes->contains($choix)) {
            $this->choixes->add($choix);
            $choix->setUE($this);
        }

        return $this;
    }

    public function removeChoix(Choix $choix): self
    {
        if ($this->choixes->removeElement($choix)) {
            // set the owning side to null (unless already changed)
            if ($choix->getUE() === $this) {
                $choix->setUE(null);
            }
        }

        return $this;
    }

    public function getBlocUECategory(): ?BlocUECategory
    {
        return $this->blocUECategory;
    }

    public function setBlocUECategory(?BlocUECategory $blocUECategory): self
    {
        $this->blocUECategory = $blocUECategory;

        return $this;
    }
}
