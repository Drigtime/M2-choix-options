<?php

namespace App\Entity\Main;

use App\Repository\CampagneChoixRepository;
use DateTimeInterface;
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
    private ?DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $dateFin = null;

    #[ORM\OneToMany(mappedBy: 'campagneChoix', targetEntity: BlocOption::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $blocOptions;

    // #[ORM\OneToMany(mappedBy: 'campagneChoix', targetEntity: Choix::class)]
    // private Collection $choixes;

    #[ORM\OneToMany(mappedBy: 'campagne', targetEntity: ResponseCampagne::class, orphanRemoval: true)]
    private Collection $responseCampagnes;

    #[ORM\ManyToMany(targetEntity: Parcours::class, inversedBy: 'campagneChoixes')]
    private Collection $parcours;

    public function __construct()
    {
        $this->blocOptions = new ArrayCollection();
        // $this->choixes = new ArrayCollection();
        $this->responseCampagnes = new ArrayCollection();
        $this->parcours = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

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

    /**
     * @return Collection<int, Parcours>
     */
    public function getParcours(): Collection
    {
        return $this->parcours;
    }

    public function addParcour(Parcours $parcour): self
    {
        if (!$this->parcours->contains($parcour)) {
            $this->parcours->add($parcour);
        }

        return $this;
    }

    public function removeParcour(Parcours $parcour): self
    {
        $this->parcours->removeElement($parcour);

        return $this;
    }

    public function isActif(): bool
    {
        $now = new \DateTime();
        return $now >= $this->getDateDebut() && $now <= $this->getDateFin();
    }

    public function isFinished(): bool
    {
        $now = new \DateTime();
        return $now > $this->getDateFin();
    }

    public function isNotStarted(): bool
    {
        $now = new \DateTime();
        return $now < $this->getDateDebut();
    }

    public function canBeEdited(): bool
    {
        return $this->isNotStarted() && $this->getResponseCampagnes()->isEmpty();
    }

    public function cantBeEditedReason(): array
    {
        $reason = [];

        if ($this->isFinished()) {
            $reason[] = 'La campagne est terminée';

            return $reason;
        }

        if ($this->isActif()) {
            $reason[] = 'La campagne est en cours';
        }

        if (!$this->getResponseCampagnes()->isEmpty()) {
            $reason[] = 'La campagne a déjà des réponses';
        }

        return $reason;
    }

}