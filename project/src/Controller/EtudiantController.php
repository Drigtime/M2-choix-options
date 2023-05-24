<?php

namespace App\Controller;

use App\Entity\Main\AnneeFormation;
use App\Entity\Main\BlocUE;
use App\Entity\Main\BlocUeUe;
use App\Entity\Main\Etudiant;
use App\Entity\Main\EtudiantUE;
use App\Entity\Main\Groupe;
use App\Entity\Main\Parcours;
use App\Entity\Main\ResponseCampagne;
use App\Entity\Main\UE;
use App\Entity\User\ResetPasswordToken;
use App\Entity\User\User;
use App\Form\EtudiantType;
use App\Form\UserImportType;
use App\Repository\AnneeFormationRepository;
use App\Repository\ChoixRepository;
use App\Repository\EtudiantRepository;
use App\Repository\PasswordTokenRepository;
use App\Repository\UERepository;
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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;
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
    public function __construct(private readonly MailerService   $mailerService,
                                private readonly ChoixRepository $choixRepository,
                                private readonly UERepository    $UERepository)
    {
    }

    #[Route('/', name: 'app_etudiant_index', methods: ['GET', 'POST'])]
    public function index(Request                     $request,
                          PaginatorInterface          $paginator,
                          EtudiantRepository          $etudiantRepository,
                          UserRepository              $userRepository,
                          PasswordTokenRepository     $passwordTokenRepository,
                          AnneeFormationRepository    $anneeFormationRepository,
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
                        $moveStudentService->moveEtudiantToParcours($etudiant, $parcours, false);
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

        $anneeFormations = $anneeFormationRepository->findAll();

        return $this->render('etudiant/index.html.twig', [
            'etudiants' => $etudiants,
            'anneeFormations' => $anneeFormations,
            'form' => $form->createView()
        ]);
    }

    #[Route('/export/{anneeFormation}', name: 'app_etudiant_export', methods: ['GET', 'POST'])]
    public function export(Request $request, EtudiantRepository $etudiantRepository, AnneeFormation $anneeFormation): Response
    {
        $parcours = $anneeFormation->getParcours();

        // Create new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        // remove default sheet
        $spreadsheet->removeSheetByIndex(0);

        // create tabs for each parcours
        foreach ($parcours as $p) {
            $spreadsheet->createSheet();
            $spreadsheet->setActiveSheetIndex($spreadsheet->getSheetCount() - 1);
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle($p->getLabel());
            $sheet->getTabColor()->setRGB('FCE4D6');

            // En tête 1-4:1 Mettre en gras "Choix d'options \n M2 MIAGE - {nom de parcours} \n 2022-23
            $sheet->mergeCells([1, 1, 4, 1]);
            // dont use \n in setCellValue because it will not be interpreted as a new line
            $sheet->setCellValue([1, 1], 'Choix d\'options ' . PHP_EOL . 'M2 MIAGE - ' . $p->getLabel() . PHP_EOL . $anneeFormation->getLabel());

            $sheet->getStyle([1, 1, 4, 1])->applyFromArray([
                'font' => [
                    'bold' => true,
                    'size' => 16
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ]);
            $sheet->getRowDimension(1)->setRowHeight(98);


            $sheet->getColumnDimensionByColumn(1)->setAutoSize(true); // auto size column

            $rowIndex = 3;
            $sheet->setCellValue([1, $rowIndex], "PARCOURS {$p->getLabel()}");
            $sheet->getStyle([1, $rowIndex])->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FF0000'],
                    'size' => 14
                ]
            ]);

            $rowIndex = 5;
            foreach ($p->getBlocUEs() as $blocUE) {
                $nbUEsOptional = $blocUE->getNbUEsOptional();
                $optionalUEs = $blocUE->getOptionalUEs();
                $optionalUEsCount = $optionalUEs->count();

                if ($nbUEsOptional == 0 or $optionalUEsCount == 0) {
                    continue;
                }

                $etudiants = $p->getEtudiants();
                $etudiantsCount = $etudiants->count();

                $sheet->setCellValue([1, $rowIndex], "Bloc {$blocUE->getCategory()->getLabel()} - {$nbUEsOptional} option" . ($nbUEsOptional > 1 ? 's' : '') . " parmi {$optionalUEsCount}");
                $sheet->getStyle([1, $rowIndex])->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => '8EA9DB']
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_BOTTOM
                    ]
                ]);

                $tempRowIndex = $rowIndex + 2;
                $tempColIndex = 2;
                for ($i = 0; $i < $optionalUEsCount; $i++) {
                    $tempColIndexLetter = Coordinate::stringFromColumnIndex($tempColIndex); // convert column index to letter
                    $sheet->setCellValue([$tempColIndex, $rowIndex], "=SUMIF({$tempColIndexLetter}{$tempRowIndex}:{$tempColIndexLetter}" . ($tempRowIndex + $etudiantsCount - 1) . ",1)");
                    $tempColIndex += 1;
                }
                $sheet->getStyle([2, $rowIndex, 2 + $optionalUEsCount - 1, $rowIndex])->applyFromArray([
                    'font' => [
                        'size' => 11
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'color' => ['rgb' => '8EA9DB']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                $rowIndex += 1;
                $blocUEFirstRow = $rowIndex;
                $sheet->setCellValue([1, $rowIndex], "Classer de 1 à {$optionalUEsCount}");
                $sheet->getStyle([1, $rowIndex])->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 11
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER
                    ]
                ]);

                $tempColIndex = 2;
                for ($i = 0; $i < 2; $i++) {
                    foreach ($optionalUEs as $optionalUE) {
                        /** @var BlocUeUe $optionalUE */
                        $sheet->setCellValue([$tempColIndex, $rowIndex], $optionalUE->getUE()->getLabel());
                        $sheet->getStyle([$tempColIndex, $rowIndex])->applyFromArray([
                            'font' => [
                                'name' => 'Arial',
                                'bold' => true,
                                'size' => 9
                            ],
                            'alignment' => [
                                'horizontal' => Alignment::HORIZONTAL_CENTER,
                                'vertical' => Alignment::VERTICAL_CENTER,
                                'wrapText' => true
                            ]
                        ]);
                        $tempColIndex++;
                    }
                }
                $sheet->getRowDimension($rowIndex)->setRowHeight(76);

                $rowIndex += 1;
                foreach ($etudiants as $etudiant) {
                    /** @var Etudiant $etudiant */
                    $sheet->setCellValue([1, $rowIndex], $etudiant);
                    if ($etudiant->isRedoublant()) {
                        $sheet->getStyle([2, $rowIndex, 2 + $optionalUEsCount - 1, $rowIndex])->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => 'FF0000']
                            ]
                        ]);
                        continue;
                    }
                    $tempColIndex = 2;
                    foreach ($optionalUEs as $optionalUE) {
                        /** @var BlocUeUe $optionalUE */
                        $choice = $etudiant->getResponseCampagnes()->map(function (ResponseCampagne $responseCampagne) use ($optionalUE) {
                            return $this->choixRepository->findOneBy([
                                'responseCampagne' => $responseCampagne,
                                'UE' => $optionalUE->getUE()
                            ]);
                        })->filter(function ($choice) {
                            return $choice != null;
                        })->first();

                        if ($choice != null) {
                            $sheet->setCellValue([$tempColIndex, $rowIndex], $choice->getOrdre());
                        }

                        $tempColIndex++;
                    }
                    foreach ($optionalUEs as $optionalUE) {
                        /** @var BlocUeUe $optionalUE */
                        $studentUE = $etudiant->getEtudiantUEs()->filter(function (EtudiantUE $studentUE) use ($optionalUE) {
                            return $studentUE->getUE() === $optionalUE->getUE();
                        })->first();

                        if ($studentUE != null) {
                            $sheet->setCellValue([$tempColIndex, $rowIndex], $etudiant);
                        }

                        $tempColIndex++;
                    }
                    $sheet->getStyle([2, $rowIndex, 2 + $optionalUEsCount - 1, $rowIndex])->applyFromArray([
                        'font' => [
                            'size' => 11
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_BOTTOM
                        ]
                    ]);
                    $rowIndex++;
                }
                $sheet->getStyle([1, $blocUEFirstRow, 1 + ($optionalUEsCount * 2), $rowIndex - 1])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

                $rowIndex += 1;
            }
        }

        // Create a tab for all students
        $spreadsheet->createSheet();
        $spreadsheet->setActiveSheetIndex($spreadsheet->getSheetCount() - 1);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Tous étudiants');

        // for each parcours list students
        $colIndex = 1;
        foreach ($parcours as $p) {
            $etudiants = $p->getEtudiants();

            $sheet->setCellValue([$colIndex + 1, 1], 'Nom');
            $sheet->setCellValue([$colIndex + 2, 1], 'Parcours');

            $sheet->getColumnDimensionByColumn($colIndex)->setAutoSize(true); // auto size column
            $sheet->getColumnDimensionByColumn($colIndex + 1)->setAutoSize(true); // auto size column
            $sheet->getColumnDimensionByColumn($colIndex + 2)->setAutoSize(true); // auto size column

            $sheet->getStyle([$colIndex + 1, 1, $colIndex + 2, 1])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFDCE6F1'); // set background color

            $rowIndex = 2;
            foreach ($etudiants as $index => $e) {
                $sheet->setCellValue([$colIndex, $rowIndex], $index + 1);
                $sheet->setCellValue([$colIndex + 1, $rowIndex], $e);

                // if the student is a redoublant, add a red background
                if ($e->isRedoublant()) {
                    $sheet->getStyle([$colIndex + 1, $rowIndex])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FCE4D6');
                }

                $sheet->setCellValue([$colIndex + 2, $rowIndex], $e->getParcours()->getLabel());
                $sheet->getStyle([$colIndex + 2, $rowIndex])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // center text
                $rowIndex++;
            }

            // make grid borders around the table
            $sheet->getStyle([$colIndex + 1, 1, $colIndex + 2, $rowIndex - 1])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            $colIndex += 4;
        }

        $ues = $parcours->map(function (Parcours $parcours) {
            return $parcours->getBlocUEs()->map(function (BlocUE $blocUE) {
                return $blocUE->getBlocUeUes()->map(function (BlocUeUe $blocUeUe) {
                    return $blocUeUe->getUE();
                })->toArray();
            })->toArray();
        })->toArray();
        $ues = array_merge(...array_merge(...$ues));
        $ues = array_unique($ues, SORT_REGULAR);

        // create a tab for each UE
        foreach ($ues as $ue) {
            /** @var UE $ue */
            $spreadsheet->createSheet();
            $spreadsheet->setActiveSheetIndex($spreadsheet->getSheetCount() - 1);

            $sheet = $spreadsheet->getActiveSheet();
            $title = $ue->getLabel();
            $title = substr($title, 0, min(strlen($title), 31));
            $sheet->setTitle($title); // max 31 characters for sheet name TODO : find a better way to do this

            $sheet->setCellValue([1, 1], $ue->getLabel());
            $sheet->getStyle([1, 1])->getFont()->setBold(true)->setSize(14);

            $sheet->setCellValue([1, 5], 'Num');
            $sheet->setCellValue([2, 5], 'Nom');
            $sheet->setCellValue([3, 5], 'Parcours');
            $sheet->setCellValue([4, 5], 'Groupe');

            $sheet->getStyle([1, 5, 4, 5])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB('FFDCE6F1'); // set background color
            $sheet->getStyle([1, 5])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // center text
            $sheet->getStyle([3, 5])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // center text
            $sheet->getStyle([4, 5])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // center text

            $groupes = $ue->getGroupes();
            $rowIndex = 6;
            $etudiantIndex = 1;
            $colors = ["E2EFDA", "FCE4D6", "FFF"];
            foreach ($groupes as $gIndex => $groupe) {
                // unique background color for each group, from array of colors
                $color = $gIndex < count($colors) ? $colors[$gIndex] : $colors[$gIndex % count($colors)];
                /** @var Groupe $groupe */
                foreach ($groupe->getEtudiants() as $e) {
                    /** @var Etudiant $e */
                    $sheet->setCellValue([1, $rowIndex], $etudiantIndex);
                    $sheet->setCellValue([2, $rowIndex], $e);
                    $sheet->setCellValue([3, $rowIndex], $e->getParcours()->getLabel());
                    $sheet->setCellValue([4, $rowIndex], $groupe->getLabel());

                    $sheet->getStyle([2, $rowIndex, 4, $rowIndex])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($color); // set background color
                    $sheet->getStyle([1, $rowIndex])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // center text
                    $sheet->getStyle([3, $rowIndex])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // center text
                    $sheet->getStyle([4, $rowIndex])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // center text

                    $sheet->getColumnDimensionByColumn(2)->setAutoSize(true);
                    $sheet->getColumnDimensionByColumn(3)->setAutoSize(true);
                    $sheet->getColumnDimensionByColumn(4)->setAutoSize(true);

                    $etudiantIndex += 1;
                    $rowIndex += 1;
                }
            }

            // make grid borders around the table
            $sheet->getStyle([1, 5, 4, $rowIndex - 1])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }

        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $spreadsheet->setActiveSheetIndex(0);

        // Create your Office 2007 Excel (XLSX Format)
        $writer = new WriterXlsx($spreadsheet);

        // Create a Temporary file in the system
        $fileName = "IP_MASTER_MIAGE_{$anneeFormation->getLabel()}.xlsx";
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        // Create the excel file in the tmp directory of the system
        $writer->save($temp_file);

        // Return the excel file as an attachment
        return $this->file($temp_file, $fileName);
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
                $etudiantRepository->save($etudiant);
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

                $moveStudentService->moveEtudiantToParcours($etudiant, $etudiant->getParcours(), false);
                $etudiantRepository->save($etudiant, true);

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
