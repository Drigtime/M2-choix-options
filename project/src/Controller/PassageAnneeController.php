<?php

namespace App\Controller;

use App\Entity\Main\Etudiant;
use App\Entity\Main\EtudiantUE;
use App\Entity\Main\Parcours;
use App\Form\MoveEtudiantType;
use App\Form\PassageAnnee\Step_1\AnneeFormationType as Step1AnneeFormationType;
use App\Form\PassageAnnee\Step_2\AnneeFormationType as Step2AnneeFormationType;
use App\Repository\AnneeFormationRepository;
use App\Repository\EtudiantRepository;
use App\Repository\ParcoursRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
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

    #[Route('/passage_annee/workflow', name: 'app_passage_annee_worflow_new')]
    public function new(Session $session): Response
    {
        $session->remove('form_step_M1_1_data');
        $session->remove('form_step_M1_1_redoublants');
        $session->remove('form_step_M1_2_data');
        $session->remove('form_step_M2_1_data');
        $session->remove('form_step_M2_1_redoublants');
        $session->remove('form_step_M2_2_data');

        return $this->redirectToRoute('app_passage_annee_worflow_step_1', ['anneeFormation' => 'M2']);
    }

    // $anneeFormation is a string like "M2" or "M1"
    #[Route('/passage_annee/workflow/step/{anneeFormation}', name: 'app_passage_annee_worflow_step_1', requirements: ['anneeFormation' => 'M1|M2'], methods: ['GET', 'POST'])]
    public function newStep11(Request $request, string $anneeFormation, AnneeFormationRepository $anneeFormationRepository, SessionInterface $session): Response
    {
        $formDatas = $session->get("form_step_{$anneeFormation}_1_data");

        if ($formDatas) {
            $form = $this->createForm(Step1AnneeFormationType::class, $formDatas);
            $redoublants = $session->get("form_step_{$anneeFormation}_1_redoublants");
            if ($redoublants) {
                foreach ($form->get('etudiants') as $etudiant) {
                    if (in_array($etudiant->getData(), $redoublants)) {
                        $etudiant->get('statut')->setData('1');
                    }
                }
            }
        } else {
            $etudiants = [];
            foreach ($anneeFormationRepository->findOneBy(['label' => $anneeFormation])->getParcours() as $parcours) {
                foreach ($parcours->getEtudiants() as $etudiant) {
                    $etudiants[] = $etudiant;
                }
            }

            $form = $this->createForm(Step1AnneeFormationType::class, ['etudiants' => $etudiants]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get all Etudiant with valide = "1" (redouble)
            $redoubles = [];
            $valides = [];
            $stop = [];
            foreach ($form->get('etudiants') as $etudiant) {
                if ($etudiant->get('statut')->getData() === '1') {
                    $redoubles[] = $etudiant->getData();
                } elseif ($etudiant->get('statut')->getData() === '0') {
                    $valides[] = $etudiant->getData();
                } elseif ($etudiant->get('statut')->getData() === '2') {
                    $stop[] = $etudiant->getData();
                }
            }

            $session->set("form_step_{$anneeFormation}_1_data", $form->getData());
            $session->set("form_step_{$anneeFormation}_1_valides", $valides);
            $session->set("form_step_{$anneeFormation}_1_stop", $stop);

            if (!empty($redoubles)) {
                $session->set("form_step_{$anneeFormation}_1_redoublants", $redoubles);
                return $this->redirectToRoute('app_passage_annee_worflow_step_1_2', ['anneeFormation' => $anneeFormation]);
            } else {
                $session->remove("form_step_{$anneeFormation}_1_redoublants");
                $session->remove("form_step_{$anneeFormation}_2_data");
            }

            if ($anneeFormation === 'M1') {
                return $this->redirectToRoute('app_passage_annee_worflow_step_2', ['anneeFormation' => 'M1']);
            }

            return $this->redirectToRoute('app_passage_annee_worflow_step_1', ['anneeFormation' => 'M1']);
        }

        $previousRoute = $session->get('form_step_M2_2_data') ? 'app_passage_annee_worflow_step_1_2' : 'app_passage_annee_worflow_step_1';

        return $this->render('passage_annee/form_step_1_1.html.twig', [
            'form' => $form->createView(),
            'anneeFormation' => $anneeFormation,
            'previousRoute' => $previousRoute,
        ]);
    }

    #[Route('/passage_annee/workflow/step/{anneeFormation}/redoublants', name: 'app_passage_annee_worflow_step_1_2', requirements: ['anneeFormation' => 'M1|M2'], methods: ['GET', 'POST'])]
    public function newStep12(Request $request, string $anneeFormation, EtudiantRepository $etudiantRepository, SessionInterface $session): Response
    {
        $step2FormData = $session->get("form_step_{$anneeFormation}_2_data");

        if ($step2FormData) {
            $etudiantList = [];

            // Récupération des redoublants sélectionnés dans l'étape 1
            $redoublants = $session->get("form_step_{$anneeFormation}_1_redoublants", []);
            $redoublantIds = array_map(function ($redoublant) {
                return $redoublant->getId();
            }, $redoublants);

            // Suppression des étudiants qui ne sont pas redoublants
            $etudiantsStep2 = array_filter($step2FormData['etudiants'], function ($dataEtudiant) use ($redoublantIds) {
                return in_array($dataEtudiant->getId(), $redoublantIds);
            });

            // Ajout des redoublants qui n'étaient pas dans l'étape 2
            $newRedoublants = array_filter($redoublants, function ($redoublant) use ($etudiantsStep2) {
                $etudiantIds = array_map(function ($etudiant) {
                    return $etudiant->getId();
                }, $etudiantsStep2);
                return !in_array($redoublant->getId(), $etudiantIds);
            });

            foreach ($newRedoublants as $redoublant) {
                $etudiant = $etudiantRepository->find($redoublant->getId());
                $etudiantUEs = $etudiant->getEtudiantUEs();
                foreach ($etudiantUEs as $etudiantUE) {
                    $etudiantUE->setAcquis(true);
                }
                $etudiantsStep2[] = $etudiant;
            }

            // Récupération des étudiants de l'étape 2 avec les UE à valider
            foreach ($etudiantsStep2 as $dataEtudiant) {
                $etudiant = $etudiantRepository->find($dataEtudiant->getId());
                $etudiantUEs = $dataEtudiant->getEtudiantUEs();
                foreach ($etudiant->getEtudiantUEs() as $etudiantUE) {
                    foreach ($etudiantUEs as $etudiantUEData) {
                        if ($etudiantUE->getUE()->getId() === $etudiantUEData->getUE()->getId()) {
                            $etudiantUE->setAcquis($etudiantUEData->isAcquis());
                        }
                    }
                }
                $etudiantList[] = $etudiant;
            }

            $session->set("form_step_{$anneeFormation}_2_data", ['etudiants' => $etudiantList]);
            $form = $this->createForm(Step2AnneeFormationType::class, ['etudiants' => $etudiantList]);
        } else {
            $dataEtudiants = $session->get("form_step_{$anneeFormation}_1_redoublants");
            $etudiantList = [];
            foreach ($dataEtudiants as $dataEtudiant) {
                $etudiant = $etudiantRepository->find($dataEtudiant->getId());
                foreach ($etudiant->getEtudiantUEs() as $etudiantUE) {
                    $etudiantUE->setAcquis(true);
                }
                $etudiantList[] = $etudiant;
            }
            $form = $this->createForm(Step2AnneeFormationType::class, ['etudiants' => $etudiantList]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $session->set("form_step_{$anneeFormation}_2_data", $form->getData());

            if ($anneeFormation === 'M1') {
                return $this->redirectToRoute('app_passage_annee_worflow_step_2', ['anneeFormation' => 'M1']);
            }

            return $this->redirectToRoute('app_passage_annee_worflow_step_1', ['anneeFormation' => 'M1']);
        }

        return $this->render('passage_annee/form_step_1_2.html.twig', [
            'form' => $form,
            'anneeFormation' => $anneeFormation,
        ]);
    }

    #[Route('/passage_annee/workflow/step/{anneeFormation}/parcours', name: 'app_passage_annee_worflow_step_2')]
    public function newStep2(Request $request, string $anneeFormation, SessionInterface $session, ParcoursRepository $parcoursRepository): Response
    {
        $queryBuilder = $parcoursRepository->createQueryBuilder('p')
            ->join('p.anneeFormation', 'af')
            ->where('af.label = :anneeFormation')
            ->setParameter('anneeFormation', 'M2');

        $M2Parcours = $queryBuilder->getQuery()->getResult();

        $etudiants = $session->get("form_step_{$anneeFormation}_1_valides", []);
        $previousRoute = $session->get("form_step_{$anneeFormation}_1_redoublants", []) ? 'app_passage_annee_worflow_step_1_2' : 'app_passage_annee_worflow_step_1';


        // TODO créer un formulaire pour cette étape, ou gérer les résultat de la requête

        return $this->render('passage_annee/form_step_2.html.twig', [
            'anneeFormation' => $anneeFormation,
            'parcours' => $M2Parcours,
            'etudiants' => $etudiants,
            'previousRoute' => $previousRoute,
        ]);
    }

    #[Route('/passage_annee/workflow/submit', name: 'app_passage_annee_worflow_submit')]
    public function savePassageAnnee(Session $session, EtudiantRepository $etudiantRepository): Response
    {
        $formStep1M2Data = $session->get('form_step_M2_1_data');
        $formStep2M2Data = $session->get('form_step_M2_2_data');
        $formStep1M1Data = $session->get('form_step_M1_1_data');
        $formStep2M1Data = $session->get('form_step_M1_2_data');

        if ($formStep1M2Data) {
            foreach ($formStep1M2Data['etudiants'] as $etudiant) {
                if ($etudiant->get('statut')->getData() === '0' || $etudiant->get('statut')->getData() === '2') {
                    $etudiantRepository->remove($etudiant->getData());
                }
            }
        }

        if ($formStep2M2Data) {
            // TODO Gérer les redoublants de M2
        }

        if ($formStep1M1Data) {
            // TODO bouger les étudiants de M1 vers M2
        }

        if ($formStep2M1Data) {
            // TODO Gérer les redoublants de M1
        }

        $session->remove('form_step_M1_1_data');
        $session->remove('form_step_M1_1_redoublants');
        $session->remove('form_step_M1_2_data');
        $session->remove('form_step_M2_1_data');
        $session->remove('form_step_M2_1_redoublants');
        $session->remove('form_step_M2_2_data');

        $this->addFlash('success', 'Les étudiants ont bien été déplacés');

        return $this->redirectToRoute('app_passage_annee');
    }

    #[Route('/passage_annee/move_student/{id}', name: 'app_passage_annee_move_student')]
    public function moveStudent(Etudiant $etudiant, Request $request, EtudiantRepository $etudiantRepository, AnneeFormationRepository $anneeFormationRepository): Response
    {
        $form = $this->createForm(MoveEtudiantType::class, $etudiant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Parcours $parcours */
            $parcours = $form->get('parcours')->getData();
            $etudiant = $this->moveEtudiantToParcours($etudiant, $parcours);

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

    /**
     * @param mixed $etudiant
     * @param Parcours $parcours
     * @return Etudiant
     */
    public function moveEtudiantToParcours(Etudiant $etudiant, Parcours $parcours): Etudiant
    {
        foreach ($etudiant->getResponseCampagnes() as $responseCampagne) {
            $etudiant->removeResponseCampagne($responseCampagne);
        }

        // remove all EtudiantUE
        $etudiant->getEtudiantUEs()->clear();

        $etudiant->setParcours($parcours);

        // add new EtudiantUE
        foreach ($parcours->getBlocUEs() as $blocUE) {
            $mandatoryUEs = $blocUE->getBlocUeUes()->filter(fn($blocUeUe) => !$blocUeUe->isOptional());
            $optionalUEs = $blocUE->getBlocUeUes()->filter(fn($blocUeUe) => $blocUeUe->isOptional());
            foreach ($mandatoryUEs as $blocUeUe) {
                $etudiant->addEtudiantUE(new EtudiantUE($etudiant, $blocUeUe->getUe()));
            }

            // Assigner des UE optionnelles par défaut
            $nbUEsOptional = $blocUE->getNbUEsOptional();
            if ($nbUEsOptional > 0) {
                $optionalUEs = $optionalUEs->slice(0, $nbUEsOptional);
                foreach ($optionalUEs as $blocUeUe) {
                    $etudiant->addEtudiantUE(new EtudiantUE($etudiant, $blocUeUe->getUe()));
                }
            }
        }

        return $etudiant;
    }

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
                $etudiant = $this->moveEtudiantToParcours($etudiant, $parcours);
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
