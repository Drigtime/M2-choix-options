<?php

namespace App\Entity\Main;

use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 100)]
    private ?string $mail = null;

    #[ORM\ManyToOne(targetEntity: Parcours::class, inversedBy: 'etudiants')]
    private ?Parcours $parcours = null;

    #[ORM\ManyToMany(targetEntity: Groupe::class, mappedBy: 'etudiants')]
    private Collection $groupes;

    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: EtudiantUE::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $etudiantUEs;

    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: ResponseCampagne::class, orphanRemoval: true)]
    private Collection $responseCampagnes;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->etudiantUEs = new ArrayCollection();
        $this->responseCampagnes = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getNom() . ' ' . $this->getPrenom();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

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
     * @return Collection<int, Groupe>
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes->add($groupe);
            $groupe->addEtudiant($this);
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->removeElement($groupe)) {
            $groupe->removeEtudiant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, EtudiantUE>
     */
    public function getEtudiantUEs(): Collection
    {
        return $this->etudiantUEs;
    }

    public function addEtudiantUE(EtudiantUE $etudiantUE): self
    {
        if (!$this->etudiantUEs->contains($etudiantUE)) {
            $this->etudiantUEs->add($etudiantUE);
            $etudiantUE->setEtudiant($this);
        }

        return $this;
    }

    public function removeEtudiantUE(EtudiantUE $etudiantUE): self
    {
        if ($this->etudiantUEs->removeElement($etudiantUE)) {
            // set the owning side to null (unless already changed)
            if ($etudiantUE->getEtudiant() === $this) {
                $etudiantUE->setEtudiant(null);
            }
        }

        return $this;
    }

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
            $responseCampagne->setEtudiant($this);
        }

        return $this;
    }

    public function removeResponseCampagne(ResponseCampagne $responseCampagne): self
    {
        if ($this->responseCampagnes->removeElement($responseCampagne)) {
            // set the owning side to null (unless already changed)
            if ($responseCampagne->getEtudiant() === $this) {
                $responseCampagne->setEtudiant(null);
            }
        }

        return $this;
    }
}
