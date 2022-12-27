<?php

namespace App\Controller;

use App\Form\UserEmailType;
use App\Form\UserPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserProfileController extends AbstractController
{
    #[Route('/user/profile', name: 'app_user_profile')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request): Response
    {
        $user = $this->getUser();

        // form to edit email and an other form to edit password

        $formEmail = $this->createForm(UserEmailType::class, $user);
        $formPassword = $this->createForm(UserPasswordType::class, $user);

        $formEmail->handleRequest($request);
        $formPassword->handleRequest($request);

        

        return $this->render('user_profile/index.html.twig', [
            'user' => $user,
            'formEmail' => $formEmail->createView(),
            'formPassword' => $formPassword->createView(),
        ]);

    }
}
