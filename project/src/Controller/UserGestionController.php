<?php

namespace App\Controller;

use App\Entity\UserGestion;
use App\Entity\User;
use App\Form\UserGestionType;
use App\Form\UserImportType;
use App\Repository\UserGestionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\MailerService;

#[Route('/user/gestion')]
class UserGestionController extends AbstractController
{
    public function __construct(private readonly MailerService $mailerService)
    {
    }

    #[Route('/', name: 'app_user_gestion_index', methods: ['GET', 'POST'])]
    public function index(Request $request,UserGestionRepository $userGestionRepository,UserRepository $userRepository,UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserImportType::class);
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
            $fileImport = $form->get('fileImport')->getData();

            $fileImport = fopen($fileImport, 'r');

            if($fileImport){
                while(($data = fgetcsv($fileImport)) !== false) {
                    if($data[0] != 'nom'){
                        $userGestion = new UserGestion();
                        $userGestion->setNom($data[0]);
                        $userGestion->setPrenom($data[1]);
                        $userGestion->setEmail($data[2]);
                        $userGestionRepository->save($userGestion, true);

                        $user = new User();
                        $user->setEmail($data[2]);
                        $user->setPassword('default');
                        $userRepository->save($user,true);
                        // generate a signed url and email it to the user
                        $this->mailerService->sendEmailConfirmation('app_verify_email', $user);
                    }
                }
            }
            return $this->redirectToRoute('app_user_gestion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user_gestion/index.html.twig', [
            'user_gestions' => $userGestionRepository->findAll(),
            'form'=> $form->createView()
        ]);
    }

    #[Route('/new', name: 'app_user_gestion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserGestionRepository $userGestionRepository): Response
    {
        $userGestion = new UserGestion();
        $form = $this->createForm(UserGestionType::class, $userGestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userGestionRepository->save($userGestion, true);

            return $this->redirectToRoute('app_user_gestion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_gestion/new.html.twig', [
            'user_gestion' => $userGestion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_gestion_show', methods: ['GET'])]
    public function show(UserGestion $userGestion): Response
    {
        return $this->render('user_gestion/show.html.twig', [
            'user_gestion' => $userGestion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_gestion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserGestion $userGestion, UserGestionRepository $userGestionRepository): Response
    {
        $form = $this->createForm(UserGestionType::class, $userGestion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userGestionRepository->save($userGestion, true);

            return $this->redirectToRoute('app_user_gestion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user_gestion/edit.html.twig', [
            'user_gestion' => $userGestion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_gestion_delete', methods: ['POST'])]
    public function delete(Request $request, UserGestion $userGestion, UserGestionRepository $userGestionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userGestion->getId(), $request->request->get('_token'))) {
            $userGestionRepository->remove($userGestion, true);
        }

        return $this->redirectToRoute('app_user_gestion_index', [], Response::HTTP_SEE_OTHER);
    }
}
