<?php

namespace App\Entity\Main;

use App\Repository\EtudiantUERepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantUERepository::class)]
#[ORM\Table(name: 'etudiant_ue')]
class EtudiantUE
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Etudiant::class, inversedBy: 'etudiantUEs')]
    private ?Etudiant $etudiant = null;

    #[ORM\ManyToOne(targetEntity: UE::class, inversedBy: 'etudiantUEs')]
    private ?UE $UE = null;

    #[ORM\Column]
    private ?bool $acquis = null;

    public function __construct(Etudiant $etudiant, UE $UE)
    {
        $this->etudiant = $etudiant;
        $this->UE = $UE;
        $this->acquis = false;
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

    public function getUE(): ?UE
    {
        return $this->UE;
    }

    public function setUE(?UE $UE): self
    {
        $this->UE = $UE;

        return $this;
    }

    public function isAcquis(): ?bool
    {
        return $this->acquis;
    }

    public function setAcquis(bool $acquis): self
    {
        $this->acquis = $acquis;

        return $this;
    }
}
