<?php

namespace App\Service;


use App\Entity\Main\AnneeFormation;
use App\Entity\Main\EtudiantUE;
use App\Entity\Main\Parcours;
use App\Entity\Main\ResponseCampagne;
use App\Repository\ChoixRepository;
use Doctrine\Common\Collections\Collection;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as WriterXlsx;

class ExportStudentService
{
    const LIGHT_ORANGE = 'FCE4D6';
    const LIGHT_BLUE = 'DCE6F1';
    const LIGHT_GREEN = 'E2EFDA';
    const LIGHT_RED = 'F2DCDB';
    const LIGHT_PURPLE = 'E4DFEC';
    const LIGHT_BROWN = 'DDD9C4';

    private Spreadsheet $spreadsheet;

    public function __construct(private readonly ChoixRepository $choixRepository)
    {
        $this->spreadsheet = new Spreadsheet();
    }

    public function prepareSpreadsheet(AnneeFormation $anneeFormation)
    {
        $this->spreadsheet->removeSheetByIndex(0);

        $parcours = $anneeFormation->getParcours();

        foreach ($parcours as $p) {
            $this->createParcoursSheet($p, $anneeFormation);
        }

        $this->createEtudiantsSheet($parcours);
        $this->createUeSheets($parcours);

        $this->spreadsheet->setActiveSheetIndex(0);
    }

    public function createParcoursSheet(Parcours $parcours, AnneeFormation $anneeFormation)
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle($this->computeTitle($parcours->getLabel()));
        $sheet->getTabColor()->setRGB(self::LIGHT_ORANGE);

