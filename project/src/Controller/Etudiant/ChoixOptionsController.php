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
use App\Repository\ChoixRepository;

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
    public function edit(Request $request, CampagneChoix $campagne, ResponseCampagneRepository $responseCampagneRepository, EtudiantRepository $etudiantRepository, ChoixRepository $choixRepository): Response
    {
        foreach($campagne->getBlocOptions() as $blocOption) {
            foreach($blocOption->getUEs() as $index => $ue) {
                $choix = new Choix();
                $choix->setUE($ue);
                $choix->setOrdre($index);
                $blocOption->addChoix($choix);
                // $choixRepository->save($choix, true);
            }
        }

        $form = $this->createForm(ResponseCampagneType::class, $campagne);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $em = $this->getDoctrine()->getManager();
            // $em->persist($campagne);
            // $em->flush();
            return $this->redirectToRoute('campagne_choix_index');
        }
        return $this->render('etudiant/choix_options/edit.html.twig', [
            'form' => $form,
            'campagne' => $campagne,
        ]);
    }
}
