<?php

namespace App\Entity;

use App\Repository\ChoixRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChoixRepository::class)]
class Choix
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $ordre = null;

    #[ORM\ManyToOne(inversedBy: 'choixes')]
    private ?UE $UE = null;

    #[ORM\ManyToOne(inversedBy: 'choixes')]
    private ?ResponseCampagne $responseCampagne = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

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

    public function getResponseCampagne(): ?ResponseCampagne
    {
        return $this->responseCampagne;
    }

    public function setResponseCampagne(?ResponseCampagne $responseCampagne): self
    {
        $this->responseCampagne = $responseCampagne;

        return $this;
    }
}
