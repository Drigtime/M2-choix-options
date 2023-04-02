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

    //Crée les groupes une fois la campagne termine 
    #[Route('/{id}/choix/{choix}', name: 'app_campagne_groupe_choix', methods: ['GET', 'POST'])]
    public function choix_groupe(Request $request, $id, $choix, GroupeRepository $groupeRep, CampagneChoix $campagneChoix, CampagneChoixRepository $campagneChoixRepository, ParcoursRepository $parcoursRepository): Response
    {
        $groupe = new Groupe();
        $indice = 1;
        $UE = null;
        $effectif = 25;
        
        foreach ($campagneChoix->getParcours() as $parcours) {
            dump($parcours);
            $parcours_id = $parcours->getId();
            $parcours = $parcoursRepository->findOneBy(['id' => $parcours_id]);
            foreach ($parcours->getEtudiants() as $etudiant) {
                $result[] = $etudiant;
            }
        }
        /*
        dump($campagneChoix->getResponseCampagnes());
        foreach ($campagneChoix->getResponseCampagnes() as $responsecampagne) {
            foreach ($responsecampagne->getEtudiant() as $etudiant) {
                $result[] = $etudiant;
            }
        }*/

        dump($result);
        $BlocUEs = $campagneChoix->getBlocOptions();
        foreach($BlocUEs as $bloc){
            dump($bloc->getParcours());
        }
        dump(count($BlocUEs));
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
                        //fausses data pour avancer le front

                     

                        

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
        $informatique_SIO = array(
            'id'=>1,
            'intitule'=>'informatique - SIO',
            'parcours'=>'SIO',
            'ues'=>array('archi web','c#','nodeJs')
        );

        $informatique_OSIE = array(
            'id'=>2,
            'parcours'=>'OSIE',
            'intitule'=>'informatique - OSIE',
            'ues'=>array('archi web','c#','nodeJs')
        );

        $competence_traverse_SIO = array(
            'id'=>3,
            'parcours'=>'SIO',
            'intitule'=>'compétence transverse - SIO',
            'ues'=>array('anglais','espagnol')
        );

        $competence_traverse_OSIE = array(
            'id'=>4,
            'parcours'=>'OSIE',
            'intitule'=>'compétence transverse - OSIE',
            'ues'=>array('anglais','espagnol')
        );

        $blocsUES = array();
        array_push($blocsUES,$informatique_SIO,$informatique_OSIE,$competence_traverse_SIO,$competence_traverse_OSIE);

        return $this->render('campagne_choix/groupe_choix/_groupe_choix.html.twig', [
            'campagne_choix' => $campagneChoix,
            'blocsUES' => $blocsUES,
            'form' => $form,
        ]);
    }

    #[Route('/list/{parcours}', name: 'app_campagne_choix_get_etudiant', methods: ['POST'])]
    public function list(Request $request,$parcours): JsonResponse
    {
        $etudiants_OSIE = array(
            'jean lafesse',
            'henry pointevant',
            'igor dosgore',
            'brice denice',
            'daniel craig',

        );

        $etudiants_SIO = array(
            'andrew tate',
            'napoleon bonaparte',
            'john wick',
            'solomon grundy',
            'miyamoto musashi',
        );

        $data = $request->getContent();

        dump($parcours);
        dump($data);

        $etudiants = array();

        if($data == 'OSIE'){
            $etudiants = $etudiants_OSIE;
        }
        elseif($data == 'SIO'){
            $etudiants = $etudiants_SIO;
        }

        
        
        return $this->json($etudiants);
    }


    
}
