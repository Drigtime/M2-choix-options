<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PassageAnneeController extends AbstractController
{
    #[Route('/passage/annee', name: 'app_passage_annee')]
    public function index(): Response
    {
        return $this->render('passage_annee/index.html.twig', [
            'controller_name' => 'PassageAnneeController',
        ]);
    }
}
