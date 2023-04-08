<?php

namespace App\Controller;

use App\Entity\BlocOption;
use App\Entity\CampagneChoix;
use App\Entity\Groupe;
use App\Entity\Etudiant;
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
        dump($campagneChoix->getResponseCampagnes());

        $BlocUEs = $campagneChoix->getBlocOptions();
        foreach($BlocUEs as $bloc){
            dump($bloc->getParcours());
        }
        dump(count($BlocUEs));
        dump("test");
        //Pour chaque ue du blocUE 
        for ($g = 0; $g < count($BlocUEs); $g++) {
            $BlocUE = $BlocUEs[$g];
            dump("test12");
            dump($BlocUE);
            $UEs = $BlocUE->getUEs();
            for ($i = 0; $i < count($UEs); $i++) {
                $result = array();
                $UE = $UEs[$i];
                if(count($UE->getGroupes()) == 0){
                $responses = $campagneChoix->getResponseCampagnes();
                for($y = 0; $y < count($responses); $y++) {
                    $choixes = $responses[$y]->getChoixes();
                    for($z = 0; $z < count($BlocUEs); $z++) {
                    if($UE == $choixes[$z]->getUE()){
                        $result[] = $responses[$y]->getEtudiant(); 
                        dump($UE);
                        dump($result);
                    }
                    }
                }
            
                dump($result);
                dump(count($result));
                if(empty($result) == false){
                 
                $indice = 1;
                switch ($choix) {
                    //groupe par ordre alphabetique
                    case 1:
                        usort($result, function($a, $b) {return strcmp($a->getNom(), $b->getNom());});
                        $j = 0;
                        for($j = 0; $j < count($result); $j++) {
                            dump($result);
                            if (count($groupe->getEtudiants()) > $effectif) {
                                $groupe->setLabel($UE->getLabel() . "-Groupe-" . strval($indice));
                                $UE->addGroupe($groupe);
                                $groupeRep->save($groupe, true);
                                dump($groupe);
                                $indice = $indice + 1;
                                $groupe = new Groupe();
                            } else if ($j == count($result)-1) {
                                $groupe->addEtudiant($result[$j]);
                                $groupe->setLabel($UE->getLabel() . "-Groupe-" . strval($indice));
                                $UE->addGroupe($groupe);
                                $groupeRep->save($groupe, true);
                                dump($groupe);
                                $indice = $indice + 1;
                                $groupe = new Groupe();
                            } else {
                                $groupe->addEtudiant($result[$j]);
                                dump($groupe);
                            }
                            $j++;

                        }
                        break;
                    //aleatoire
                    case 2:
                        shuffle($result);
                        for($j = 0; $j < count($result); $j++) {
                            dump($result);
                            if (count($groupe->getEtudiants()) > $effectif) {
                                $groupe->setLabel($UE->getLabel() . "-Groupe-" . strval($indice));
                                $UE->addGroupe($groupe);
                                $groupeRep->save($groupe, true);
                                dump($groupe);
                                $indice = $indice + 1;
                                $groupe = new Groupe();
                            } else if ($j == count($result)-1) {
                                $groupe->addEtudiant($result[$j]);
                                $groupe->setLabel($UE->getLabel() . "-Groupe-" . strval($indice));
                                $UE->addGroupe($groupe);
                                $groupeRep->save($groupe, true);
                                dump($groupe);
                                $indice = $indice + 1;
                                $groupe = new Groupe();
                            } else {
                                $groupe->addEtudiant($result[$j]);
                                dump($groupe);
                            }
                            $j++;

                        }
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
        }
        $form = $this->createForm(GroupeType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $campagneChoixRepository->save($campagneChoix, true);

            return $this->redirectToRoute('app_campagne_choix_index', [], Response::HTTP_SEE_OTHER);
        }
        $informatique_SIO = array(
            'id'=>1,
            'nb_groupes'=>2,
            'intitule'=>'informatique - SIO',
            'parcours'=>'SIO',
            'ues'=>array('archi web','c#','nodeJs')
        );

        $informatique_OSIE = array(
            'id'=>2,
            'parcours'=>'OSIE',
            'nb_groupes'=>2,
            'intitule'=>'informatique - OSIE',
            'ues'=>array('archi web','c#','nodeJs')
        );

        $competence_traverse_SIO = array(
            'id'=>3,
            'parcours'=>'SIO',
            'nb_groupes'=>3,
            'intitule'=>'compétence transverse - SIO',
            'ues'=>array('anglais','espagnol')
        );

        $competence_traverse_OSIE = array(
            'id'=>4,
            'parcours'=>'OSIE',
            'nb_groupes'=>3,
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
