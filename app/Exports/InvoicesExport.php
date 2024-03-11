<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\FromCollection;
use \Maatwebsite\Excel\Sheet;
use Illuminate\Support\Facades\DB;

class InvoicesExport implements  WithHeadings, WithCustomStartCell, WithEvents{ 

    protected $tanggal_dari;
    protected $tanggal_sampai;

    public function __construct($tanggal_sampai,$tanggal_dari)
    {
       $this->tanggal_dari = $tanggal_dari;
       $this->tanggal_sampai = $tanggal_sampai;
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function registerEvents(): array {
        
        return [
            AfterSheet::class => function(AfterSheet $event) {
                /** @var Sheet $sheet */
                $sheet = $event->sheet;

                $sheet->mergeCells('A1:A4');
                $sheet->setCellValue('A1', "Local ID*");

                $sheet->mergeCells('B1:C1');
                $sheet->mergeCells('B2:C2');
                $sheet->setCellValue('B1', "Umum");
                $event->sheet->getDelegate()->getStyle('B1:C1')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


               
                $sheet->mergeCells('B3:B4');
                $sheet->setCellValue('B3', "No. LTKL Koreksi");

                $sheet->mergeCells('C3:C4');
                $sheet->setCellValue('C3', "Jenis Laporan*");

                $sheet->mergeCells('D1:D4');
                $sheet->setCellValue('D1', "PJK bertindak sebagai*");

                $sheet->mergeCells('E1:Y1');
                $sheet->setCellValue('E1', "Identitas Pengirim (Ordering Customer) ");
                $event->sheet->getDelegate()->getStyle('E1:Y1')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('E2:Q2');
                $sheet->setCellValue('E2', "Perorangan");
                $event->sheet->getDelegate()->getStyle('E2:Q2')
                                ->getAlignment()
                                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

               
                $sheet->mergeCells('E3:E4');
                $sheet->setCellValue('E3', "No Rek");
                $sheet->mergeCells('F3:F4');
                $sheet->setCellValue('F3', "Nama Bank"); 
                $sheet->mergeCells('G3:G4');
                $sheet->setCellValue('G3', "Nama Lengkap*");
                $sheet->mergeCells('H3:H4');
                $sheet->setCellValue('H3', "Tanggal Lahir");
                $sheet->mergeCells('I3:I4');
                $sheet->setCellValue('I3', "Negara Bagian/Kota");
                $sheet->mergeCells('J3:J4');
                $sheet->setCellValue('J3', "Negara*");
                $sheet->mergeCells('K3:K4');
                $sheet->setCellValue('K3', "Negara Lainnya");
                $sheet->mergeCells('L3:L4');
                $sheet->setCellValue('L3', "Apakah sumber dana tunai?*");

                $sheet->mergeCells('M3:N3');
                $sheet->setCellValue('M3', "Apabila sumber dana tunai");
                $event->sheet->getDelegate()->getStyle('M3:N3')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


                $sheet->setCellValue('M4', "Alamat");
                $sheet->setCellValue('N4', "No Telp");

                $sheet->mergeCells('O3:Q3');
                $sheet->setCellValue('O3', "Bukti Identitas");
                $event->sheet->getDelegate()->getStyle('O3:Q3')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

               
                $sheet->setCellValue('O4', "Jenis");
                $sheet->setCellValue('P4', "Identitas Lainnya");
                $sheet->setCellValue('Q4', "No Identitas");
               
                $sheet->mergeCells('R2:Y2');
                $sheet->setCellValue('R2', "Korporasi");
                $event->sheet->getDelegate()->getStyle('R2:Y2')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

               
                $sheet->mergeCells('R3:R4');
                $sheet->setCellValue('R3', "No Rek");
                $sheet->mergeCells('S3:S4');
                $sheet->setCellValue('S3', "Nama Bank");
                $sheet->mergeCells('T3:T4');
                $sheet->setCellValue('T3', "Nama Korporasi*");
                $sheet->mergeCells('U3:U4');
                $sheet->setCellValue('U3', "Negara*");
                $sheet->mergeCells('V3:V4');
                $sheet->setCellValue('V3', "Negara lainnya");
                $sheet->mergeCells('W3:W4');
                $sheet->setCellValue('W3', "Apakah sumber dana tunai?*");
                $sheet->mergeCells('X2:Y2');
                $sheet->setCellValue('X2', "Apabila sumber dana tunai");
                $sheet->setCellValue('X4', "Alamat Korporasi");
                $sheet->setCellValue('Y4', "No Telp");
                $sheet->mergeCells('Z1:BB1');
                $sheet->setCellValue('Z1', "Identitas Penerima (Beneficiary Customer)  ");
                $event->sheet->getDelegate()->getStyle('Z1:BB1')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('Z2:AR2');
                $sheet->setCellValue('Z2', "Perorangan");
                $event->sheet->getDelegate()->getStyle('Z2:AR2')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                
                $sheet->mergeCells('Z3:Z4');
                $sheet->setCellValue('Z3', "Kode Rahasia Transaksi");
                $sheet->mergeCells('AA3:AA4');
                $sheet->setCellValue('AA3', "No Rek");
                $sheet->mergeCells('AB3:AB4');
                $sheet->setCellValue('AB3', "Nama Bank");
                $sheet->mergeCells('AC3:AC4');
                $sheet->setCellValue('AC3', "Nama Lengkap*");
                $sheet->mergeCells('AD3:AD4');
                $sheet->setCellValue('AD3', "Tanggal lahir");
               
                $sheet->mergeCells('AE3:AI3');
                $sheet->setCellValue('AE3', "Alamat Domisili");
                $event->sheet->getDelegate()->getStyle('AE3:AI3')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('AE4', "Alamat");
                $sheet->setCellValue('AF4', "Provinsi");
                $sheet->setCellValue('AG4', "Provinsi Lainnya");
                $sheet->setCellValue('AH4', "Kabupaten/Kota");
                $sheet->setCellValue('AI4', "Kabupaten/Kota Lainnya");

                $sheet->mergeCells('AJ3:AN3');
                $sheet->setCellValue('AJ3', "Alamat Sesuai Bukti Identitas*");
                $event->sheet->getDelegate()->getStyle('AJ3:AN3')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('AJ4', "Alamat*");
                $sheet->setCellValue('AK4', "Provinsi*");
                $sheet->setCellValue('AL4', "Provinsi Lainnya");
                $sheet->setCellValue('AM4', "Kabupaten/Kota*");
                $sheet->setCellValue('AN4', "Kabupaten/Kota Lainnya");
                $sheet->mergeCells('AO3:AO4');
                $sheet->setCellValue('AO3', "No Telp");

                $sheet->mergeCells('AP3:AR3');
                $sheet->setCellValue('AP3', "Bukti Identitas*");
                $event->sheet->getDelegate()->getStyle('AP3:AR3')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


                $sheet->setCellValue('AP4', "Jenis*");
                $sheet->setCellValue('AQ4', "Identias Lainnya");
                $sheet->setCellValue('AR4', "No Identitas*");
                $sheet->mergeCells('AS2:BB2');
                $sheet->setCellValue('AS2', "Korporasi");
                $event->sheet->getDelegate()->getStyle('AS2:BB2')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                
                $sheet->mergeCells('AS3:AS4');
                $sheet->setCellValue('AS3', "Kode Rahasia Transaksi");
                $sheet->mergeCells('AT3:AT4');
                $sheet->setCellValue('AT3', "No Rek");
                $sheet->mergeCells('AU3:AU4');
                $sheet->setCellValue('AU3', "Nama Bank");
                $sheet->mergeCells('AV3:AV4');
                $sheet->setCellValue('AV3', "Nama Korporasi*");
                
                $sheet->mergeCells('AW3:BA3');
                $sheet->setCellValue('AW3', "Alamat Lengkap Korporasi*");
                $event->sheet->getDelegate()->getStyle('AW3:BA3')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('AW4', "Alamat");
                $sheet->setCellValue('AX4', "Provinsi*");
                $sheet->setCellValue('AY4', "Provinsi Lainnya");
                $sheet->setCellValue('AZ4', "Kabupaten/Kota*");
                $sheet->setCellValue('BA4', "Kabupaten/Kota Lainnya");
                $sheet->mergeCells('BB3:BB4');
                $sheet->setCellValue('BB3', "No Telp");

                $sheet->mergeCells('BC1:BK1');
                $sheet->setCellValue('BC1', "Transaksi");
                $event->sheet->getDelegate()->getStyle('BC1:BK1')
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $sheet->mergeCells('BC2:BC4');
                $sheet->setCellValue('BC2', "Tanggal Transaksi");
                $sheet->mergeCells('BD2:BD4');
                $sheet->setCellValue('BD2', "Tanggal efektif penyelesaian transfer");
                $sheet->mergeCells('BE2:BE4');
                $sheet->setCellValue('BE2', "Mata Uang asal yang diinstruksikan");
                $sheet->mergeCells('BF2:BF4');
                $sheet->setCellValue('BF2', "Nilai uang asal yang diinstruksikan");
                $sheet->mergeCells('BG2:BG4');
                $sheet->setCellValue('BG2', "Kurs Nilai Tukar");
                $sheet->mergeCells('BH2:BH4');
                $sheet->setCellValue('BH2', "Mata uang yang diterima*");
                $sheet->mergeCells('BI2:BI4');
                $sheet->setCellValue('BI2', "Mata uang yang diterima dalam");
                $sheet->mergeCells('BJ2:BJ4');
                $sheet->setCellValue('BJ2', "Tujuan transaksi");
                $sheet->mergeCells('BK2:BK4');
                $sheet->setCellValue('BK2', "Sumber penggunaan dana");
               
                $no=1;
                $line=5;
                $total_bsu=0;
                $qur = DB::table('transaksi_valid')->where('deltawork','=','ya')->where('id_transaksi','<>','')->where('tanggal','>=',$this->tanggal_dari.' 00:00:00')->where('tanggal','<=',$this->tanggal_sampai.' 23:59:59')->orderBy('no','asc')->get(); 
                foreach($qur as $datatrans){
                    $idpos= substr($datatrans->id_pos, 0, 5);
                    if($datatrans->identitas=="GOV"){
                    $identitas=1;
                    }
                    elseif($datatrans->identitas=="DRV"){
                    $identitas=2;
                    }
                    elseif($datatrans->identitas=="PAS"){
                    $identitas=3;
                    }
                    else{
                    $identitas=1;
                    }

                    $kodepos=DB::table('kp_konverter')->join('nopend_lama','nopend_lama.kprk = kp_konverter.kprk')->where('kpc','like',$idpos.'%')->first();
                    $kodenegara=DB::table('negara_konverter')->where('nama','like','%'.$datatrans->negara_pengirim.'%')->first();
                    $kodeposasli_ppatk=$kodepos->ppatk;
                    $sheet->setCellValue('C'.$line, 1);
                    $sheet->setCellValue('D'.$line, 1);
                    $sheet->setCellValue('D'.$line, 1);
                    $sheet->setCellValue('G'.$line, $datatrans->nama_pengirim);
                    $sheet->setCellValue('J'.$line, $kodenegara['kodeppatk']);
                    $sheet->setCellValue('L'.$line, 1);
                    $sheet->setCellValue('T'.$line, 'moneygram');
                    $sheet->setCellValue('U'.$line, $kodenegar->kodeppatk);
                    $sheet->setCellValue('W'.$line, 2);
                    $sheet->setCellValue('Z'.$line, $datatrans->no_referensi);
                    $sheet->setCellValue('AC'.$line, $datatrans->nama_penerima);
                    $sheet->setCellValue('AD'.$line, $datatrans->tanggal_lahir);
                    $sheet->setCellValue('AE'.$line, $datatrans->alamat_penerima);
                    $sheet->setCellValueExplicit('AF'.$line, $kodepos->propinsi);
                    $sheet->setCellValueExplicit('AH'.$line, $kodeposasli_ppatk);
                    $sheet->setCellValueExplicit('AK'.$line, $kodepos->propinsi);
                    $sheet->setCellValueExplicit('AM'.$line, $kodeposasli_ppatk);
                    $sheet->setCellValue('AJ'.$line, $datatrans->alamat_penerima);
                    $sheet->setCellValue('AP'.$line, $identitas);
                    $sheet->setCellValueExplicit('AR'.$line, $datatrans->nomor_identitas,PHPExcel_Cell_DataType::TYPE_STRING);
                    $sheet->setCellValue('AV'.$line, 'ARSEMA');
                    $sheet->setCellValue('AW'.$line, $datatrans->alamat_penerima);
                    $sheet->setCellValueExplicit('AX'.$line, $kodepos->propinsi);
                    $sheet->setCellValueExplicit('AZ'.$line, $kodeposasli_ppatk);
                    $sheet->setCellValue('BC'.$line,  $datatrans->tanggal);
                    $sheet->setCellValue('BD'.$line,  $datatrans->tanggal);
                    $sheet->setCellValue('BE'.$line,  74);
                    $sheet->setCellValue('BH'.$line,  74);
                    $sheet->setCellValue('BI'.$line,  $datatrans->bsubanyak);
                                	

                }
               


                $event->sheet->getStyle('A1:A4')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getStyle('B1:C4')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getStyle('D1:D4')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getStyle('E1:Y4')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getStyle('Z1:BB4')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $event->sheet->getStyle('BC1:BK4')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);


                $event->sheet->getDelegate()->getStyle('A1:A4')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('b2dee7');

                $event->sheet->getDelegate()->getStyle('B1:C4')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('cdc0d9');
                $event->sheet->getDelegate()->getStyle('D1:D4')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('ffd5b6');

                $event->sheet->getDelegate()->getStyle('E1:Y4')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('d7e4be');
                $event->sheet->getDelegate()->getStyle('Z1:BB4')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('eab8b8');

               $event->sheet->getDelegate()->getStyle('BC1:BK4')
                        ->getFill()
                        ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                        ->getStartColor()
                        ->setARGB('b6cce3');
              
                
                
                
                
                
                $styleArray = [
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ];
                
               // $cellRange = 'A1:D1'; // All headers
                //$event->sheet->getDelegate()->getStyle($cellRange)->applyFromArray($styleArray);
            },
        ];
    }
      
    public function headings(): array
    {
        return [
                /*"Account 1 id",
                "Account 1 branch", 
                "Account 2 id",
                "Account 2 branch" */ 
        ];
    }
}