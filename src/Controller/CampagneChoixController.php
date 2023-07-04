<?php

namespace App\Controller;

use App\Entity\Main\BlocOption;
use App\Entity\Main\CampagneChoix;
use App\Entity\Main\Etudiant;
use App\Entity\Main\EtudiantUE;
use App\Entity\Main\Groupe;
use App\Entity\Main\Parcours;
use App\Entity\Main\UE;
use App\Form\CampagneChoixDateType;
use App\Form\CampagneChoixType;
use App\Form\GroupeType;
use App\Repository\CampagneChoixRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
use App\Repository\ParcoursRepository;
use App\Repository\UERepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/admin/campagne_choix')]
class CampagneChoixController extends AbstractController
{
    #[Route('/', name: 'app_campagne_choix_index', methods: ['GET'])]
    public function index(CampagneChoixRepository $campagneChoixRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder = $campagneChoixRepository->createQueryBuilder('cc');
        $queryBuilder->leftJoin('cc.parcours', 'ccp');

        $campagneChoix = $paginator->paginate(
            $queryBuilder->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('campagne_choix/index.html.twig', [
            'campagne_choixes' => $campagneChoix,
        ]);
    }

    #[Route('/new', name: 'app_campagne_choix_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CampagneChoixRepository $campagneChoixRepository): Response
    {
        $campagneChoix = new CampagneChoix();
        $form = $this->createForm(CampagneChoixType::class, $campagneChoix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($campagneChoix->getBlocOptions() as $blocOption) {
                /* @var $blocOption BlocOption */
                $parcours = $blocOption->getBlocUE();
                $blocUeUes = $parcours->getBlocUeUes()->filter(function ($blocUeUe) {
                    return $blocUeUe->isOptional();
                });
                foreach ($blocUeUes as $blocUeUe) {
                    $blocOption->addUE($blocUeUe->getUE());
                }
            }
            $campagneChoixRepository->save($campagneChoix, true);

            return $this->redirectToRoute('app_campagne_choix_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('campagne_choix/new.html.twig', [
            'campagne_choix' => $campagneChoix,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_campagne_choix_show', methods: ['GET'])]
    public function show(CampagneChoix $campagneChoix): Response
    {
        return $this->render('campagne_choix/show.html.twig', [
            'campagne_choix' => $campagneChoix,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_campagne_choix_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CampagneChoix $campagneChoix, CampagneChoixRepository $campagneChoixRepository): Response
    {
        if ($campagneChoix->isFinished()) {
            $this->addFlash('warning', 'Impossible de modifier une campagne terminée');
            return $this->redirectToRoute('app_campagne_choix_index', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(CampagneChoixType::class, $campagneChoix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // same as in new action but we must remove all UEs from blocOptions
            foreach ($campagneChoix->getBlocOptions() as $blocOption) {
                /* @var $blocOption BlocOption */
                $blocOption->getUEs()->clear();
                $parcours = $blocOption->getBlocUE();
                $blocUeUes = $parcours->getBlocUeUes()->filter(function ($blocUeUe) {
                    return $blocUeUe->isOptional();
                });
                foreach ($blocUeUes as $blocUeUe) {
                    $blocOption->addUE($blocUeUe->getUE());
                }
            }
            $campagneChoix->getResponseCampagnes()->clear();
            $campagneChoixRepository->save($campagneChoix, true);

            return $this->redirectToRoute('app_campagne_choix_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('campagne_choix/edit.html.twig', [
            'campagne_choix' => $campagneChoix,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit_date', name: 'app_campagne_choix_edit_date', methods: ['GET', 'POST'])]
    public function editDate(Request $request, CampagneChoix $campagneChoix, CampagneChoixRepository $campagneChoixRepository): Response
    {
        if ($campagneChoix->isFinished()) {
            $this->addFlash('warning', 'Impossible de modifier une campagne terminée');
            return $this->redirectToRoute('app_campagne_choix_index', [], Response::HTTP_SEE_OTHER);
        }

        $form = $this->createForm(CampagneChoixDateType::class, $campagneChoix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $campagneChoixRepository->save($campagneChoix, true);

            return $this->redirectToRoute('app_campagne_choix_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('campagne_choix/edit_date.html.twig', [
            'campagne_choix' => $campagneChoix,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_campagne_choix_delete', methods: ['POST'])]
    public function delete(Request $request, CampagneChoix $campagneChoix, CampagneChoixRepository $campagneChoixRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $campagneChoix->getId(), $request->request->get('_token'))) {
            $campagneChoixRepository->remove($campagneChoix, true);
        }

        return $this->redirectToRoute('app_campagne_choix_index', [], Response::HTTP_SEE_OTHER);
    }

    // #[Route('/{id}/choix/{choix}', name: 'app_campagne_groupe_choix_ue', methods: ['GET', 'POST'])]
    // public function choix_groupe_ue(Request $request, $id, $choix, GroupeRepository $groupeRep, CampagneChoix $campagneChoix, CampagneChoixRepository $campagneChoixRepository, ParcoursRepository $parcoursRepository): Response
    // {
    //     // $parcours = $campagneChoix->getParcours();
    //     // foreach($parcours as $p){
    //     //     $b = $p->getBlocUEs();
    //     //     foreach($b){

    //     //     }
    //     // }
    //     return $this->render('campagne_choix/groupe_choix/choix_groupe_ue.html.twig', [
    //         'campagne_choix' => $campagneChoix,
    //     ]);
    // }

    //Crée les groupes une fois la campagne termine
    #[Route('/{campagneChoix}/choix/{choix}', name: 'app_campagne_groupe_choix', methods: ['GET', 'POST'])]
    public function choix_groupe(
        Request                 $request,
        CampagneChoix           $campagneChoix,
        int                     $choix,
        GroupeRepository        $groupeRep,
        CampagneChoixRepository $campagneChoixRepository,
        ParcoursRepository      $parcoursRepository
    ): Response
    {
        $groupe = new Groupe();
        $effectifDefaut = 25;

        foreach ($campagneChoix->getParcours() as $parcours) {
            $parcoursId = $parcours->getId();
            $parcours = $parcoursRepository->findOneBy(['id' => $parcoursId]);
            foreach ($parcours->getEtudiants() as $etudiant) {
                $result[] = $etudiant;
            }
        }

        $blocUEs = $campagneChoix->getBlocOptions();
        //Pour chaque bloc dans la campagne
        for ($g = 0; $g < count($blocUEs); $g++) {
            $blocUE = $blocUEs[$g];
            $nbUEOptional = $blocUE->getBlocUE()->getNbUEsOptional();
            $ues = $blocUE->getUEs();
            $etudiantsDecales = array();
            //Garde en memoire cb d'ue l'etudiant est inscrit
            $etudiantsUeOptionnels = array();
            //Pour chaque UE dans le bloc
            $responses = $campagneChoix->getResponseCampagnes()->toArray();

            //Pour chaque etudiant
            for ($y = 0; $y < count($responses); $y++) {
                $etudiantsUeOptionnels[$responses[$y]->getEtudiant()->getId()] = 0;
            }

            for ($i = 0; $i < count($ues); $i++) {
                $result = array();
                $UE = $ues[$i];
                $nbgrp = 2;
                if (count($etudiantsDecales) > 0) {
                    $result = array_merge($result, $etudiantsDecales);
                    $etudiantsDecales = [];
                }
                if ($UE->getNbrGroupe() != null) {
                    $nbgrp = $UE->getNbrGroupe();
                }
                if ($UE->getEffectif() != null) {
                    $effectif = $UE->getEffectif();
                    $effectifTotal = $effectif * $nbgrp;
                } else {
                    $effectif = $effectifDefaut;
                }
                $countgrp = count($UE->getGroupes());
                //On verifie qu'il y a pas de groupe
                if ($countgrp == '0') {
                    //On loop 3 fois, pour chaque ordre
                    for ($currentOrder = 1; $currentOrder < 4; $currentOrder++) {
                        $filterresponses = array_filter($responses, function ($response) use ($UE, $nbUEOptional, $currentOrder, $etudiantsUeOptionnels) {
                            $choixes = $response->getChoixes();
                            for ($z = 0; $z < count($choixes); $z++) {
                                if (($UE == $choixes[$z]->getUE()) && ($choixes[$z]->getOrdre() == $currentOrder) && ($etudiantsUeOptionnels[$response->getEtudiant()->getId()] < $nbUEOptional)) {
                                    return $response->getEtudiant();
                                } else {
                                    break;
                                }
                            }
                        });
                        //Si le nb d'etudiants trouves est superieur a l'effectif possible pour l'UE
                        //On garde le reste pour les autres UE
                        if ((count($filterresponses) + count($result)) > $effectifTotal) {
                            $indexMax = $effectifTotal - count($result);
                            if ($indexMax > 0) {
                                $output = array_slice($filterresponses, 0, $indexMax);
                                $result = array_merge($result, $output);
                            } else {
                                $etudiantsDecales = array_merge($etudiantsDecales, $filterresponses);
                            }
                        } elseif ((count($filterresponses) + count($result)) <= $effectifTotal) {
                            $result = array_merge($result, $filterresponses);
                        }
                    }
                    if (!empty($result)) {

                        $indice = 1;
                        switch ($choix) {
                            //groupe par ordre alphabetique
                            case 1:
                                usort($result, function ($a, $b) {
                                    return strcmp($a->getEtudiant()->getNom(), $b->getEtudiant()->getNom());
                                });
                                for ($j = 0; $j < count($result); $j++) {
                                    if ($j == count($result) - 1) {
                                        $groupe->addEtudiant($result[$j]->getEtudiant());
                                        $etudiantsUeOptionnels[$result[$j]->getEtudiant()->getId()] += 1;
                                        $groupe->setLabel("Groupe " . strval($indice));
                                        $UE->addGroupe($groupe);
                                        $groupeRep->save($groupe, true);
                                        $indice = $indice + 1;
                                        $groupe = new Groupe();
                                    } else {
                                        $groupe->addEtudiant($result[$j]->getEtudiant());
                                        $etudiantsUeOptionnels[$result[$j]->getEtudiant()->getId()] += 1;
                                    }

                                    if (count($groupe->getEtudiants()) >= $effectif) {
                                        $groupe->setLabel("Groupe " . strval($indice));
                                        $UE->addGroupe($groupe);
                                        $groupeRep->save($groupe, true);
                                        $indice = $indice + 1;
                                        $groupe = new Groupe();
                                    }
                                }
                                break;
                                //aleatoire
                            case 2:
                                shuffle($result);
                                for ($j = 0; $j < count($result); $j++) {
                                    if ($j == count($result) - 1) {
                                        $groupe->addEtudiant($result[$j]->getEtudiant());
                                        $etudiantsUeOptionnels[$result[$j]->getEtudiant()->getId()] += 1;
                                        $groupe->setLabel("Groupe " . strval($indice));
                                        $UE->addGroupe($groupe);
                                        $groupeRep->save($groupe, true);
                                        $indice = $indice + 1;
                                        $groupe = new Groupe();
                                    } else {
                                        $groupe->addEtudiant($result[$j]->getEtudiant());
                                        $etudiantsUeOptionnels[$result[$j]->getEtudiant()->getId()] += 1;
                                    }

                                    if (count($groupe->getEtudiants()) >= $effectif) {
                                        $groupe->setLabel("Groupe " . strval($indice));
                                        $UE->addGroupe($groupe);
                                        $groupeRep->save($groupe, true);
                                        $indice = $indice + 1;
                                        $groupe = new Groupe();
                                    }
                                }
                                break;
                            //manuel
                            //gestion dans une autre route
                            case 3:
                                $blocUEs = $campagneChoix->getBlocOptions();
                                $ues = $blocUE->getUEs();

                                foreach ($blocUEs as $blocUE) {
                                    $ues = $blocUE->getUEs();
                                    foreach ($ues as $UE) {

                                        if (count($UE->getGroupes()) != $UE->getNbrGroupe()) {
                                            for ($u = 1; $u <= $UE->getNbrGroupe(); $u++) {
                                                $groupe = new Groupe();
                                                $groupe->setLabel("Groupe " . strval($u));
                                                $UE->addGroupe($groupe);
                                                $groupeRep->save($groupe, true);
                                            }
                                        }
                                    }
                                }

                                break;
                            default:
                                break;
                        }
                    }
                }   else{
                    if($choix == 1 || $choix ==2){
                        $this->addFlash('warning', 'Il y a déjà des groupes crée pour ces UEs');
                        return $this->redirectToRoute('app_campagne_choix_index', [], Response::HTTP_SEE_OTHER);
                    }
                }

            }
        }
        $form = $this->createForm(GroupeType::class, $groupe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $campagneChoixRepository->save($campagneChoix, true);

            return $this->redirectToRoute('app_campagne_choix_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('campagne_choix/groupe_choix/_groupe_choix.html.twig', [
            'campagne_choix' => $campagneChoix,
            'form' => $form,
        ]);
    }

    #[Route('/list/{campagneChoix}/{parcours}/{id}/{type}', name: 'app_campagne_choix_get_etudiant', methods: ['POST'])]
    public function list(CampagneChoix $campagneChoix, Parcours $parcours, UE $UE, string $type = "optional"): JsonResponse
    {
        $results = array();
        $responses = $campagneChoix->getResponseCampagnes();

        $etudiantAvecGroupe = array();
        $etudiantSansGroupe = array();

        $etudiantAvecGroupeId = array();

        $groupes = $UE->getGroupes();

        foreach ($groupes as $groupe) {
            $etudiants = $groupe->getEtudiants();

            foreach ($etudiants as $etudiant) {
                if ($etudiant->getParcours() === $parcours) {
                    $selected = array(
                        'id' => $etudiant->getId(),
                        "nom" => $etudiant->getNom(),
                        "prenom" => $etudiant->getPrenom(),
                        "groupe_id" => $groupe->getId(),
                        "groupe_label" => $groupe->getLabel(),
                        "parcours" => $etudiant->getParcours()->getLabel()
                    );
                    if (!in_array($selected, $etudiantAvecGroupe)) {
                        $etudiantAvecGroupe[] = $selected;
                        $etudiantAvecGroupeId[] = $etudiant->getId();
                    }
                }
            }
        }

        $etudiantRejetesId = array();

        if ($type == "optional") {
            foreach ($responses as $r) {
                $choixes = $r->getChoixes();
                foreach ($choixes as $choix) {
                    if ($choix->getUE() == $UE) {
                        $blocOption = $choix->getBlocOption();
                        $ues = $blocOption->getUEs();
                        foreach ($ues as $ue) {
                            if ($ue != $UE) {
                                $groupes = $ue->getGroupes();
                                foreach ($groupes as $groupe) {
                                    $etudiants = $groupe->getEtudiants();
                                    foreach ($etudiants as $etudiant) {
                                        $etudiantRejetesId[] = $etudiant->getId();
                                    }
                                }
                            }
                        }


                        $etudiant = $r->getEtudiant();
                        if ($etudiant->getParcours() == $parcours) {
                            $selected = array(
                                'id' => $etudiant->getId(),
                                "nom" => $etudiant->getNom(),
                                "prenom" => $etudiant->getPrenom(),
                                'ordre' => $choix->getOrdre(),
                                'ue' => $UE->getId(),
                                'parcours' => $etudiant->getParcours()->getLabel()
                            );

                            if (!in_array($selected, $etudiantSansGroupe) && !in_array($etudiant->getId(), $etudiantAvecGroupeId) && !in_array($etudiant->getId(), $etudiantRejetesId)) {
                                $etudiantSansGroupe[] = $selected;
                            }
                        }
                    }
                }
            }
        } elseif ($type == "mandatory") {
            $etudiants = $parcours->getEtudiants();
            foreach ($etudiants as $etudiant) {
                $selected = array(
                    'id' => $etudiant->getId(),
                    "nom" => $etudiant->getNom(),
                    "prenom" => $etudiant->getPrenom(),
                    "parcours" => $etudiant->getParcours()->getLabel()
                );
                if (!in_array($selected, $etudiantAvecGroupe) && !in_array($selected, $etudiantSansGroupe)) {
                    $etudiantSansGroupe[] = $selected;
                }
            }
        }

        $type = 'optionelle';

        array_push($results, $etudiantAvecGroupe, $etudiantSansGroupe, $type);
        return $this->json($results);
    }

    #[Route('/list_obligatoire/{campagneChoix}/{parcours}/{ue}', name: 'app_campagne_choix_get_etudiant_obligatoire', methods: ['POST'])]
    public function list_obligatoire(campagneChoix $campagneChoix, Parcours $parcours, UE $ue): JsonResponse
    {

        $results = array();

        $etudiantAvecGroupe = array();
        $etudiantSansGroupe = array();

        $etudiantAvecGroupeId = array();

        $groupes = $ue->getGroupes();

        foreach ($groupes as $groupe) {
            $etudiants = $groupe->getEtudiants();

            foreach ($etudiants as $etudiant) {
                if ($etudiant->getParcours() === $parcours) {
                    $selected = array(
                        'id' => $etudiant->getId(),
                        "nom" => $etudiant->getNom(),
                        "prenom" => $etudiant->getPrenom(),
                        "groupe_id" => $groupe->getId(),
                        "groupe_label" => $groupe->getLabel(),
                        "parcours" => $etudiant->getParcours()->getLabel()
                    );
                    if (!in_array($selected, $etudiantAvecGroupe)) {
                        $etudiantAvecGroupe[] = $selected;
                        $etudiantAvecGroupeId[] = $etudiant->getId();
                    }

                }
            }
        }

        $etudiants = $parcours->getEtudiants();

        foreach ($etudiants as $etudiant) {
            $selected = array(
                'id' => $etudiant->getId(),
                "nom" => $etudiant->getNom(),
                "prenom" => $etudiant->getPrenom(),
                'ordre' => 0,
                'ue' => $ue->getId(),
                'parcours' => $etudiant->getParcours()->getLabel()
            );

            if (!in_array($selected, $etudiantSansGroupe) && !in_array($etudiant->getId(), $etudiantAvecGroupeId)) {
                $etudiantSansGroupe[] = $selected;
            }

        }

        $type = 'obligatoire';
        array_push($results, $etudiantAvecGroupe, $etudiantSansGroupe, $type);
        return $this->json($results);
    }

    //gestion du post ici
    #[Route('/api/groupe_manuel', name: 'app_campagne_choix_groupe_manuel', methods: ['GET', 'POST'])]
    public function groupe_manuel(Request $request, UERepository $UERepository, GroupeRepository $groupeRepository, EtudiantRepository $etudiantRepository): Response
    {
        if ($request->isMethod('GET')) {
            $ue = $request->get('ue');

            if (!$ue) {
                return new JsonResponse(['error' => 'ue not found']);
            }

            $ue = $UERepository->find($ue);
            $groupes = $ue->getGroupes();
            return new JsonResponse(['groupes' => $groupes->map(fn(Groupe $groupe) => [
                'maxEffectif' => $groupe->getUe()->getEffectif() / $groupe->getUe()->getNbrGroupe(),
                'currentEffectif' => $groupe->getEtudiants()->count(),
                'id' => $groupe->getId(),
                'label' => $groupe->getLabel()])->toArray()
            ]);
        }

        $etudiants = $request->get('selectionEtudiant');
        $groupeId = $request->get('choixGroupes');

        $groupeSelected = $groupeRepository->findOneById($groupeId);
        $ueSelected = $groupeSelected->getUe();

        foreach ($etudiants as $etudiant) {
            $etudiantSelected = $etudiantRepository->findOneById($etudiant);
            $etudiantSelected->addEtudiantUE(new EtudiantUE($etudiantSelected, $ueSelected));
            $groupeSelected->addEtudiant($etudiantSelected);
        }

        $groupeRepository->save($groupeSelected, true);

        return new Response('ok');
    }

    //gestion du post ici
    #[Route('/delete_etudiant_groupe/{campagneChoix}/{groupe}/{etudiant}', name: 'app_campagne_choix_delete_etudiant_groupe', methods: ['DELETE'])]
    public function delete_etudiant_groupe(CampagneChoix $campagneChoix, Groupe $groupe, Etudiant $etudiant, GroupeRepository $groupeRepository, EtudiantRepository $etudiantRepository): Response
    {
        $groupe->removeEtudiant($etudiant);
        $groupeRepository->save($groupe, true);
        $ue = $groupe->getUe();

        $etudiant->removeEtudiantUE(new EtudiantUE($etudiant, $ue));

        $this->addFlash('success', 'L\'étudiant a été retiré du groupe');

        return new Response('ok');
    }
}
