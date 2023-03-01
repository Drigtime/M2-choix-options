<?php

namespace App\Controller;

use App\Entity\BlocOption;
use App\Entity\Groupe;
use App\Entity\BlocUE;
use App\Entity\CampagneChoix;
use App\Entity\Parcours;
use App\Form\BlocOptionType;
use App\Form\BlocUEType;
use App\Form\CampagneChoixType;
use App\Form\GroupeType;
use App\Repository\BlocOptionRepository;
use App\Repository\BlocUERepository;
use App\Repository\CampagneChoixRepository;
use App\Repository\ParcoursRepository;
use App\Repository\GroupeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/campagne_choix')]
class CampagneChoixController extends AbstractController
{
    #[Route('/', name: 'app_campagne_choix_index', methods: ['GET'])]
    public function index(CampagneChoixRepository $campagneChoixRepository): Response
    {
        return $this->render('campagne_choix/index.html.twig', [
            'campagne_choixes' => $campagneChoixRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_campagne_choix_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CampagneChoixRepository $campagneChoixRepository): Response
    {
        $campagneChoix = new CampagneChoix();
        $form = $this->createForm(CampagneChoixType::class, $campagneChoix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $campagneChoixRepository->save($campagneChoix, true);

            return $this->redirectToRoute('app_campagne_choix_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('campagne_choix/new.html.twig', [
            'campagne_choix' => $campagneChoix,
            'form' => $form,
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
        if ($this->isCsrfTokenValid('delete'.$campagneChoix->getId(), $request->request->get('_token'))) {
            $campagneChoixRepository->remove($campagneChoix, true);
        }

        return $this->redirectToRoute('app_campagne_choix_index', [], Response::HTTP_SEE_OTHER);
    }

    //Crée les groupes une fois la campagne termine 
    #[Route('/{id}/choix/{choix}', name: 'app_campagne_groupe_choix', methods: ['GET', 'POST'])]
    public function choix_groupe(Request $request, $id, $choix, GroupeRepository $groupeRep,CampagneChoix $campagneChoix, CampagneChoixRepository $campagneChoixRepository, ParcoursRepository $parcoursRepository): Response
    {
        $groupe = new Groupe();
        $indice=1;
        $UE = null;
        $effectif=25;
        $parcours = $campagneChoix->getParcours();
        $parcours_id = $parcours->getId();
        $parcours = $parcoursRepository->findOneBy(['id' => $parcours_id]);
        dump($parcours);
        foreach ($parcours->getEtudiants() as $etudiant) {
            $result[] = $etudiant;
        }
        $choixes = $campagneChoix->getResponseCampagnes();
        $BlocUEs =  $campagneChoix->getBlocOptions();
        dump($result);
        dump("test");
        //Pour chaque ue du blocUE 
        for($g = 0; $g < count($BlocUEs); $g++)
        {
            $BlocUE = $BlocUEs[$g];
            dump("test12");
            dump($BlocUE);
            $UEs = $BlocUE->getUEs();
            for($i = 0; $i < count($UEs); $i++)
            {
                $UE=$UEs[$i];
                dump("test123");
                // $result = array_keys(array_filter($choixes, function($v){
                //     if($v.getUE() == $UE){
                //         return $v->getEtudiant();
                //     }
                // }));
                dump("etudiants");
                $indice = 1;
                switch($choix) {
                    //groupe par ordre alphabetique
                    case 1:
                        $j=0;
                        while($j < count($result))
                        {
                            $j++;
                            if(count($groupe->getEtudiants()) >= $effectif)
                            {
                                $groupe->setLabel($UE->getLabel() . "-Groupe-" . strval($indice));
                                $UE->addGroupe($groupe);
                                $groupeRep->save($groupe, true);
                                dump($groupe);
                                $indice = $indice+1;
                                $groupe = new Groupe();
                            }
                            else if($j == count($result))
                            {
                                $groupe->setLabel($UE->getLabel() . "-Groupe-" . strval($indice));
                                $UE->addGroupe($groupe);
                                $groupeRep->save($groupe, true);
                                dump($groupe);
                                $indice = $indice+1;
                                $groupe = new Groupe();
                            } 
                            else
                            {
                                $groupe->addEtudiant($result[$j]);
                            }

                        }
                        dump($groupe);
                        $groupeRep->save($groupe);
                        break;
                    //aleatoire
                    case 2:
                        shuffle($result);
                        $j=0;
                        while($j < count($result))
                        {
                            $j++;
                            if(count($groupe->getEtudiants()) >= $effectif )
                            {
                                $groupe->setLabel($UE->getLabel() . "-Groupe-" . strval($indice));
                                $UE->addGroupe($groupe);
                                $indice = $indice+1;
                                dump($groupe);
                                $groupe = new Groupe();
                                $groupe->addEtudiant($result[$j]);
                            }
                            else if($j == count($result))
                            {
                                $groupe->setLabel($UE->getLabel() . "-Groupe-" . strval($indice));
                                $UE->addGroupe($groupe);
                                dump($groupe);
                                $indice = $indice+1;
                                $groupe = new Groupe();
                            } 
                            else
                            {
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

    // add bloc ue, with ajax
    #[Route('/{id}/bloc_option/add', name: 'app_campagnechoix_add_bloc_option', methods: ['GET', 'POST'])]
    public function addBlocOption(Request $request, CampagneChoix $campagneChoix, BlocOptionRepository $blocOptionRepository): Response
    {
        $blocOption = new BlocOption();
        $blocOption->setCampagneChoix($campagneChoix);
        $form = $this->createForm(BlocOptionType::class, $blocOption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blocOptionRepository->save($blocOption, true);

            return $this->render('campagne_choix/bloc_option/_list.html.twig', [
                'campagne' => $campagneChoix,
                'bloc_options' => $campagneChoix->getBlocOptions(),
            ]);
        }

        return $this->render('campagne_choix/bloc_option/_form.html.twig', [
            'bloc_option' => $blocOption,
            'form' => $form,
        ]);
    }

    // edit bloc ue, with ajax
    #[Route('/{id}/bloc_option/edit/{blocOption}', name: 'app_campagnechoix_edit_bloc_option', methods: ['GET', 'POST'])]
    public function editBlocOption(Request $request, CampagneChoix $campagneChoix, BlocOptionRepository $blocOptionRepository, BlocOption $blocOption): Response
    {
        $form = $this->createForm(BlocOptionType::class, $blocOption);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $blocOptionRepository->save($blocOption, true);

            return $this->render('campagne_choix/bloc_option/_list.html.twig', [
                'campagne' => $campagneChoix,
                'bloc_options' => $campagneChoix->getBlocOptions(),
            ]);
        }

        return $this->render('campagne_choix/bloc_option/_form.html.twig', [
            'bloc_option' => $blocOption,
            'form' => $form,
        ]);
    }

    // delete bloc ue, with ajax
    #[Route('/{id}/bloc_option/delete/{blocOption}', name: 'app_campagnechoix_delete_bloc_option', methods: ['GET', 'POST'])]
    public function deleteBlocOption(Request $request, CampagneChoix $campagneChoix, BlocOptionRepository $blocOptionRepository, BlocOption $blocOption): Response
    {
        if ($this->isCsrfTokenValid('delete' . $blocOption->getId(), $request->request->get('_token'))) {
            $blocOptionRepository->remove($blocOption, true);

            return $this->render('campagne_choix/bloc_option/_list.html.twig', [
                'campagne' => $campagneChoix,
                'bloc_options' => $campagneChoix->getBlocOptions(),
            ]);
        }

        return $this->render('campagne_choix/bloc_option/_delete_form.html.twig', [
            'campagne' => $campagneChoix,
            'bloc_option' => $blocOption,
        ]);
    }
}
