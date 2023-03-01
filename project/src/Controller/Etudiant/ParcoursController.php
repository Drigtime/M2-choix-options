<?php

namespace App\Controller\Etudiant;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParcoursController extends AbstractController
{
    #[Route('/etudiant/parcours', name: 'app_etudiant_parcours')]
    public function index(): Response
    {
        return $this->render('etudiant/parcours/index.html.twig', [
            'controller_name' => 'ParcoursController',
        ]);
    }
}
