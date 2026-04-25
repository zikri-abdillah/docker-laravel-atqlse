<?php

namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class SpreadsheetHelper
{
    public static function createExcelFile(array $data, array $headers, string $filename = 'report.xlsx')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set column headers
        $colIndex = 1;
        foreach ($headers as $header) {
            $cell = Coordinate::stringFromColumnIndex($colIndex) . '1';
            $sheet->setCellValue($cell, $header);

            // Apply border style to header
            $sheet->getStyle($cell)->applyFromArray([
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000']
                    ]
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FFC0C0C0']
                ]
            ]);

            $colIndex++;
        }

        // Set data rows
        $rowIndex = 2;
        $arrMerge = [];
        foreach ($data as $keyRow => $row) {
            $colIndex = 1;

            // Write main data
            foreach ($row as $key => $cell) {
                if (is_array($cell)) {
                    $arrMerge[$keyRow]['start'] = $rowIndex;
                    // If cell contains an array, process nested data
                    foreach ($cell as $nestedRow) {
                        // Write the nested array data
                        $nestedColIndex = $colIndex;
                        $arrMerge[$keyRow]['endCol'] = $nestedColIndex;
                        foreach ($nestedRow as $nestedCell) {
                            $cellAddress = Coordinate::stringFromColumnIndex($nestedColIndex) . $rowIndex;
                            if(isset($cell) && !is_array($nestedCell)){
                                $sheet->setCellValue($cellAddress, $nestedCell);
                            }

                            $idxNtpn = 0;
                            if(is_array($nestedCell) && count($nestedCell) > 0)
                            {
                                $idxNtpn++;
                                foreach ($nestedCell as $ntpn) {
                                    $conNtpn = $nestedColIndex;
                                    foreach ($ntpn as $key => $valntpn) {
                                        if(isset($valntpn) && !is_array($valntpn)){
                                            $cellAddress = Coordinate::stringFromColumnIndex($conNtpn) . $rowIndex;
                                            $sheet->setCellValue($cellAddress, $valntpn);
                                            $conNtpn++;
                                            //$rowIndex++;
                                        }
                                    }
                                    // if($idxNtpn < count($nestedCell)){
                                    //     $rowIndex++;
                                    // }
                                }
                            }
                            else{
                                $nestedColIndex++;
                            }
                        }
                        $rowIndex++;
                    }
                    $arrMerge[$keyRow]['end'] = $rowIndex;
                } else {
                    $cellAddress = Coordinate::stringFromColumnIndex($colIndex) . $rowIndex;
                    if(isset($cell) && !is_array($cell))
                        $sheet->setCellValue($cellAddress, $cell);
                    $colIndex++;
                }
            }
            // Move to the next row
            //$rowIndex++;
        }
        // foreach ($arrMerge as $keyx => $value) {
        //     if($value['start'] != $value['end'])
        //     {
        //         for ($i=1; $i < $value['endCol']; $i++) {
        //             $start = Coordinate::stringFromColumnIndex($i) . $value['start'];
        //             $end = Coordinate::stringFromColumnIndex($i) . $value['end'];
        //             $sheet->mergeCells($start.':'.$end);
        //         }
        //     }
        // }

        // Apply border only to the outside of the entire sheet
        $lastCol = Coordinate::stringFromColumnIndex(count($headers));
        $sheet->getStyle("A1:$lastCol" . ($rowIndex - 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000']
                ]
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_TOP
            ]
        ]);

        // Auto size columns
        foreach (range('A', $lastCol) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Write file
        $writer = new Xlsx($spreadsheet);
        $writer->save($filename);
    }
}
