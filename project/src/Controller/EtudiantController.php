<?php

namespace App\Controller;

use App\Entity\Main\Etudiant;
use App\Entity\User\User;
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
            //gestion csv
            if (!in_array($mimeType, ['text/csv'])) {
                $fileImport = $file->getData();
                $fileImport = fopen($fileImport, 'r');

                if ($fileImport) {
                    while (($data = fgetcsv($fileImport)) !== false) {
                        if ($data[0] != 'nom') {
                            $user_exist = $userRepository->findOneByMail($data[2]);

                            if($user_exist == null){
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
                }
            }
            //gestion xls
            elseif (!in_array($mimeType, ['application/vnd.ms-excel'])) {
                
            } else {
                throw new FileException('Invalid file format. Only CSV and XLS files are allowed.');
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
    public function new(Request $request, EtudiantRepository $etudiantRepository, UserRepository $userRepository): Response
    {
        $etudiant = new Etudiant();
        $user = new User();

        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        

        if ($form->isSubmitted() && $form->isValid()) {

            $user_exist = $userRepository->findOneByMail($etudiant->getMail());

            if($user_exist == null){

            $etudiantRepository->save($etudiant, true);
            $user = new User();
            $user->setEmail($etudiant->getMail());
            $user->setPassword('default');
            $userRepository->save($user, true);
            // generate a signed url and email it to the user
            $this->mailerService->sendEmailConfirmation('app_verify_email', $user);
        


            return $this->redirectToRoute('app_etudiant_index', [], Response::HTTP_SEE_OTHER);
            }
            else{
                dump('email deja utilisé');
            }
        }

        return $this->render('etudiant/new.html.twig', [
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

        return $this->render('etudiant/edit.html.twig', [
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

    #[Route('/{id}/delete', name: 'app_etudiant_suppr', methods: ['GET', 'POST'])]
    public function suppr(Request $request, Etudiant $etudiant, EtudiantRepository $etudiantRepository): Response
    {

        $etudiantRepository->remove($etudiant, true);


        return $this->redirectToRoute('app_etudiant_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{mail}/renvoie', name: 'app_etudiant_renvoyer', methods: ['GET', 'POST'])]
    public function renvoie(Request $request,$mail, UserRepository $userRepository): Response
    {


        $user = $userRepository->findOneByEmail($mail);

        if($user != null){
            $this->mailerService->sendEmailConfirmation('app_verify_email', $user);
        }

        return $this->redirectToRoute('app_etudiant_index', [], Response::HTTP_SEE_OTHER);
    }
}
