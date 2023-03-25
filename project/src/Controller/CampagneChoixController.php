<?php

namespace App\Controller;

use App\Entity\BlocOption;
use App\Entity\CampagneChoix;
use App\Entity\Groupe;
use App\Form\CampagneChoixType;
use App\Form\GroupeType;
use App\Repository\CampagneChoixRepository;
use App\Repository\GroupeRepository;
use App\Repository\ParcoursRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

    //Crée les groupes une fois la campagne termine 
    #[Route('/{id}/choix/{choix}', name: 'app_campagne_groupe_choix', methods: ['GET', 'POST'])]
    public function choix_groupe(Request $request, $id, $choix, GroupeRepository $groupeRep, CampagneChoix $campagneChoix, CampagneChoixRepository $campagneChoixRepository, ParcoursRepository $parcoursRepository): Response
    {
        $groupe = new Groupe();
        $indice = 1;
        $UE = null;
        $effectif = 25;
        $parcours = $campagneChoix->getParcours();
        $parcours_id = $parcours->getId();
        $parcours = $parcoursRepository->findOneBy(['id' => $parcours_id]);
        dump($parcours);
        foreach ($parcours->getEtudiants() as $etudiant) {
            $result[] = $etudiant;
        }
        $choixes = $campagneChoix->getResponseCampagnes();
        $BlocUEs = $campagneChoix->getBlocOptions();
        dump($result);
        dump("test");
        //Pour chaque ue du blocUE 
        for ($g = 0; $g < count($BlocUEs); $g++) {
            $BlocUE = $BlocUEs[$g];
            dump("test12");
            dump($BlocUE);
            $UEs = $BlocUE->getUEs();
            for ($i = 0; $i < count($UEs); $i++) {
                $UE = $UEs[$i];
                if(count($UE->getGroupes()) == 0){
                dump("test123");
                // $result = array_keys(array_filter($choixes, function($v){
                //     if($v.getUE() == $UE){
                //         return $v->getEtudiant();
                //     }
                // }));
                dump("etudiants");
                $indice = 1;
                switch ($choix) {
                    //groupe par ordre alphabetique
                    case 1:
                        $j = 0;
                        while ($j < count($result)) {
                            $j++;
                            if (count($groupe->getEtudiants()) >= $effectif) {
                                $groupe->setLabel($UE->getLabel() . "-Groupe-" . strval($indice));
                                $UE->addGroupe($groupe);
                                $groupeRep->save($groupe, true);
                                dump($groupe);
                                $indice = $indice + 1;
                                $groupe = new Groupe();
                            } else if ($j == count($result)) {
                                $groupe->setLabel($UE->getLabel() . "-Groupe-" . strval($indice));
                                $UE->addGroupe($groupe);
                                $groupeRep->save($groupe, true);
                                dump($groupe);
                                $indice = $indice + 1;
                                $groupe = new Groupe();
                            } else {
                                $groupe->addEtudiant($result[$j]);
                            }

                        }
                        dump($groupe);
                        $groupeRep->save($groupe);
                        break;
                    //aleatoire
                    case 2:
                        shuffle($result);
                        $j = 0;
                        while ($j < count($result)) {
                            $j++;
                            if (count($groupe->getEtudiants()) >= $effectif) {
                                $groupe->setLabel($UE->getLabel() . "-Groupe-" . strval($indice));
                                $UE->addGroupe($groupe);
                                $indice = $indice + 1;
                                dump($groupe);
                                $groupe = new Groupe();
                                $groupe->addEtudiant($result[$j]);
                            } else if ($j == count($result)) {
                                $groupe->setLabel($UE->getLabel() . "-Groupe-" . strval($indice));
                                $UE->addGroupe($groupe);
                                dump($groupe);
                                $indice = $indice + 1;
                                $groupe = new Groupe();
                            } else {
                                $groupe->addEtudiant($result[$j]);
                            }
                        }
                        dump($groupe);
                        $UE->addGroupe($groupe);
                        $groupeRep->save($groupe, true);
                        break;
                    //manuel
                    case 3:
                        //a implementer 
                        break;
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
    
}
