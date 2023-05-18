<?php

namespace App\Service;

use App\Entity\Main\Etudiant;
use App\Entity\Main\EtudiantUE;
use App\Entity\Main\Groupe;
use App\Entity\Main\Parcours;
use App\Entity\Main\UE;

class MoveStudentService
{
    /**
     * @param Etudiant $etudiant
     * @param Parcours $parcours
     * @return Etudiant
     */
    public function moveEtudiantToParcours(Etudiant $etudiant, Parcours $parcours): Etudiant
    {
        $etudiant->getResponseCampagnes()->clear();
        $etudiant->getGroupes()->clear();
        $etudiant->getEtudiantUEs()->clear();
        $etudiant->setParcours($parcours);

        foreach ($parcours->getBlocUEs() as $blocUE) {
            $mandatoryUEs = $blocUE->getMandatoryUEs();
            $optionalUEs = $blocUE->getOptionalUEs()->slice(0, $blocUE->getNbUEsOptional());

            $ues = array_map(fn($blocUeUe) => $blocUeUe->getUe(), array_merge($mandatoryUEs->toArray(), $optionalUEs));
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
        $groups = $ue->getGroupes();
        $group = null;

        foreach ($groups as $g) {
            if ($g->getEtudiants()->count() < $ue->getEffectif()) {
                $group = $g;
                break;
            }
        }

        if (!$group) {
            $group = new Groupe();
            $group->setUe($ue);
            $group->setLabel($ue->getLabel() . "-Groupe-" . ($ue->getGroupes()->count() + 1));
        }

        $group->addEtudiant($etudiant);
        $etudiant->addGroupe($group);
    }
}