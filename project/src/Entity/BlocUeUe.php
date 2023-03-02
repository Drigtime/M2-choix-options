<?php

namespace App\Entity;

use App\Repository\BlocUeUeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlocUeUeRepository::class)]
class BlocUeUe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'blocUeUes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?BlocUE $blocUE = null;

    #[ORM\ManyToOne(inversedBy: 'blocUeUes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?UE $UE = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isOptional = false;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUE(): ?UE
    {
        return $this->UE;
    }

    public function setUE(?UE $UE): self
    {
        $this->UE = $UE;

        return $this;
    }

    public function isIsOptional(): ?bool
    {
        return $this->isOptional;
    }

    public function setIsOptional(?bool $isOptional): self
    {
        $this->isOptional = $isOptional;

        return $this;
    }
}
