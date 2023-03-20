<?php

namespace App\Entity;

use App\Repository\ResponseCampagneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ResponseCampagneRepository::class)]
class ResponseCampagne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'responseCampagnes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etudiant $etudiant = null;

    #[ORM\ManyToOne(inversedBy: 'responseCampagnes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CampagneChoix $campagne = null;

    #[ORM\OneToMany(mappedBy: 'responseCampagne', targetEntity: Choix::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $choixes;

    public function __construct()
    {
        $this->choixes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    public function getCampagne(): ?CampagneChoix
    {
        return $this->campagne;
    }

    public function setCampagne(?CampagneChoix $campagne): self
    {
        $this->campagne = $campagne;

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
            $choix->setResponseCampagne($this);
        }

        return $this;
    }

    public function removeChoix(Choix $choix): self
    {
        if ($this->choixes->removeElement($choix)) {
            // set the owning side to null (unless already changed)
            if ($choix->getResponseCampagne() === $this) {
                $choix->setResponseCampagne(null);
            }
        }

        return $this;
    }
}
