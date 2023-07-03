<?php

namespace App\Controller;

use App\Form\UserEmailType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserProfileController extends AbstractController
{
    #[Route('/user/profile', name: 'app_user_profile')]
    #[IsGranted('ROLE_USER')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $user = $this->getUser();

        // form to edit email and an other form to edit password

        $formEmail = $this->createForm(UserEmailType::class, $user);
        $formPassword = $this->createForm(UserPasswordType::class, $user);

        $formEmail->handleRequest($request);
        $formPassword->handleRequest($request);

        if ($formEmail->isSubmitted() && $formEmail->isValid()) {
            $userRepository->save($user, true);

            $this->addFlash('success', 'Votre email a bien été modifié.');
        }

        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $userRepository->save($user, true);

            $this->addFlash('success', 'Votre mot de passe a bien été modifié.');
        }

        return $this->render('user_profile/index.html.twig', [
            'user' => $user,
            'formEmail' => $formEmail->createView(),
            'formPassword' => $formPassword->createView(),
        ]);

    }
}
