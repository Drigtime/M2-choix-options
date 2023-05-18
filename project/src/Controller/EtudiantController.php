<?php

namespace App\Controller;

use App\Entity\Main\Etudiant;
use App\Entity\User\ResetPasswordToken;
use App\Entity\User\User;
use App\Form\EtudiantType;
use App\Form\UserImportType;
use App\Repository\EtudiantRepository;
use App\Repository\PasswordTokenRepository;
use App\Repository\UserRepository;
use App\Service\MailerService;
use App\Service\MoveStudentService;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

#[Route('/admin/etudiant')]
class EtudiantController extends AbstractController
{
    public function __construct(private readonly MailerService $mailerService)
    {
    }

    #[Route('/', name: 'app_etudiant_index', methods: ['GET', 'POST'])]
    public function index(Request                     $request,
                          PaginatorInterface          $paginator,
                          EtudiantRepository          $etudiantRepository,
                          UserRepository              $userRepository,
                          PasswordTokenRepository     $passwordTokenRepository,
                          UserPasswordHasherInterface $userPasswordHasher,
                          TokenGeneratorInterface     $tokenGenerator,
                          MoveStudentService          $moveStudentService): Response
    {
        $form = $this->createForm(UserImportType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('fileImport')->getData();
            $parcours = $form->get('parcours')->getData();

            if ($file == null || $parcours == null) {
                $this->addFlash('danger', 'Le formulaire n\'est pas valide');
                return $this->redirectToRoute('app_etudiant_index');
            }

            if (!$file instanceof UploadedFile) {
                $this->addFlash('danger', 'Le fichier n\'a pas été trouvé');
                return $this->redirectToRoute('app_etudiant_index');
            }

            $fileMimeType = $file->getMimeType();

            $reader = match ($fileMimeType) {
                'application/vnd.ms-excel' => new Xls(),
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => new Xlsx(),
                'text/csv', 'text/plain' => new Csv(),
                default => null,
            };

            if ($reader == null) {
                $this->addFlash('danger', 'Le fichier doit être un fichier Excel ou CSV');
                return $this->redirectToRoute('app_etudiant_index');
            }

            try {
                // Validate columns, there must be 3 columns in the file (nom, prenom, mail)
                $reader->setReadDataOnly(true);
                $spreadsheet = $reader->load($file);
                $worksheet = $spreadsheet->getActiveSheet();
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();

                $highestColumnIndex = Coordinate::columnIndexFromString($highestColumn);
                if ($highestColumnIndex != 3) {
                    $this->addFlash('danger', 'Le fichier doit contenir 3 colonnes (nom, prenom, mail)');
                    return $this->redirectToRoute('app_etudiant_index');
                }

                // Read rows
                for ($row = 1; $row <= $highestRow; ++$row) {
                    $nom = $worksheet->getCell([1, $row])->getValue();
                    $prenom = $worksheet->getCell([2, $row])->getValue();
                    $mail = $worksheet->getCell([3, $row])->getValue();

                    // validate email
                    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                        $this->addFlash('danger', 'Le mail ' . $mail . ' n\'est pas valide');
                        return $this->redirectToRoute('app_etudiant_index');
                    }

                    // Check if the user already exists
                    $user = $userRepository->findOneByMail($mail);
                    if ($user == null) {
                        // Create a new user
                        $user = new User();
                        $user->setEmail($mail);
                        $user->setRoles(['ROLE_USER']);

                        // générer un mot de passe aléatoire de 10 caractères
                        $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
                        $user->setPassword($userPasswordHasher->hashPassword($user, $password));

                        $userRepository->save($user, true);

                        // Create a new student
                        $etudiant = new Etudiant();
                        $etudiant->setNom($nom);
                        $etudiant->setPrenom($prenom);
                        $etudiant->setMail($mail);

                        $moveStudentService->moveEtudiantToParcours($etudiant, $parcours);

                        $etudiantRepository->save($etudiant, true);

                        $newResetPasswordTokens = new ResetPasswordToken();
                        $newResetPasswordTokens->setUser($user);
                        $newResetPasswordTokens->setToken($tokenGenerator->generateToken());
                        $newResetPasswordTokens->setCreatedAt(new DateTime());
                        $newResetPasswordTokens->setExpiredAt(new DateTime('+100 years'));

                        $passwordTokenRepository->save($newResetPasswordTokens, true);

                        $this->mailerService->sendEmailAccountCreated($user, $etudiant, $newResetPasswordTokens);
                    }
                }
            } catch (Exception) {
                $this->addFlash('danger', 'Une erreur est survenue lors de l\'importation du fichier');
                return $this->redirectToRoute('app_etudiant_index');
            }

            $this->addFlash('success', 'Les étudiants ont bien été importés');
            return $this->redirectToRoute('app_etudiant_index');
        }

        $query = $etudiantRepository->findAll();

