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
use App\Form\ChoixType;


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
    public function edit(Request $request, CampagneChoix $campagne, ResponseCampagneRepository $responseCampagneRepository, EtudiantRepository $etudiantRepository): Response
    {
        $responseCampagne = new ResponseCampagne();
        $etudiant = $etudiantRepository->findOneBy(['mail' => $this->getUser()->getUserIdentifier()]);            
        $responseCampagne
            ->setCampagne($campagne)
            ->setEtudiant($etudiant);

        foreach($campagne->getBlocOptions() as $blocOption) {
            foreach($blocOption->getUEs() as $ue) {
                $choix = new Choix();
                $choix->setUE($ue);
                $responseCampagne->addChoix($choix);
            }
        }
        
        $form = $this->createForm(ChoixType::class, $choix);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $responseCampagneRepository->save($responseCampagne, true);

            return $this->redirectToRoute('app_etudiant_choix_options', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('etudiant/choix_options/edit.html.twig', [
            'campagne' => $campagne,
            'form' => $form,
        ]);
    }
}
