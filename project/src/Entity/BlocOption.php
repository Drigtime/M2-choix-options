<?php

namespace App\Entity;

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

    #[ORM\ManyToMany(targetEntity: UE::class, inversedBy: 'blocOptions')]
    private Collection $UEs;

    #[ORM\OneToMany(mappedBy: 'blocOption', targetEntity: Choix::class, cascade: ['persist', 'remove'])]
    private Collection $choixes;

    #[ORM\ManyToOne(targetEntity: BlocUE::class, inversedBy: 'blocOptions')]
    private ?BlocUE $blocUE = null;

    #[ORM\ManyToOne(inversedBy: 'blocOptions')]
    private ?Parcours $parcours = null;

    public function __construct()
    {
        $this->UEs = new ArrayCollection();
        $this->choixes = new ArrayCollection();
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
        }

        return $this;
    }

    public function removeUE(UE $UE): self
    {
        $this->UEs->removeElement($UE);

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
}
