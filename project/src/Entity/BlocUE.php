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
    #[ORM\JoinColumn(name: 'parcours_id', referencedColumnName: 'id', nullable: false)]
    private Parcours $parcours;

    #[ORM\ManyToMany(targetEntity: UE::class, inversedBy: 'blocUEs')]
    private Collection $UEs;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?BlocOption $blocOption = null;

    public function __construct()
    {
        $this->UEs = new ArrayCollection();
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
            $this->UEs[] = $UE;
            $UE->addBlocUE($this);
        }

        return $this;
    }

    public function removeUE(UE $UE): self
    {
        if ($this->UEs->removeElement($UE)) {
            $UE->removeBlocUE($this);
        }

        return $this;
    }

    public function getBlocOption(): ?BlocOption
    {
        return $this->blocOption;
    }

    public function setBlocOption(?BlocOption $blocOption): self
    {
        $this->blocOption = $blocOption;

        return $this;
    }


}
