<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(): Response
    {
        $user = $this->getUser();
        // check if the user is logged in
        if (!$user) {
            // redirect to the home page
            return $this->redirectToRoute('app_login');
        }

        // check if the user is an admin
        if ($this->isGranted('ROLE_ADMIN')) {
            // redirect to the admin dashboard
            return $this->redirectToRoute('app_passage_annee');
        } else if ($this->isGranted('ROLE_USER')) {
            // redirect to the user dashboard
            return $this->redirectToRoute('app_etudiant_parcours');
        }

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    #[Route('/maintenance', name: 'maintenance')]
    public function maintenance(): Response
    {
        return $this->render('default/maintenance.html.twig');
    }
}
