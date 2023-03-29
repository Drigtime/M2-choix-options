<?php

namespace App\Entity\Main;

use App\Repository\BlocOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlocOptionRepository::class)]
class BlocOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: CampagneChoix::class, inversedBy: 'blocOptions')]
    private ?CampagneChoix $campagneChoix = null;

    #[ORM\OneToMany(mappedBy: 'blocOption', targetEntity: Choix::class, cascade: ['persist', 'remove'])]
    private Collection $choixes;

    #[ORM\ManyToOne(targetEntity: BlocUE::class, inversedBy: 'blocOptions')]
    private ?BlocUE $blocUE = null;

    #[ORM\ManyToOne(inversedBy: 'blocOptions')]
    private ?Parcours $parcours = null;

    #[ORM\ManyToMany(targetEntity: BlocOptionUe::class, mappedBy: 'blocOption')]
    private Collection $blocOptionUes;

    public function __construct()
    {
        $this->choixes = new ArrayCollection();
        $this->blocOptionUes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCampagneChoix(): ?CampagneChoix
    {
        return $this->campagneChoix;
    }

    public function setCampagneChoix(?CampagneChoix $campagneChoix): self
    {
        $this->campagneChoix = $campagneChoix;

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
            $choix->setBlocOption($this);
        }

        return $this;
    }

    public function removeChoix(Choix $choix): self
    {
        if ($this->choixes->removeElement($choix)) {
            // set the owning side to null (unless already changed)
            if ($choix->getBlocOption() === $this) {
                $choix->setBlocOption(null);
            }
        }

        return $this;
    }

    public function getBlocUE(): ?BlocUE
    {
        return $this->blocUE;
    }

    public function setBlocUE(?BlocUE $blocUE): self
    {
        $this->blocUE = $blocUE;

        return $this;
    }

    public function getParcours(): ?Parcours
    {
        return $this->parcours;
    }

    public function setParcours(?Parcours $parcours): self
    {
        $this->parcours = $parcours;

        return $this;
    }

    /**
     * @return Collection<int, BlocOptionUe>
     */
    public function getBlocOptionUes(): Collection
    {
        return $this->blocOptionUes;
    }

    public function addBlocOptionUe(BlocOptionUe $blocOptionUe): self
    {
        if (!$this->blocOptionUes->contains($blocOptionUe)) {
            $this->blocOptionUes->add($blocOptionUe);
            $blocOptionUe->addBlocOption($this);
        }

        return $this;
    }

    public function removeBlocOptionUe(BlocOptionUe $blocOptionUe): self
    {
        if ($this->blocOptionUes->removeElement($blocOptionUe)) {
            $blocOptionUe->removeBlocOption($this);
        }

        return $this;
    }

}
