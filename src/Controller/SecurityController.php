<?php

namespace App\Controller;

use App\Entity\User\ResetPasswordToken;
use App\Entity\User\User;
use App\Form\PasswordResetRequestType;
use App\Form\PasswordResetType;
use App\Form\RegistrationFormType;
use App\Repository\PasswordTokenRepository;
use App\Repository\UserRepository;
use App\Service\MailerService;
use DateTime;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class SecurityController extends AbstractController
{

    public function __construct(private readonly MailerService $mailerService)
    {
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_default');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout', methods: ['GET'])]
    public function logout()
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserRepository $userRepository): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_default');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $userRepository->save($user, true);

            // generate a signed url and email it to the user
            $this->mailerService->sendEmailConfirmation('app_verify_email', $user);
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/register.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->mailerService->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre adresse email a bien été vérifiée.');

        return $this->redirectToRoute('app_register');
    }

    #[Route('/password_reset_request', name: 'app_password_reset_request')]
    public function requestPasswordReset(Request                 $request,
                                         UserRepository          $userRepository,
                                         PasswordTokenRepository $passwordTokenRepository,
                                         TokenGeneratorInterface $tokenGenerator): Response
    {
        $form = $this->createForm(PasswordResetRequestType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['email' => $form->get('email')->getData()]);

            if ($user) {
                $newResetPasswordTokens = new ResetPasswordToken();
                $newResetPasswordTokens->setUser($user);
                $newResetPasswordTokens->setToken($tokenGenerator->generateToken());
                $newResetPasswordTokens->setCreatedAt(new DateTime());
                $newResetPasswordTokens->setExpiredAt(new DateTime('+15 minutes'));

                $passwordTokenRepository->save($newResetPasswordTokens, true);

                $this->mailerService->sendResetPasswordEmail($user, $newResetPasswordTokens);

                $this->addFlash('success', 'Un email vous a été envoyé pour réinitialiser votre mot de passe.');

                return $this->redirectToRoute('app_login');
            } else {
                $this->addFlash('danger', 'Cette adresse email n\'existe pas');

                return $this->redirectToRoute('app_password_reset_request');
            }
        }

        return $this->render('security/password_reset_request.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reset_password/{token}', name: 'app_reset_password')]
    public function resetPassword(Request                     $request,
                                  UserPasswordHasherInterface $userPasswordHasher,
                                  PasswordTokenRepository     $passwordTokensRepository,
                                  UserRepository              $userRepository,
                                  string                      $token): Response
    {
        $resetPasswordToken = $passwordTokensRepository->findOneBy(['token' => $token]);

        if (!$resetPasswordToken) {
            $this->addFlash('danger', 'Ce token n\'existe pas');

            return $this->redirectToRoute('app_password_reset_request');
        }

        if ($resetPasswordToken->isUsed()) {
            $this->addFlash('danger', 'Ce token a déjà été utilisé');

            return $this->redirectToRoute('app_password_reset_request');
        }

        // get token lifetime and created at to check if token is expired
        $tokenLifetime = $resetPasswordToken->getExpiredAt()->getTimestamp() - $resetPasswordToken->getCreatedAt()->getTimestamp();

        if ($tokenLifetime > 900) {
            $this->addFlash('danger', 'Ce token a expiré');

            return $this->redirectToRoute('app_password_reset_request');
        }


        $form = $this->createForm(PasswordResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $resetPasswordToken->getUser();

            $resetPasswordToken->setUsed(true);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $passwordTokensRepository->save($resetPasswordToken, true);
            $userRepository->save($user, true);

            $this->addFlash('success', 'Votre mot de passe a été modifié');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Route pour que l'utilisateur puisse définir son mot de passe pour sa premiére connection, la requête contient un token qui permet de vérifier que l'utilisateur est bien celui qui a reçu le mail
    #[Route('/set_password/{token}', name: 'app_set_password')]
    public function setPassword(Request                     $request,
                                UserPasswordHasherInterface $userPasswordHasher,
                                PasswordTokenRepository     $passwordTokensRepository,
                                UserRepository              $userRepository,
                                string                      $token): Response
    {
        $resetPasswordToken = $passwordTokensRepository->findOneBy(['token' => $token]);

        if (!$resetPasswordToken) {
            $this->addFlash('danger', 'Ce token n\'existe pas');

            return $this->redirectToRoute('app_login');
        }

        if ($resetPasswordToken->isUsed()) {
            $this->addFlash('danger', 'Ce token a déjà été utilisé');

            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(PasswordResetType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $resetPasswordToken->getUser();

            $resetPasswordToken->setUsed(true);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('password')->getData()
                )
            );

            $user->setIsVerified(true);

            $passwordTokensRepository->save($resetPasswordToken, true);
            $userRepository->save($user, true);

            $this->addFlash('success', 'Votre mot de passe a été modifié');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/set_password.twig', [
            'form' => $form->createView(),
        ]);
    }
}
