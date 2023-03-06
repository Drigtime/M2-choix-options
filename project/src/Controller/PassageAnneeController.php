<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\EtudiantUE;
use App\Entity\Parcours;
use App\Form\MoveEtudiantType;
use App\Form\PassageAnnee\Step_1\AnneeFormationType as Step1AnneeFormationType;
use App\Form\PassageAnnee\Step_2\AnneeFormationType as Step2AnneeFormationType;
use App\Repository\AnneeFormationRepository;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

    #[Route('/passage_annee/workflow/new', name: 'app_passage_annee_worflow_new')]
    public function new(Request $request, AnneeFormationRepository $anneeFormationRepository, SessionInterface $session): Response
    {
        return $this->redirectToRoute('app_passage_annee_worflow_step_1', ['anneeFormation' => 'M2']);
    }

    // $anneeFormation is a string like "M2" or "M1"
    #[Route('/passage_annee/workflow/step/{anneeFormation}', name: 'app_passage_annee_worflow_step_1', requirements: ['anneeFormation' => 'M1|M2'], methods: ['GET', 'POST'])]
    public function newStep11(Request $request, string $anneeFormation, AnneeFormationRepository $anneeFormationRepository, SessionInterface $session): Response
    {
        $etudiants = [];
        foreach ($anneeFormationRepository->findOneBy(['label' => $anneeFormation])->getParcours() as $parcours) {
            foreach ($parcours->getEtudiants() as $etudiant) {
                $etudiants[] = $etudiant;
            }
        }

        $form = $this->createForm(Step1AnneeFormationType::class, ['etudiants' => $etudiants]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Get all Etudiant with valide = "1" (redouble)
            $redoubles = [];
            foreach ($form->get('etudiants') as $etudiant) {
                if ($etudiant->get('statut')->getData() === '1') {
                    $redoubles[] = $etudiant->getData();
                }
            }

            $session->set("form_step_{$anneeFormation}_1_data", $data);
            $session->set("form_step_{$anneeFormation}_1_redoublants", $redoubles);

            if (!empty($redoubles)) {
                return $this->redirectToRoute('app_passage_annee_worflow_step_1_2', ['anneeFormation' => $anneeFormation]);
            }

            if ($anneeFormation === 'M1') {
                $this->addFlash('success', 'Les étudiants ont bien été déplacés');
                return $this->redirectToRoute('app_passage_annee');
            }

            return $this->redirectToRoute('app_passage_annee_worflow_step_1', ['anneeFormation' => 'M1']);
        }

        return $this->render('passage_annee/form_step_1_1.html.twig', [
            'form' => $form->createView(),
            'anneeFormation' => $anneeFormation,
        ]);
    }

    #[Route('/passage_annee/workflow/step/{anneeFormation}/redoublants', name: 'app_passage_annee_worflow_step_1_2', requirements: ['anneeFormation' => 'M1|M2'], methods: ['GET', 'POST'])]
    public function newStep12(Request $request, string $anneeFormation, EtudiantRepository $etudiantRepository, SessionInterface $session): Response
    {
        $dataEtudiants = $session->get("form_step_{$anneeFormation}_1_redoublants");
        $etudiants = [];
        foreach ($dataEtudiants as $dataEtudiant) {
            $etudiants[] = $etudiantRepository->find($dataEtudiant->getId());
        }

        $form = $this->createForm(Step2AnneeFormationType::class, ['etudiants' => $etudiants]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $session->set("form_step_{$anneeFormation}_2_data", $data);
            $session->set("form_step_{$anneeFormation}_2_redoublants", $data['etudiants']);

            if ($anneeFormation === 'M1') {
                $this->addFlash('success', 'Les étudiants ont bien été déplacés');
                return $this->redirectToRoute('app_passage_annee');
            }

            return $this->redirectToRoute('app_passage_annee_worflow_step_1', ['anneeFormation' => 'M1']);
        }

        return $this->render('passage_annee/form_step_1_2.html.twig', [
            'form' => $form->createView(),
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
            /** @var Parcours $parcours */
            $parcours = $form->get('parcours')->getData();

            foreach ($etudiants as $etudiant) {
                // remove all EtudiantUE
                foreach ($etudiant->getEtudiantUEs() as $etudiantUE) {
                    $etudiant->removeEtudiantUE($etudiantUE);
                }
                $etudiant->setParcours($parcours);
                foreach ($parcours->getBlocUEs() as $blocUE) {
                    foreach ($blocUE->getBlocUeUes() as $blocUeUe) {
                        $etudiant->addEtudiantUE(new EtudiantUE($etudiant, $blocUeUe->getUe()));
                    }
                }
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
