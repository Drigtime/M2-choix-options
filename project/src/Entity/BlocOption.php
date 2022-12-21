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

    #[ORM\Column]
    private ?int $nbUEChoix = null;

    #[ORM\ManyToOne(inversedBy: 'blocOptions')]
    private ?CampagneChoix $campagneChoix = null;

    #[ORM\ManyToOne(inversedBy: 'blocOptions')]
    private ?BlocUE $blocUE = null;

    #[ORM\ManyToMany(targetEntity: UE::class, inversedBy: 'blocOptions')]
    private Collection $UE;

    public function __construct()
    {
        $this->UE = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbUEChoix(): ?int
    {
        return $this->nbUEChoix;
    }

    public function setNbUEChoix(int $nbUEChoix): self
    {
        $this->nbUEChoix = $nbUEChoix;

        return $this;
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

    public function getBlocUE(): ?BlocUE
    {
        return $this->blocUE;
    }

    public function setBlocUE(?BlocUE $blocUE): self
    {
        $this->blocUE = $blocUE;

        return $this;
    }

    /**
     * @return Collection<int, UE>
     */
    public function getUE(): Collection
    {
        return $this->UE;
    }

    public function addUE(UE $UE): self
    {
        if (!$this->UE->contains($UE)) {
            $this->UE->add($UE);
        }

        return $this;
    }

    public function removeUE(UE $UE): self
    {
        $this->UE->removeElement($UE);

        return $this;
    }
}
