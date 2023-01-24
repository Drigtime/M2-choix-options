<?php

namespace App\Entity;

use App\Repository\CampagneChoixRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CampagneChoixRepository::class)]
class CampagneChoix
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\ManyToOne(inversedBy: 'campagneChoixes')]
    private ?Parcours $parcours = null;

    #[ORM\OneToMany(mappedBy: 'campagneChoix', targetEntity: BlocOption::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $blocOptions;

    // #[ORM\OneToMany(mappedBy: 'campagneChoix', targetEntity: Choix::class)]
    // private Collection $choixes;

    #[ORM\OneToMany(mappedBy: 'campagne', targetEntity: ResponseCampagne::class, orphanRemoval: true)]
    private Collection $responseCampagnes;

    public function __construct()
    {
        $this->blocOptions = new ArrayCollection();
        // $this->choixes = new ArrayCollection();
        $this->responseCampagnes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

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
     * @return Collection<int, BlocOption>
     */
    public function getBlocOptions(): Collection
    {
        dump("test1");
        return $this->blocOptions;
    }

    public function addBlocOption(BlocOption $blocOption): self
    {
        if (!$this->blocOptions->contains($blocOption)) {
            $this->blocOptions->add($blocOption);
            $blocOption->setCampagneChoix($this);
        }

        return $this;
    }

    public function removeBlocOption(BlocOption $blocOption): self
    {
        if ($this->blocOptions->removeElement($blocOption)) {
            // set the owning side to null (unless already changed)
            if ($blocOption->getCampagneChoix() === $this) {
                $blocOption->setCampagneChoix(null);
            }
        }

        return $this;
    }

    // /**
    //  * @return Collection<int, Choix>
    //  */
    // public function getChoixes(): Collection
    // {
    //     return $this->choixes;
    // }

    // public function addChoix(Choix $choix): self
    // {
    //     if (!$this->choixes->contains($choix)) {
    //         $this->choixes->add($choix);
    //         $choix->setCampagneChoix($this);
    //     }

    //     return $this;
    // }

    // public function removeChoix(Choix $choix): self
    // {
    //     if ($this->choixes->removeElement($choix)) {
    //         // set the owning side to null (unless already changed)
    //         if ($choix->getCampagneChoix() === $this) {
    //             $choix->setCampagneChoix(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, ResponseCampagne>
     */
    public function getResponseCampagnes(): Collection
    {
        return $this->responseCampagnes;
    }

    public function addResponseCampagne(ResponseCampagne $responseCampagne): self
    {
        if (!$this->responseCampagnes->contains($responseCampagne)) {
            $this->responseCampagnes->add($responseCampagne);
            $responseCampagne->setCampagne($this);
        }

        return $this;
    }

    public function removeResponseCampagne(ResponseCampagne $responseCampagne): self
    {
        if ($this->responseCampagnes->removeElement($responseCampagne)) {
            // set the owning side to null (unless already changed)
            if ($responseCampagne->getCampagne() === $this) {
                $responseCampagne->setCampagne(null);
            }
        }

        return $this;
    }
}
