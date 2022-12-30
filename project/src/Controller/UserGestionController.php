<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserGestionController extends AbstractController
{
    #[Route('/user/gestion', name: 'app_user_gestion')]
    public function index(): Response
    {
        return $this->render('user_gestion/index.html.twig', [
            'controller_name' => 'UserGestionController',
        ]);
    }
}
