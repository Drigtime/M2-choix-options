<?php

namespace App\Controller;

use App\Entity\User\User;
use App\Form\UserEmailType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserProfileController extends AbstractController
{
    #[Route('/user/profile', name: 'app_user_profile')]
    #[IsGranted('ROLE_USER')]
    public function index(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserRepository $userRepository
    ): Response {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé.');
        }
        /** @var User $user */

        $formEmail = $this->createForm(UserEmailType::class, $user);
        $formPassword = $this->createForm(UserPasswordType::class, $user);

        $formEmail->handleRequest($request);
        $formPassword->handleRequest($request);

        if ($formEmail->isSubmitted() && $formEmail->isValid()) {
            // upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
            $userRepository->save($user, true);

            $this->addFlash('success', 'Votre email a bien été modifié.');
        }

        if ($formPassword->isSubmitted() && $formPassword->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $formPassword->get('password')->getData()
                )
            );
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
