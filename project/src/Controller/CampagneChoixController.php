<?php

namespace App\Controller;

use App\Entity\Main\BlocOption;
use App\Entity\Main\CampagneChoix;
use App\Entity\Main\Groupe;
use App\Entity\Main\Etudiant;
use App\Entity\Main\UE;
use App\Form\CampagneChoixType;
use App\Form\GroupeType;
use App\Repository\CampagneChoixRepository;
use App\Repository\GroupeRepository;
use App\Repository\ParcoursRepository;
use Doctrine\Common\Collections\Criteria;
use Knp\Component\Pager\PaginatorInterface;
use PHPUnit\Framework\Constraint\IsEqual;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


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
            $campagneChoixRepository->save($campagneChoix, true);

            return $this->redirectToRoute('app_campagne_choix_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('campagne_choix/edit.html.twig', [
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
    //     //     dump($b);
    //     //     foreach($b){
               
    //     //     }
    //     // }
    //     return $this->render('campagne_choix/groupe_choix/choix_groupe_ue.html.twig', [
    //         'campagne_choix' => $campagneChoix,
    //     ]);
    // }


    //Crée les groupes une fois la campagne termine 
    #[Route('/{id}/choix/{choix}', name: 'app_campagne_groupe_choix', methods: ['GET', 'POST'])]
    public function choix_groupe(Request $request, $id, $choix, GroupeRepository $groupeRep, CampagneChoix $campagneChoix, CampagneChoixRepository $campagneChoixRepository, ParcoursRepository $parcoursRepository): Response
    {
        $groupe = new Groupe();
        $indice = 1;
        $UE = null;
        $effectif_defaut = 25;

        foreach ($campagneChoix->getParcours() as $parcours) {
            dump($parcours);
            $parcours_id = $parcours->getId();
            $parcours = $parcoursRepository->findOneBy(['id' => $parcours_id]);
            foreach ($parcours->getEtudiants() as $etudiant) {
                $result[] = $etudiant;
            }
        }

        $BlocUEs = $campagneChoix->getBlocOptions();
        //Pour chaque bloc dans la campagne 
        for ($g = 0; $g < count($BlocUEs); $g++) {
            dump("nouveau bloc UE");
            $BlocUE = $BlocUEs[$g];
            $nbUEOptional = $BlocUE->getBlocUE()->getNbUEsOptional();
            $UEs = $BlocUE->getUEs();
            $EtudiantsDecales = array();
            //Garde en memoire cb d'ue l'etudiant est inscrit 
            $EtudiantsUeOptionnels = array(); 
            //Pour chaque UE dans le bloc
            $responses = $campagneChoix->getResponseCampagnes()->toArray();
                    
            //Pour chaque etudiant
            for ($y = 0; $y < count($responses); $y++) {
                $EtudiantsUeOptionnels[$responses[$y]->getEtudiant()->getId()] = 0; 
            }

            for ($i = 0; $i < count($UEs); $i++) {
                dump($EtudiantsUeOptionnels);
                dump("nouvelle UE");
                $result = array();
                $UE = $UEs[$i];
                $nbgrp = 2;
                if(count($EtudiantsDecales) > 0){
                    $result = array_merge($result, $EtudiantsDecales);
                    $EtudiantsDecales = [];

                }
                if($UE->getNbrGroupe()!=null){
                    $nbgrp = $UE->getNbrGroupe(); 
                }
                if($UE->getEffectif()!=null){
                    $effectif = $UE->getEffectif();
                    $effectif_total = $effectif * $nbgrp; 
                } else {
                    $effectif = $effectif_defaut;
                }
                $countgrp =count($UE->getGroupes());
                //On verifie qu'il y a pas de groupe 
                if ($countgrp == '0') {
                    //On loop 3 fois, pour chaque ordre
                    for($currentOrder =1; $currentOrder < 4; $currentOrder++){
                        $filterresponses = array_filter($responses, function($response) use($UE, $nbUEOptional, $currentOrder, $EtudiantsUeOptionnels){  
                            $choixes = $response->getChoixes();
                            for ($z = 0; $z < count($choixes); $z++) {
                                if (($UE == $choixes[$z]->getUE()) && ($choixes[$z]->getOrdre() == $currentOrder) && ($EtudiantsUeOptionnels[$response->getEtudiant()->getId()] < $nbUEOptional)) {
                                    dump($response->getEtudiant());
                                    return $response->getEtudiant();
                                } else {
                                    break; 
                                }
                            }
                        });
                        //Si le nb d'etudiants trouves est superieur a l'effectif possible pour l'UE
                        //On garde le reste pour les autres UE
                        if((count($filterresponses)+count($result)) > $effectif_total){
                            $index_max = $effectif_total - count($result); 
                            if($index_max > 0){
                                $output = array_slice($filterresponses, 0, $index_max);
                                $result = array_merge($result, $output); 
                            } else {
                                $EtudiantsDecales = array_merge($EtudiantsDecales, $filterresponses); 
                            }
                        } else if((count($filterresponses)+count($result)) <= $effectif_total){
                            $result = array_merge($result, $filterresponses);
                        }
                    }
                    dump($nbgrp);
                    dump($effectif_total);
                    dump($result);
                    dump($EtudiantsUeOptionnels);
                    dump($EtudiantsDecales);
                    if (empty($result) == false) {

                        $indice = 1;
                        switch ($choix) {
                                //groupe par ordre alphabetique
                            case 1:
                                usort($result, function ($a, $b) {
                                    return strcmp($a->getEtudiant()->getNom(), $b->getEtudiant()->getNom());
                                });
                                for ($j = 0; $j < count($result); $j++) {
                                    dump(count($result));
                                    if ($j == count($result) - 1) {
                                        $groupe->addEtudiant($result[$j]->getEtudiant());
                                        $EtudiantsUeOptionnels[$result[$j]->getEtudiant()->getId()] +=1; 
                                        dump($EtudiantsUeOptionnels[$result[$j]->getEtudiant()->getId()]);
                                        $groupe->setLabel($UE->getLabel() . "-Groupe-" . strval($indice));
                                        $UE->addGroupe($groupe);
                                        $groupeRep->save($groupe, true);
                                        dump($groupe);
                                        $indice = $indice + 1;
                                        $groupe = new Groupe();
                                    } else {
                                        $groupe->addEtudiant($result[$j]->getEtudiant());
                                        $EtudiantsUeOptionnels[$result[$j]->getEtudiant()->getId()] +=1; 
                                        dump($EtudiantsUeOptionnels[$result[$j]->getEtudiant()->getId()]);
                                        dump($groupe);
                                    }

                                    if (count($groupe->getEtudiants()) >= $effectif) {
                                        $groupe->setLabel($UE->getLabel() . "-Groupe-" . strval($indice));
                                        $UE->addGroupe($groupe);
                                        $groupeRep->save($groupe, true);
                                        dump($groupe);
                                        $indice = $indice + 1;
                                        $groupe = new Groupe();
                                    } 
                                }
                                break;
                                //aleatoire
                            case 2:
                                shuffle($result);
                                for ($j = 0; $j < count($result); $j++) {
                                    dump(count($result));
                                    if ($j == count($result) - 1) {
                                        $groupe->addEtudiant($result[$j]->getEtudiant());
                                        $EtudiantsUeOptionnels[$result[$j]->getEtudiant()->getId()] +=1; 
                                        dump($EtudiantsUeOptionnels[$result[$j]->getEtudiant()->getId()]);
                                        $groupe->setLabel($UE->getLabel() . "-Groupe-" . strval($indice));
                                        $UE->addGroupe($groupe);
                                        $groupeRep->save($groupe, true);
                                        dump($groupe);
                                        $indice = $indice + 1;
                                        $groupe = new Groupe();
                                    } else {
                                        $groupe->addEtudiant($result[$j]->getEtudiant());
                                        $EtudiantsUeOptionnels[$result[$j]->getEtudiant()->getId()] +=1; 
                                        dump($EtudiantsUeOptionnels[$result[$j]->getEtudiant()->getId()]);
                                        dump($groupe);
                                    }

                                    if (count($groupe->getEtudiants()) >= $effectif) {
                                        $groupe->setLabel($UE->getLabel() . "-Groupe-" . strval($indice));
                                        $UE->addGroupe($groupe);
                                        $groupeRep->save($groupe, true);
                                        dump($groupe);
                                        $indice = $indice + 1;
                                        $groupe = new Groupe();
                                    } 
                                }
                                break;
                                //manuel
                                //gestion dans une autre route 
                            case 3:
                                break;
                        } 
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

    #[Route('/list/{id}/{ue_id}', name: 'app_campagne_choix_get_etudiant', methods: ['POST'])]
    public function list(Request $request, $id, $ue_id, campagneChoix $campagneChoix, UE $UE): JsonResponse
    {

        $results = array();
        $responses = $campagneChoix->getResponseCampagnes();

        foreach($responses as $r){
            dump($r);
            $choixes = $r->getChoixes();
            dump($choixes);
            foreach($choixes as $choix){
                if($choix->getUE() == $UE){
                    $results[] = $r->getEtudiant();
                }
            }
        }








        



        // foreach($parcours as $parcour){
        //     if($parcour->getId() == $parcours_id){
        //         $etudiants = $parcour->getEtudiants();
        //         foreach($etudiants as $etudiant){
        //             $results[] = $etudiant;
        //         }
        //     }
        // }

        return $this->json(array_map(function($etudiant){
            return [
              "id" => $etudiant->getId(),
              "nom" => $etudiant->getNom(),
              "prenom" => $etudiant->getPrenom(),
            ];
          }, $results));
    }

    //gestion du post ici
    #[Route('/groupe_manuel/{id}', name: 'app_campagne_choix_groupe_manuel', methods: ['POST'])]
    public function groupe_manuel(Request $request ,$id, campagneChoix $campagneChoix):Response
    {

        dump($request);
        
        return $this->redirectToRoute('app_campagne_groupe_choix', [
            'id' => $id,
            'choix' => '3'
        ], Response::HTTP_SEE_OTHER);
    }

    
}
