<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Form\MoveEtudiantType;
use App\Repository\AnneeFormationRepository;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PassageAnneeController extends AbstractController
{
    #[Route('/passage_annee', name: 'app_passage_annee')]
    public function index(AnneeFormationRepository $anneeFormationRepository): Response
    {
        $anneeFormation = $anneeFormationRepository->findAll();

        return $this->render('passage_annee/index.html.twig', [
            'anneeFormation' => $anneeFormation,
        ]);
    }

    #[Route('/passage_annee/move_student/{id}', name: 'app_passage_annee_move_student')]
    public function moveStudent(Etudiant $etudiant, Request $request, EtudiantRepository $etudiantRepository, AnneeFormationRepository $anneeFormationRepository): Response
    {
        $form = $this->createForm(MoveEtudiantType::class, $etudiant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etudiantRepository->save($etudiant, true);

            $anneeFormation = $anneeFormationRepository->findAll();
            return $this->render('passage_annee/_list_annee.html.twig', [
                'anneeFormation' => $anneeFormation,
            ]);
        }

        return $this->render('passage_annee/_move_student.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // same but it is possible to send an array of students, the form select a new parcours for all students
    #[Route('/passage_annee/move_students', name: 'app_passage_annee_move_students')]
    public function moveStudents(Request $request, EtudiantRepository $etudiantRepository, AnneeFormationRepository $anneeFormationRepository, EntityManagerInterface $entityManager): Response
    {
        $students = $request->request->all()['students'];
        $etudiants = [];
        foreach ($students as $student) {
            $etudiants[] = $etudiantRepository->find($student);
        }

        $form = $this->createForm(MoveEtudiantType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $parcours = $form->get('parcours')->getData();

            foreach ($etudiants as $etudiant) {
                $etudiant->setParcours($parcours);
                $etudiantRepository->save($etudiant);
            }
            $entityManager->flush();

            $anneeFormation = $anneeFormationRepository->findAll();
            return $this->render('passage_annee/_list_annee.html.twig', [
                'anneeFormation' => $anneeFormation,
            ]);
        }

        return $this->render('passage_annee/_move_student.html.twig', [
            'form' => $form->createView(),
            'students' => $students,
        ]);
    }
}
