<?php

namespace App\Service;

use App\Entity\Main\Etudiant;
use App\Entity\Main\EtudiantUE;
use App\Entity\Main\Groupe;
use App\Entity\Main\Parcours;
use App\Entity\Main\UE;
use App\Repository\GroupeRepository;

class MoveStudentService
{
    private GroupeRepository $groupeRepository;

    public function __construct(GroupeRepository $groupeRepository)
    {
        $this->groupeRepository = $groupeRepository;
    }

    /**
     * @param Etudiant $etudiant
     * @param Parcours $parcours
     * @return Etudiant
     */
    public function moveEtudiantToParcours(Etudiant $etudiant, Parcours $parcours, bool $optionalUE = false): Etudiant
    {
        $etudiant->getResponseCampagnes()->clear();
        $etudiant->getGroupes()->clear();
        $etudiant->getEtudiantUEs()->clear();
        $etudiant->setParcours($parcours);

        foreach ($parcours->getBlocUEs() as $blocUE) {
            $mandatoryUEs = $blocUE->getMandatoryUEs();
            $optionalUEs = $optionalUE ? $blocUE->getOptionalUEs()->slice(0, $blocUE->getNbUEsOptional()) : [];

            $ues = array_map(fn ($blocUeUe) => $blocUeUe->getUe(), array_merge($mandatoryUEs->toArray(), $optionalUEs));
            foreach ($ues as $ue) {
                $this->addStudentToUeGroup($ue, $etudiant);
                $etudiant->addEtudiantUE(new EtudiantUE($etudiant, $ue));
            }
        }

        return $etudiant;
    }

    /**
     * @param UE $ue
     * @param Etudiant $etudiant
     * @return void
     */
    public function addStudentToUeGroup(UE $ue, Etudiant $etudiant): void
    {
        $groups = $this->groupeRepository->findBy(['ue' => $ue]);
        $group = null;

        foreach ($groups as $g) {
            if ($g->getEtudiants()->count() < ($ue->getEffectif() / $ue->getNbrGroupe())) {
                $group = $g;
                break;
            }
        }

        if (!$group && count($groups) < $ue->getNbrGroupe()) {
            $group = new Groupe();
            $group->setUe($ue);
            $group->setLabel((count($groups) + 1));
        }

        if ($group) {
            $group->addEtudiant($etudiant);
            $etudiant->addGroupe($group);
        }
    }
}
