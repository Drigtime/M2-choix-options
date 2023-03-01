<?php

namespace App\Controller\Etudiant;

use App\Entity\Choix;
use App\Entity\CampagneChoix;
use App\Entity\ResponseCampagne;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EtudiantRepository;
use App\Repository\ResponseCampagneRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Form\Etudiant\ResponseCampagneType;
use App\Repository\BlocOptionRepository;
use App\Repository\CampagneChoixRepository;
use App\Repository\ChoixRepository;
use App\Repository\UERepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class ChoixOptionsController extends AbstractController
{

    #[Route('/etudiant/options', name: 'app_etudiant_choix_options')]
    public function index(EtudiantRepository $etudiantRepository): Response
    {
        $etudiant = $etudiantRepository->findOneBy(['mail' => $this->getUser()->getUserIdentifier()]);            
        $campagnes = $etudiant->getParcours()->getCampagneChoixes();
        
        return $this->render('etudiant/choix_options/index.html.twig', [
            'campagnes' => $campagnes,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_etudiant_choix_options_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CampagneChoix $campagne, ResponseCampagneRepository $responseCampagneRepository, EtudiantRepository $etudiantRepository, ChoixRepository $choixRepository, BlocOptionRepository $blocOptionRepository, UERepository $ueRepository): Response
    {
        $etudiant = $etudiantRepository->findOneBy(['mail' => $this->getUser()->getUserIdentifier()]);
        $responseCampagne = $responseCampagneRepository->findOneBy(['etudiant' => $etudiant, 'campagne' => $campagne]);
        //si la requete est de type post alors traité le retour de l'appel ajax du fichier edit.html.twig
        if ($request->isXmlHttpRequest()) {
            $jsonData = $request->getContent(); 
            $data = json_decode($jsonData, true);

            // // Vérifier si une réponse de campagne existe déjà pour l'étudiant et la campagne

            // if($responseCampagne == null) {
            //     // Créer une nouvelle réponse de campagne si elle n'existe pas encore
            //     $responseCampagne = new ResponseCampagne();
            //     $responseCampagne->setEtudiant($etudiant);
            //     $responseCampagne->setCampagne($campagne);
            //     $responseCampagneRepository->save($responseCampagne, true);
            // }

            // Parcourir les choix pour chaque UE
            foreach($data['ordre'] as $i => $ueId) {
                // Vérifier si un choix existe déjà pour la réponse de campagne et l'UE
                $choix = $choixRepository->findOneBy(['responseCampagne' => $responseCampagne, 'UE' => $ueRepository->findOneBy(['id' => $ueId]), 'blocOption' => $blocOptionRepository->findOneBy(['id' => $data['blocOptionsId']])]);
                // if($choix == null) {
                //     // Créer un nouveau choix si il n'existe pas encore
                //     $choix = new Choix();
                //     $choix->setUE($ueRepository->findOneBy(['id' => $ueId]));
                //     $choix->setOrdre($i+1);
                //     $choix->setBlocOption($blocOptionRepository->findOneBy(['id' => $data['blocOptionsId']]));
                //     $choix->setResponseCampagne($responseCampagne);
                // } else {
                    // Mettre à jour l'ordre si le choix existe déjà
                    $choix->setOrdre($i+1);
                // }
                
                $choixRepository->save($choix, true);
            }

            $response = array(
                'success' => true,
                'message' => 'Requête AJAX réussie !',
                'data' => $data
            );
    
            return new JsonResponse($response);
        }   

        if($responseCampagne == null) {
            $responseCampagne = new ResponseCampagne();
            $responseCampagne->setEtudiant($etudiant);
            $responseCampagne->setCampagne($campagne);
            $responseCampagneRepository->save($responseCampagne, true);
        }

        foreach($campagne->getBlocOptions() as $blocOption) {
            foreach ($blocOption->getUEs() as $index => $ue) {
                $choix = $choixRepository->findOneBy(['responseCampagne' => $responseCampagne, 'UE' => $ueRepository->findOneBy(['id' => $ue]), 'blocOption' => $blocOptionRepository->findOneBy(['id' => $blocOption->getId()])]);
                if ($choix == null) {
                    $choix = new Choix();
                    $choix->setUE($ue);
                    $choix->setOrdre($index + 1);
                    $blocOption->addChoix($choix);
                    $choix->setResponseCampagne($responseCampagne);
                    $choixRepository->save($choix, true);
                }
            }
        }

        $form = $this->createForm(ResponseCampagneType::class, $campagne);
        $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid()) {

        // }
        return $this->render('etudiant/choix_options/edit.html.twig', [
            'form' => $form,
            'campagne' => $campagne,
        ]);
    }
}
