<?php

namespace App\Controller;

use App\Entity\Main\AnneeFormation;
use App\Entity\Main\Etudiant;
use App\Entity\Main\EtudiantUE;
use App\Entity\Main\Groupe;
use App\Entity\Main\Parcours;
use App\Entity\Main\UE;
use App\Form\MoveEtudiantType;
use App\Form\PassageAnnee\Step_1\AnneeFormationType as Step1AnneeFormationType;
use App\Form\PassageAnnee\Step_2\AnneeFormationType as Step2AnneeFormationType;
use App\Repository\AnneeFormationRepository;
use App\Repository\EtudiantRepository;
use App\Repository\GroupeRepository;
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

    private AnneeFormationRepository $anneeFormationRepository;
    private ParcoursRepository $parcoursRepository;
    private EtudiantRepository $etudiantRepository;
    private GroupeRepository $groupeRepository;

    public function __construct(
        AnneeFormationRepository $anneeFormationRepository,
        ParcoursRepository       $parcoursRepository,
        EtudiantRepository       $etudiantRepository,
        GroupeRepository         $groupeRepository)
    {
        $this->anneeFormationRepository = $anneeFormationRepository;
        $this->parcoursRepository = $parcoursRepository;
        $this->etudiantRepository = $etudiantRepository;
        $this->groupeRepository = $groupeRepository;
    }

    #[Route('/admin', name: 'app_passage_annee')]
    public function index(): Response
    {
        $anneeFormation = $this->anneeFormationRepository->findAll();

        return $this->render('passage_annee/index.html.twig', [
            'anneeFormation' => $anneeFormation,
        ]);
    }

    #[Route('/passage_annee/workflow', name: 'app_passage_annee_workflow_new')]
    public function new(Session $session): Response
    {
        $session->remove('passage_annee_form');
        $session->set('passage_annee_form', [
            "form_step_M2_1_data" => [],
            "form_step_M1_1_data" => [],
            "form_step_M1_1_redoublants" => [],
            "form_step_M2_1_redoublants" => [],
            'form_step_M2_2_data' => [],
            'form_step_M1_2_data' => [],
            'form_step_M1_3_data' => [],
        ]);

        return $this->redirectToRoute('app_passage_annee_workflow_step_1');
    }

    // $anneeFormation is a string like "M2" or "M1"
    #[Route('/passage_annee/workflow/step_1', name: 'app_passage_annee_workflow_step_1', methods: ['GET', 'POST'])]
    #[Route('/passage_annee/workflow/step_2', name: 'app_passage_annee_workflow_step_2', methods: ['GET', 'POST'])]
    public function newStep1(Request $request, SessionInterface $session): Response
    {
        $routeName = $request->attributes->get('_route');
        $anneeFormation = $routeName === 'app_passage_annee_workflow_step_1' ? AnneeFormation::M2 : AnneeFormation::M1;

        $passageAnneForm = $session->get('passage_annee_form');
        $formData = $passageAnneForm["form_step_{$anneeFormation}_1_data"] ?? [];

        if ($formData) {
            $students = array_map(function ($studentData) {
                $student = $this->etudiantRepository->find($studentData['id']);
                $student->setStatut($studentData['statut']);
                return $student;
            }, $formData);

            $form = $this->createForm(Step1AnneeFormationType::class, ['etudiants' => $students]);

            $redoublants = $passageAnneForm["form_step_{$anneeFormation}_1_redoublants"] ?? [];
            if ($redoublants) {
                foreach ($form->get('etudiants') as $student) {
                    if (in_array($student->getData(), $redoublants)) {
                        $student->getData()->setStatut(1);
                    }
                }
            }
        } else {
            $students = [];
            foreach ($this->anneeFormationRepository->findOneBy(['label' => $anneeFormation])->getParcours() as $parcours) {
                foreach ($parcours->getEtudiants() as $student) {
                    $students[] = $student;
                }
            }

            $form = $this->createForm(Step1AnneeFormationType::class, ['etudiants' => $students]);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = [];
            $validStudents = [];
            $stoppedStudents = [];
            $retainedStudents = [];

            foreach ($form->get('etudiants') as $student) {
                $formData[] = [
                    'id' => $student->getData()->getId(),
                    'statut' => $student->getData()->getStatut()
                ];

                switch ($student->getData()->getStatut()) {
                    case 0:
                        $validStudents[] = ['id' => $student->getData()->getId()];
                        break;
                    case 1:
                        $retainedStudents[] = ['id' => $student->getData()->getId()];
                        break;
                    case 2:
                        $stoppedStudents[] = ['id' => $student->getData()->getId()];
                        break;
                }
            }

            $passageAnneForm["form_step_{$anneeFormation}_1_data"] = $formData;
            $passageAnneForm["form_step_{$anneeFormation}_1_valides"] = $validStudents;
            $passageAnneForm["form_step_{$anneeFormation}_1_stops"] = $stoppedStudents;
            $passageAnneForm["form_step_{$anneeFormation}_1_redoublants"] = $retainedStudents;

            $session->set('passage_annee_form', $passageAnneForm);

            $redoubles = $passageAnneForm["form_step_{$anneeFormation}_1_redoublants"] ?? [];
            if ($redoubles) {
                $routeName = $anneeFormation === AnneeFormation::M2 ? 'app_passage_annee_workflow_step_1_2' : 'app_passage_annee_workflow_step_2_2';
                return $this->redirectToRoute($routeName);
            } else {
                $passageAnneForm["form_step_{$anneeFormation}_2_data"] = [];
                $session->set('passage_annee_form', $passageAnneForm);
            }

            $routeName = $anneeFormation === AnneeFormation::M1 ? 'app_passage_annee_workflow_step_3' : 'app_passage_annee_workflow_step_2';
            return $this->redirectToRoute($routeName);
        }

        $previousRoute = $passageAnneForm['form_step_M2_2_data'] ? 'app_passage_annee_workflow_step_1_2' : 'app_passage_annee_workflow_step_1';

        return $this->render('passage_annee/form_step_1_1.html.twig', [
            'form' => $form->createView(),
            'anneeFormation' => $anneeFormation,
            'previousRoute' => $previousRoute,
        ]);
    }

    #[Route('/passage_annee/workflow/step_1_1', name: 'app_passage_annee_workflow_step_1_2', methods: ['GET', 'POST'])]
    #[Route('/passage_annee/workflow/step_2_1', name: 'app_passage_annee_workflow_step_2_2', methods: ['GET', 'POST'])]
    public function newStep11(Request $request, SessionInterface $session): Response
    {
        $anneeFormation = $request->attributes->get('_route') === 'app_passage_annee_workflow_step_1_2' ? AnneeFormation::M2 : AnneeFormation::M1;

        $passageAnneForm = $session->get('passage_annee_form');
        $passageAnneFormIndex = "form_step_{$anneeFormation}_2_data";
        $step2FormData = $passageAnneForm[$passageAnneFormIndex] ?? null;

        $redoublants = $passageAnneForm["form_step_{$anneeFormation}_1_redoublants"] ?? [];
        if ($step2FormData) {
            $redoublantIds = array_column($redoublants, 'id');

            $etudiantsStep2 = array_filter($step2FormData, function ($dataEtudiant) use ($redoublantIds) {
                return in_array($dataEtudiant['id'], $redoublantIds);
            });

            $newRedoublants = array_filter($redoublants, function ($redoublant) use ($etudiantsStep2) {
                $etudiantIds = array_column($etudiantsStep2, 'id');
                return !in_array($redoublant['id'], $etudiantIds);
            });

            foreach ($newRedoublants as $redoublant) {
                $etudiant = $this->etudiantRepository->find($redoublant['id']);
                $etudiantUEs = $etudiant->getEtudiantUEs();
                foreach ($etudiantUEs as $etudiantUE) {
                    $etudiantUE->setAcquis(true);
                }
                $etudiantsStep2[] = [
                    'id' => $etudiant->getId(),
                    'etudiantUEs' => array_map(function ($etudiantUE) {
                        return [
                            'ueId' => $etudiantUE->getUE()->getId(),
                            'acquis' => $etudiantUE->isAcquis()
                        ];
                    }, $etudiantUEs->toArray())
                ];
            }

            $etudiantIds = array_column($etudiantsStep2, 'id');
            $etudiantList = $this->etudiantRepository->findBy(['id' => $etudiantIds]);
            foreach ($etudiantsStep2 as $dataEtudiant) {
                $etudiant = array_filter($etudiantList, function ($etudiant) use ($dataEtudiant) {
                    return $etudiant->getId() === $dataEtudiant['id'];
                });
                $etudiant = array_shift($etudiant);
                $etudiantUEs = $dataEtudiant['etudiantUEs'];
                foreach ($etudiant->getEtudiantUEs() as $etudiantUE) {
                    foreach ($etudiantUEs as $etudiantUEData) {
                        if ($etudiantUE->getUE()->getId() === $etudiantUEData['ueId']) {
                            $etudiantUE->setAcquis($etudiantUEData['acquis']);
                        }
                    }
                }
            }
        } else {
            $etudiantIds = array_column($redoublants, 'id');
            $etudiantList = $this->etudiantRepository->findBy(['id' => $etudiantIds]);
            foreach ($etudiantList as $etudiant) {
                foreach ($etudiant->getEtudiantUEs() as $etudiantUE) {
                    $etudiantUE->setAcquis(true);
                }
            }
        }
        $form = $this->createForm(Step2AnneeFormationType::class, ['etudiants' => $etudiantList]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etudiants = $form->getData()['etudiants'];

            $passageAnneForm[$passageAnneFormIndex] = array_map(function ($etudiant) {
                return [
                    'id' => $etudiant->getId(),
                    'etudiantUEs' => array_map(function ($etudiantUE) {
                        return [
                            'id' => $etudiantUE->getId(),
                            'ueId' => $etudiantUE->getUE()->getId(),
                            'acquis' => $etudiantUE->isAcquis(),
                        ];
                    }, $etudiant->getEtudiantUEs()->toArray())
                ];
            }, $etudiants);

            $session->set('passage_annee_form', $passageAnneForm);

            if ($anneeFormation === AnneeFormation::M2) {
                return $this->redirectToRoute('app_passage_annee_workflow_step_2');
            }

            return $this->redirectToRoute('app_passage_annee_workflow_step_3');
        }

        return $this->render('passage_annee/form_step_1_2.html.twig', [
            'form' => $form->createView(),
            'anneeFormation' => $anneeFormation,
        ]);

    }

    #[Route('/passage_annee/workflow/step_3', name: 'app_passage_annee_workflow_step_3', methods: ['GET', 'POST'])]
    public function newStep3(Request $request, SessionInterface $session): Response
    {
        $passageAnneForm = $session->get('passage_annee_form');
        $parcoursQueryBuilder = $this->parcoursRepository->createQueryBuilder('p')
            ->join('p.anneeFormation', 'af')
            ->where('af.label = :anneeFormation')
            ->setParameter('anneeFormation', AnneeFormation::M2);
        $parcours = $parcoursQueryBuilder->getQuery()->getResult();

        $etudiants = array_map(function ($dataEtudiant) {
            return $this->etudiantRepository->find($dataEtudiant['id']);
        }, array_filter($passageAnneForm["form_step_M1_1_data"] ?? [], function ($etudiant) {
            return $etudiant['statut'] === 0;
        }));

        // handle post request
        if ($request->isMethod('POST')) {
            $formData = [];
            foreach ($request->request->all()['parcours'] as $parcoursId => $etudiantsIds) {
                $etudiants = [];
                foreach ($etudiantsIds as $etudiantId) {
                    $etudiant = $this->etudiantRepository->find($etudiantId);
                    $etudiants[] = [
                        'id' => $etudiantId,
                        'text' => $etudiant->__toString()
                    ];
                }
                $formData[$parcoursId] = $etudiants;
            }
            $passageAnneForm["form_step_M1_3_data"] = $formData;
            $session->set('passage_annee_form', $passageAnneForm);
            return $this->redirectToRoute('app_passage_annee_workflow_submit');
        }

        $formData = $passageAnneForm["form_step_M1_3_data"] ?? [];
        $previousRoute = $passageAnneForm["form_step_M1_1_redoublants"] ? 'app_passage_annee_workflow_step_2_2' : 'app_passage_annee_workflow_step_2';

        return $this->render('passage_annee/form_step_3.html.twig', [
            'etudiants' => $etudiants,
            'parcours' => $parcours,
            'previousRoute' => $previousRoute,
            'formData' => array_map(function ($etudiants) {
                return array_map(function ($etudiant) {
                    return [
                        'value' => $etudiant['id'],
                        'text' => $etudiant['text'],
                    ];
                }, $etudiants);
            }, $formData),
        ]);
    }

    #[Route('/passage_annee/workflow/submit', name: 'app_passage_annee_workflow_submit')]
    public function savePassageAnnee(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $passageAnneForm = $session->get('passage_annee_form');

        $this->removeStudents(array_merge($passageAnneForm['form_step_M2_1_valides'], $passageAnneForm['form_step_M2_1_stops']));
        $this->updateStudentUEStatus($passageAnneForm['form_step_M2_1_redoublants'], $passageAnneForm['form_step_M2_2_data']);

        $this->removeStudents($passageAnneForm['form_step_M1_1_stops']);
        $this->updateStudentUEStatus($passageAnneForm['form_step_M1_1_redoublants'], $passageAnneForm['form_step_M1_2_data']);

        // Déplacer les étudiants de M1 vers leur parcours de M2 correspondant
        foreach ($passageAnneForm['form_step_M1_3_data'] as $parcoursId => $etudiants) {
            $studentIds = array_column($etudiants, 'id');
            $students = $this->etudiantRepository->findBy(['id' => $studentIds]);
            foreach ($students as $student) {
                $this->etudiantRepository->save($this->moveEtudiantToParcours($student, $this->parcoursRepository->find($parcoursId)));
            }
        }

        $entityManager->flush();

        $session->remove('passage_annee_form');

        $this->addFlash('success', 'Les étudiants ont bien été déplacés');

        return $this->redirectToRoute('app_passage_annee');
    }

    private function removeStudents(array $students): void
    {
        $studentIds = array_column($students, 'id');
        $students = $this->etudiantRepository->findBy(['id' => $studentIds]);

        foreach ($students as $etudiant) {
            $this->etudiantRepository->remove($etudiant);
        }
    }

    private function updateStudentUEStatus(array $redoublants, array $stepData): void
    {
        $studentIds = array_column($redoublants, 'id');
        $students = $this->etudiantRepository->findBy(['id' => $studentIds]);

        foreach ($students as $student) {
            $studentId = $student->getId();
            $studentData = array_filter($stepData, function ($data) use ($studentId) {
                return $data['id'] === $studentId;
            });

            if (!$studentData) {
                continue;
            }

            $etudiantUEs = $studentData[0]['etudiantUEs'];
            foreach ($student->getEtudiantUEs() as $etudiantUE) {
                $ueId = $etudiantUE->getUE()->getId();
                foreach ($etudiantUEs as $etudiantUEData) {
                    if (array_key_exists('ueId', $etudiantUEData) && $etudiantUEData['ueId'] === $ueId) {
                        $etudiantUE->setAcquis($etudiantUEData['acquis']);
                    }
                }
            }

            $this->etudiantRepository->save($student);
        }
    }

    #[Route('/passage_annee/move_student/{id}', name: 'app_passage_annee_move_student')]
    public function moveStudent(Etudiant $etudiant, Request $request): Response
    {
        $form = $this->createForm(MoveEtudiantType::class, $etudiant)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->etudiantRepository->save($this->moveEtudiantToParcours($etudiant, $form->get('parcours')->getData()), true);

            return $this->render('passage_annee/_list_annee.html.twig', [
                'anneeFormation' => $this->anneeFormationRepository->findAll(),
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
        $etudiant->getResponseCampagnes()->clear();
        $etudiant->getGroupes()->clear();
        $etudiant->getEtudiantUEs()->clear();
        $etudiant->setParcours($parcours);

        foreach ($parcours->getBlocUEs() as $blocUE) {
            $mandatoryUEs = $blocUE->getMandatoryUEs();
            $optionalUEs = $blocUE->getOptionalUEs()->slice(0, $blocUE->getNbUEsOptional());

            $ues = array_map(fn($blocUeUe) => $blocUeUe->getUe(), array_merge($mandatoryUEs->toArray(), $optionalUEs));
            foreach ($ues as $ue) {
                $this->addStudentToUeGroup($ue, $etudiant);
                $etudiant->addEtudiantUE(new EtudiantUE($etudiant, $ue));
            }
        }

        return $etudiant;
    }

    #[Route('/passage_annee/move_students', name: 'app_passage_annee_move_students')]
    public function moveStudents(Request $request, EntityManagerInterface $entityManager): Response
    {
        $students = $request->request->all()['students'];
        $etudiants = $this->etudiantRepository->findBy(['id' => $students]);

        $form = $this->createForm(MoveEtudiantType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Parcours $parcours */
            $parcours = $form->get('parcours')->getData();

            foreach ($etudiants as $etudiant) {
                $etudiant = $this->moveEtudiantToParcours($etudiant, $parcours);
                $this->etudiantRepository->save($etudiant);
            }

            $entityManager->flush();
            $anneeFormation = $this->anneeFormationRepository->findAll();

            return $this->render('passage_annee/_list_annee.html.twig', [
                'anneeFormation' => $anneeFormation,
            ]);
        }

        return $this->render('passage_annee/_move_student.html.twig', [
            'form' => $form->createView(),
            'students' => $students,
        ]);
    }

    /**
     * @param $blocUeUe
     * @param mixed $etudiant
     * @return void
     */
    public function addStudentToUeGroup(UE $ue, Etudiant $etudiant): void
    {
        $groups = $ue->getGroupes();
        $group = null;

        foreach ($groups as $g) {
            if ($g->getEtudiants()->count() < $ue->getEffectif()) {
                $group = $g;
                break;
            }
        }

        if (!$group) {
            $group = new Groupe();
            $group->setUe($ue);
            $group->setLabel($ue->getLabel() . "-Groupe-" . ($ue->getGroupes()->count() + 1));
        }

        $group->addEtudiant($etudiant);
        $this->groupeRepository->save($group);
    }
}
