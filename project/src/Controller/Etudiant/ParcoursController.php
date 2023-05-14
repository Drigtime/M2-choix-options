<?php

namespace App\Controller\Etudiant;

use App\Repository\EtudiantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParcoursController extends AbstractController
{
    #[Route('/etudiant', name: 'app_etudiant_parcours')]
    public function index(EtudiantRepository $etudiantRepository): Response
    {
        $etudiant = $etudiantRepository->findOneBy(['mail' => $this->getUser()->getUserIdentifier()]);
        $groupes = $etudiant->getGroupes();

        return $this->render('etudiant/parcours/index.html.twig', [
            'etudiant' => $etudiant,
            'UEsGroupes' => $groupes,
        ]);
    }
}
