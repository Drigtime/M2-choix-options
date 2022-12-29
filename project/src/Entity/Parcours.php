<?php

namespace App\Entity;

use App\Repository\ParcoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParcoursRepository::class)]
class Parcours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'parcours')]
    private ?AnneeFormation $anneeFormation = null;

    #[ORM\ManyToOne(inversedBy: 'parcours')]
    private ?Rythme $rythme = null;

    #[ORM\ManyToOne(inversedBy: 'parcours')]
    private ?Specialisation $specialisation = null;

    #[ORM\OneToMany(mappedBy: 'parcours', targetEntity: Groupe::class)]
    private Collection $groupes;

    #[ORM\ManyToMany(targetEntity: BlocUE::class, inversedBy: 'parcours')]
    private Collection $blocUEs;

    #[ORM\OneToMany(mappedBy: 'parcours', targetEntity: CampagneChoix::class)]
    private Collection $campagneChoixes;

    #[ORM\OneToMany(mappedBy: 'parcours', targetEntity: Etudiant::class)]
    private Collection $etudiants;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->blocUEs = new ArrayCollection();
        $this->campagneChoixes = new ArrayCollection();
        $this->etudiants = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getAnneeFormation()->getLabel() . ' ' . $this->getRythme()->getLabel() . ' ' . $this->getSpecialisation()->getLabel();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRythme(): ?Rythme
    {
        return $this->rythme;
    }

    public function setRythme(?Rythme $rythme): self
    {
        $this->rythme = $rythme;

        return $this;
    }

    public function getSpecialisation(): ?Specialisation
    {
        return $this->specialisation;
    }

    public function setSpecialisation(?Specialisation $specialisation): self
    {
        $this->specialisation = $specialisation;

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
            $groupe->setParcours($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            // set the owning side to null (unless already changed)
            if ($groupe->getParcours() === $this) {
                $groupe->setParcours(null);
            }
        }

        return $this;
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
        }

        return $this;
    }

    public function removeBlocUE(BlocUE $blocUE): self
    {
        $this->blocUEs->removeElement($blocUE);

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
            $campagneChoix->setParcours($this);
        }

        return $this;
    }

    public function removeCampagneChoix(CampagneChoix $campagneChoix): self
    {
        if ($this->campagneChoixes->removeElement($campagneChoix)) {
            // set the owning side to null (unless already changed)
            if ($campagneChoix->getParcours() === $this) {
                $campagneChoix->setParcours(null);
            }
        }

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
}