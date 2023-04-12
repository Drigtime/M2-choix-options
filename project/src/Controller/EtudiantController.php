<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\User;
use App\Form\EtudiantType;
use App\Form\UserImportType;
use App\Repository\EtudiantRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\MailerService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[Route('/admin/etudiant')]
class EtudiantController extends AbstractController
{
    public function __construct(private readonly MailerService $mailerService)
    {
    }

    #[Route('/', name: 'app_etudiant_index', methods: ['GET', 'POST'])]
    public function index(Request $request, EtudiantRepository $etudiantRepository, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $form = $this->createForm(UserImportType::class);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('fileImport');



            if (!$file instanceof UploadedFile) {
                throw new FileException('Pas de fichier importé');
            }

            // Check if the file is a CSV or XLS file
            $mimeTypeGuesser = \Symfony\Component\Mime\MimeTypes::getDefault();
            $mimeType = $mimeTypeGuesser->guessMimeType($file->getPathname());
            if (!in_array($mimeType, ['text/csv', 'application/vnd.ms-excel'])) {
                throw new FileException('Invalid file format. Only CSV and XLS files are allowed.');
            }

            $fileImport = $file->getData();
            $fileImport = fopen($fileImport, 'r');

            if ($fileImport) {
                while (($data = fgetcsv($fileImport)) !== false) {
                    if ($data[0] != 'nom') {
                        $etudiant = new Etudiant();
                        $etudiant->setNom($data[0]);
                        $etudiant->setPrenom($data[1]);
                        $etudiant->setMail($data[2]);
                        $etudiantRepository->save($etudiant, true);

                        $user = new User();
                        $user->setEmail($data[2]);
                        $user->setPassword('default');
                        $userRepository->save($user, true);
                        // generate a signed url and email it to the user
                        $this->mailerService->sendEmailConfirmation('app_verify_email', $user);
                    }
                }
            }
            return $this->redirectToRoute('app_etudiant_index', [], Response::HTTP_SEE_OTHER);
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
    public function new(Request $request, EtudiantRepository $etudiantRepository): Response
    {
        $etudiant = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etudiantRepository->save($etudiant, true);

            return $this->redirectToRoute('app_etudiant_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('etudiant/new.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form,
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

        return $this->renderForm('etudiant/edit.html.twig', [
            'etudiant' => $etudiant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_etudiant_delete', methods: ['POST'])]
    public function delete(Request $request, Etudiant $etudiant, EtudiantRepository $etudiantRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $etudiant->getId(), $request->request->get('_token'))) {
            $etudiantRepository->remove($etudiant, true);
        }

        return $this->redirectToRoute('app_etudiant_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/delete', name: 'app_etudiant_suppr', methods: ['GET','POST'])]
    public function suppr(Request $request, Etudiant $etudiant, EtudiantRepository $etudiantRepository): Response
    {
        
        $etudiantRepository->remove($etudiant, true);
        

        return $this->redirectToRoute('app_etudiant_index', [], Response::HTTP_SEE_OTHER);
    }


 
}
