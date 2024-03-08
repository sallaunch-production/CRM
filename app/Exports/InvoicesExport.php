<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;
use \Maatwebsite\Excel\Sheet;

class InvoicesExport implements  WithHeadings, WithCustomStartCell, WithEvents{ 

    public function startCell(): string
    {
        return 'A2';
    }

    public function registerEvents(): array {
        
        return [
            AfterSheet::class => function(AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;

                $sheet->mergeCells('A1:B1');
                $sheet->setCellValue('A1', "Account 1");

                $sheet->mergeCells('C1:D1');
                $sheet->setCellValue('C1', "Account 2");
                
                $styleArray = [
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                
                $cellRange = 'A1:D1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }
      
    public function headings(): array
    {
        return [
                "Account 1 id",
                "Account 1 branch", 
                "Account 2 id",
                "Account 2 branch"  
        ];
    }
}