        $sheet->mergeCells('A1:D1');
        $sheet->setCellValue('A1', "Choix d'options \n M2 MIAGE - {$parcours->getLabel()} \n {$anneeFormation->getLabel()}");
        $this->applyStyle($sheet, 'A1:D1', [
            'font' => [
                'bold' => true,
                'size' => 16
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ]);
        $sheet->getRowDimension(1)->setRowHeight(98);

        $sheet->getColumnDimension('A')->setAutoSize(true);

        $rowIndex = 3;
        $sheet->setCellValue('A' . $rowIndex, "PARCOURS {$parcours->getLabel()}");
        $this->applyStyle($sheet, 'A' . $rowIndex, [
            'font' => [
                'bold' => true,
                'size' => 14
            ]
        ]);

        $rowIndex = 5;
        foreach ($parcours->getBlocUEs() as $blocUE) {
            $nbUEsOptional = $blocUE->getNbUEsOptional();
            $optionalUEs = $blocUE->getOptionalUEs();
            $optionalUEsCount = $optionalUEs->count();

            if ($nbUEsOptional == 0 || $optionalUEsCount == 0) {
                continue;
            }

            $etudiants = $parcours->getEtudiants();
            $etudiantsCount = $etudiants->count();

            $sheet->setCellValue('A' . $rowIndex, "Bloc {$blocUE->getCategory()->getLabel()} - {$nbUEsOptional} option" . ($nbUEsOptional > 1 ? 's' : '') . " parmi {$optionalUEsCount}");
            $this->applyStyle($sheet, 'A' . $rowIndex, [
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
                $tempColIndexLetter = Coordinate::stringFromColumnIndex($tempColIndex);
                $sheet->setCellValue($tempColIndexLetter . $rowIndex, "=SUMIF({$tempColIndexLetter}{$tempRowIndex}:{$tempColIndexLetter}" . ($tempRowIndex + $etudiantsCount - 1) . ",1)");
                $tempColIndex++;
            }
            $this->applyStyle($sheet, [2, $rowIndex, 2 + $optionalUEsCount - 1, $rowIndex], [
                'font' => [
                    'bold' => true,
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
            $sheet->setCellValue('A' . $rowIndex, "Classer de 1 à {$optionalUEsCount}");
            $this->applyStyle($sheet, 'A' . $rowIndex, [
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
                    $coordinate = [$tempColIndex, $rowIndex];
                    $sheet->setCellValue($coordinate, $optionalUE->getUE()->getLabel());
                    $this->applyStyle($sheet, $coordinate, [
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
                $sheet->setCellValue('A' . $rowIndex, $etudiant);
                if ($etudiant->isRedoublant()) {
                    $this->applyStyle($sheet, [2, $rowIndex, 2 + $optionalUEsCount - 1, $rowIndex], [
                        'font' => [
                            'color' => ['rgb' => 'FF0000']
                        ]
                    ]);
                    continue;
                }
                $tempColIndex = 2;
                foreach ($optionalUEs as $optionalUE) {
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
                    $studentUE = $etudiant->getEtudiantUEs()->filter(function (EtudiantUE $studentUE) use ($optionalUE) {
                        return $studentUE->getUE() === $optionalUE->getUE();
                    })->first();

                    if ($studentUE != null) {
                        $sheet->setCellValue([$tempColIndex, $rowIndex], $etudiant);
                    }

                    $tempColIndex++;
                }
                $this->applyStyle($sheet, [2, $rowIndex, 2 + $optionalUEsCount - 1, $rowIndex], [
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

    public function createEtudiantsSheet(Collection $parcours)
    {
        $sheet = $this->spreadsheet->createSheet();
        $sheet->setTitle($this->computeTitle('Tous étudiants'));

        $colIndex = 1;
        foreach ($parcours as $p) {
            $etudiants = $p->getEtudiants();

            $sheet->setCellValue([$colIndex + 1, 1], 'Nom');
            $sheet->setCellValue([$colIndex + 2, 1], 'Parcours');

            $sheet->getColumnDimensionByColumn($colIndex)->setAutoSize(true);
            $sheet->getColumnDimensionByColumn($colIndex + 1)->setAutoSize(true);
            $sheet->getColumnDimensionByColumn($colIndex + 2)->setAutoSize(true);

            $sheet->getStyle([$colIndex + 1, 1, $colIndex + 2, 1])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB(self::LIGHT_BLUE);

            $rowIndex = 2;
            foreach ($etudiants as $index => $e) {
                $sheet->setCellValue([$colIndex, $rowIndex], $index + 1);
                $sheet->setCellValue([$colIndex + 1, $rowIndex], $e);

                if ($e->isRedoublant()) {
                    $sheet->getStyle([$colIndex + 1, $rowIndex])->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB(self::LIGHT_ORANGE);
                }

                $sheet->setCellValue([$colIndex + 2, $rowIndex], $e->getParcours()->getLabel());
                $sheet->getStyle([$colIndex + 2, $rowIndex])->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $rowIndex++;
            }

            $sheet->getStyle([$colIndex + 1, 1, $colIndex + 2, $rowIndex - 1])->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            $colIndex += 4;
        }
    }

    public function createUeSheets(Collection $parcours)
    {
        $ues = [];
        foreach ($parcours as $p) {
            foreach ($p->getBlocUEs() as $blocUE) {
                foreach ($blocUE->getBlocUeUes() as $blocUeUe) {
                    $ues[] = $blocUeUe->getUE();
                }
            }
        }
        $ues = array_unique($ues, SORT_REGULAR);

        foreach ($ues as $ue) {
            $sheet = $this->spreadsheet->createSheet();
            $sheet->setTitle($this->computeTitle($ue->getLabel()));

            $sheet->setCellValue('A1', $ue->getLabel());
            $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

            $sheet->setCellValue('A5', 'Num');
            $sheet->setCellValue('B5', 'Nom');
            $sheet->setCellValue('C5', 'Parcours');
            $sheet->setCellValue('D5', 'Groupe');

            $sheet->getStyle('A5:D5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB(self::LIGHT_BLUE);
            $sheet->getStyle('A5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('C5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('D5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $groupes = $ue->getGroupes();
            $rowIndex = 6;
            $etudiantIndex = 1;
            $colors = [self::LIGHT_GREEN, self::LIGHT_ORANGE, self::LIGHT_RED, self::LIGHT_PURPLE, self::LIGHT_BROWN];
            foreach ($groupes as $gIndex => $groupe) {
                $color = $gIndex < count($colors) ? $colors[$gIndex] : $colors[$gIndex % count($colors)];
                foreach ($groupe->getEtudiants() as $e) {
                    $sheet->setCellValue('A' . $rowIndex, $etudiantIndex);
                    $sheet->setCellValue('B' . $rowIndex, $e);
                    $sheet->setCellValue('C' . $rowIndex, $e->getParcours()->getLabel());
                    $sheet->setCellValue('D' . $rowIndex, $groupe->getLabel());

                    $sheet->getStyle('B' . $rowIndex . ':D' . $rowIndex)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setRGB($color);
                    $sheet->getStyle('A' . $rowIndex)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('C' . $rowIndex)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                    $sheet->getStyle('D' . $rowIndex)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $sheet->getColumnDimension('B')->setAutoSize(true);
                    $sheet->getColumnDimension('C')->setAutoSize(true);
                    $sheet->getColumnDimension('D')->setAutoSize(true);

                    $etudiantIndex += 1;
                    $rowIndex += 1;
                }
            }

            $sheet->getStyle('A5:D' . ($rowIndex - 1))->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        }
    }

    public function saveSpreadsheet(AnneeFormation $anneeFormation)
    {
        $writer = new WriterXlsx($this->spreadsheet);

        $fileName = "IP_MASTER_MIAGE_{$anneeFormation->getLabel()}.xlsx";
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);

        return $temp_file;
    }

    private function applyStyle(Worksheet $sheet, mixed $cell, array $style)
    {
        $sheet->getStyle($cell)->applyFromArray($style);
    }

    public function computeTitle(string $title): string
    {
        return substr($title, 0, min(31, strlen($title)));
    }

}