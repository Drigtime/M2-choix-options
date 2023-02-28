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

    #[ORM\ManyToMany(targetEntity: BlocOption::class, mappedBy: 'UEs')]
    private Collection $blocOptions;

    #[ORM\OneToMany(mappedBy: 'UE', targetEntity: Choix::class)]
    private Collection $choixes;

    #[ORM\ManyToMany(targetEntity: BlocUECategory::class, inversedBy: 'uEs')]
    private Collection $blocUECategories;

    #[ORM\OneToMany(mappedBy: 'ue', targetEntity: Groupe::class)]
    private Collection $groupes;

    #[ORM\OneToMany(mappedBy: 'UE', targetEntity: EtudiantUE::class)]
    private Collection $etudiantUEs;

    #[ORM\OneToMany(mappedBy: 'ue', targetEntity: BlocUeUe::class)]
    private Collection $blocUeUes;

    public function __construct()
    {
        $this->blocUEs = new ArrayCollection();
        $this->blocOptions = new ArrayCollection();
        $this->choixes = new ArrayCollection();
        $this->blocUECategories = new ArrayCollection();
        $this->groupes = new ArrayCollection();
        $this->etudiantUEs = new ArrayCollection();
        $this->blocUeUes = new ArrayCollection();
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

    /**
     * @return Collection<int, BlocUECategory>
     */
    public function getBlocUECategories(): Collection
    {
        return $this->blocUECategories;
    }

    public function addBlocUECategory(BlocUECategory $blocUECategory): self
    {
        if (!$this->blocUECategories->contains($blocUECategory)) {
            $this->blocUECategories->add($blocUECategory);
        }

        return $this;
    }

    public function removeBlocUECategory(BlocUECategory $blocUECategory): self
    {
        $this->blocUECategories->removeElement($blocUECategory);

        return $this;
    }

    /**
     * @return Collection<int, Groupe>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes->add($groupe);
            $groupe->setUe($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getUe() === $this) {
                $groupe->setUe(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EtudiantUE>
     */
    public function getEtudiantUEs(): Collection
    {
        return $this->etudiantUEs;
    }

    public function addEtudiantUE(EtudiantUE $etudiantUE): self
    {
        if (!$this->etudiantUEs->contains($etudiantUE)) {
            $this->etudiantUEs->add($etudiantUE);
            $etudiantUE->setUE($this);
        }

        return $this;
    }

    public function removeEtudiantUE(EtudiantUE $etudiantUE): self
    {
        if ($this->etudiantUEs->removeElement($etudiantUE)) {
            // set the owning side to null (unless already changed)
            if ($etudiantUE->getUE() === $this) {
                $etudiantUE->setUE(null);
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
            $blocUeUe->setUe($this);
        }

        return $this;
    }

    public function removeBlocUeUe(BlocUeUe $blocUeUe): self
    {
        if ($this->blocUeUes->removeElement($blocUeUe)) {
            // set the owning side to null (unless already changed)
            if ($blocUeUe->getUe() === $this) {
                $blocUeUe->setUe(null);
            }
        }

        return $this;
    }
}
