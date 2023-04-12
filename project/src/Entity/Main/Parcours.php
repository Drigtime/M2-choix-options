<?php

namespace App\Entity\Main;

use App\Repository\ParcoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

#[ORM\Entity(repositoryClass: ParcoursRepository::class)]
class Parcours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'parcours')]
    private ?AnneeFormation $anneeFormation = null;

    #[ORM\Column]
    private ?string $label;

    #[ORM\OneToMany(mappedBy: 'parcours', targetEntity: BlocUE::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $blocUEs;

    #[ORM\OneToMany(mappedBy: 'parcours', targetEntity: Etudiant::class)]
    #[OrderBy(["nom" => "DESC"])]
    private Collection $etudiants;

    #[ORM\ManyToMany(targetEntity: CampagneChoix::class, mappedBy: 'parcours')]
    private Collection $campagneChoixes;

    #[ORM\OneToMany(mappedBy: 'parcours', targetEntity: BlocOption::class)]
    private Collection $blocOptions;

    public function __construct()
    {
        $this->blocUEs = new ArrayCollection();
        $this->etudiants = new ArrayCollection();
        $this->campagneChoixes = new ArrayCollection();
        $this->blocOptions = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getAnneeFormation()->getLabel() . ' - ' . $this->getLabel();
    }

    /**
     * @return string|null
     */
    public function getLabel(): ?string
    {
        return $this->label;
    }

    /**
     * @param string|null $label
     */
    public function setLabel(?string $label): void
    {
        $this->label = $label;
    }

    public function getAnneeFormation(): ?AnneeFormation
    {
        return $this->anneeFormation;
    }

    public function setAnneeFormation(?AnneeFormation $anneeFormation): self
    {
        $this->anneeFormation = $anneeFormation;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    /**
     * @return Collection<int, BlocUE>
     */
    public function getBlocUEs(): Collection
    {
        return $this->blocUEs;
    }

    public function addBlocUE(BlocUE $blocUE): self
    {
        if (!$this->blocUEs->contains($blocUE)) {
            $this->blocUEs->add($blocUE);
            $blocUE->setParcours($this);
        }

        return $this;
    }

    public function removeBlocUE(BlocUE $blocUE): self
    {
        $this->blocUEs->removeElement($blocUE);

        return $this;
    }

    /**
     * @return Collection<int, Etudiant>
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants->add($etudiant);
            $etudiant->setParcours($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getParcours() === $this) {
                $etudiant->setParcours(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CampagneChoix>
     */
    public function getCampagneChoixes(): Collection
    {
        return $this->campagneChoixes;
    }

    public function addCampagneChoix(CampagneChoix $campagneChoix): self
    {
        if (!$this->campagneChoixes->contains($campagneChoix)) {
            $this->campagneChoixes->add($campagneChoix);
            $campagneChoix->addParcour($this);
        }

        return $this;
    }

    public function removeCampagneChoix(CampagneChoix $campagneChoix): self
    {
        if ($this->campagneChoixes->removeElement($campagneChoix)) {
            $campagneChoix->removeParcour($this);
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
            $blocOption->setParcours($this);
        }

        return $this;
    }

    public function removeBlocOption(BlocOption $blocOption): self
    {
        if ($this->blocOptions->removeElement($blocOption)) {
            // set the owning side to null (unless already changed)
            if ($blocOption->getParcours() === $this) {
                $blocOption->setParcours(null);
            }
        }

        return $this;
    }
}