        $etudiants = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('etudiant/index.html.twig', [
            'etudiants' => $etudiants,
            'form' => $form->createView()
        ]);
    }

    #[Route('/new', name: 'app_etudiant_new', methods: ['GET', 'POST'])]
    public function new(Request                     $request,
                        EtudiantRepository          $etudiantRepository,
                        UserRepository              $userRepository,
                        PasswordTokenRepository     $passwordTokenRepository,
                        MoveStudentService          $moveStudentService,
                        UserPasswordHasherInterface $userPasswordHasher,
                        TokenGeneratorInterface     $tokenGenerator): Response
    {
        $etudiant = new Etudiant();

        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user_exist = $userRepository->findOneByMail($etudiant->getMail());

            if ($user_exist == null) {
                $etudiantRepository->save($etudiant, true);
                $user = new User();
                $user->setEmail($etudiant->getMail());
                $user->setRoles(['ROLE_USER']);

                // générer un mot de passe aléatoire de 10 caractères
                $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
                $user->setPassword($userPasswordHasher->hashPassword($user, $password));

                $userRepository->save($user, true);

                $newResetPasswordTokens = new ResetPasswordToken();
                $newResetPasswordTokens->setUser($user);
                $newResetPasswordTokens->setToken($tokenGenerator->generateToken());
                $newResetPasswordTokens->setCreatedAt(new DateTime());
                $newResetPasswordTokens->setExpiredAt(new DateTime('+100 years'));

                $passwordTokenRepository->save($newResetPasswordTokens, true);

                $this->mailerService->sendEmailAccountCreated($user, $etudiant, $newResetPasswordTokens);

                return $this->redirectToRoute('app_etudiant_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('danger', 'Un compte existe déjà avec cette adresse mail');
            }
        }

        return $this->render('etudiant/new.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_etudiant_show', methods: ['GET'])]
    public function show(Etudiant $etudiant): Response
    {
        return $this->render('etudiant/show.html.twig', [
            'etudiant' => $etudiant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_etudiant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etudiant $etudiant, EtudiantRepository $etudiantRepository): Response
    {
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etudiantRepository->save($etudiant, true);

            return $this->redirectToRoute('app_etudiant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('etudiant/edit.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_etudiant_delete', methods: ['POST'])]
    public function delete(Request $request, Etudiant $etudiant, EtudiantRepository $etudiantRepository): Response
    {
        try {
            if ($this->isCsrfTokenValid('delete' . $etudiant->getId(), $request->request->get('_token'))) {
                $etudiantRepository->remove($etudiant, true);
            }

            $this->addFlash('success', 'L\'étudiant a bien été supprimé');
        } catch (Exception) {
            $this->addFlash('danger', 'Une erreur est survenue lors de la suppression de l\'étudiant');
        }

        return $this->redirectToRoute('app_etudiant_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/delete', name: 'app_etudiant_ajax_delete', methods: ['POST'])]
    public function ajaxDelete(Request $request, Etudiant $etudiant, EtudiantRepository $etudiantRepository, UserRepository $userRepository): JsonResponse
    {
        $response = [
            'redirect' => $this->generateUrl('app_etudiant_index')
        ];

        try {
            if ($this->isCsrfTokenValid('delete' . $etudiant->getId(), $request->request->get('_token'))) {
                $user = $userRepository->findOneByEmail($etudiant->getMail());
                if ($user != null) {
                    $userRepository->remove($user, true);
                }
                $etudiantRepository->remove($etudiant, true);
            }

            $this->addFlash('success', 'L\'étudiant a bien été supprimé');
            $response['status'] = 'ok';
        } catch (Exception) {
            $this->addFlash('danger', 'Une erreur est survenue lors de la suppression de l\'étudiant');
            $response['status'] = 'error';
        }

        return new JsonResponse($response, Response::HTTP_OK);
    }

    #[Route('/{id}/renvoie', name: 'app_etudiant_renvoyer', methods: ['GET', 'POST'])]
    public function renvoie(Etudiant                $etudiant,
                            UserRepository          $userRepository,
                            PasswordTokenRepository $passwordTokenRepository,
                            TokenGeneratorInterface $tokenGenerator): Response
    {
        $user = $userRepository->findOneByEmail($etudiant->getMail());

        if ($user != null) {
            $newResetPasswordTokens = new ResetPasswordToken();
            $newResetPasswordTokens->setUser($user);
            $newResetPasswordTokens->setToken($tokenGenerator->generateToken());
            $newResetPasswordTokens->setCreatedAt(new DateTime());
            $newResetPasswordTokens->setExpiredAt(new DateTime('+100 years'));

            $passwordTokenRepository->save($newResetPasswordTokens, true);

            $this->mailerService->sendEmailAccountCreated($user, $etudiant, $newResetPasswordTokens);

            $this->addFlash('success', 'L\'email a bien été renvoyé');
        } else {
            $this->addFlash('danger', 'Une erreur est survenue lors de l\'envoi de l\'email');
        }

        return $this->redirectToRoute('app_etudiant_index', [], Response::HTTP_SEE_OTHER);
    }
}
