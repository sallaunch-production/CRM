<?php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class NewCustomerExport implements FromQuery, WithHeadings, ShouldAutoSize
{
    protected $tanggal_dari;
    protected $tanggal_sampai;

    public function __construct($tanggal_dari, $tanggal_sampai)
    {
        $this->tanggal_dari = $tanggal_dari;
        $this->tanggal_sampai = $tanggal_sampai;
    }

    public function query()
    {
        return DB::table('transaksi_valid as t1')
            ->select(
                DB::raw('MIN(t1.no) AS no'),
                't1.nama_penerima',
                DB::raw('MIN(t1.no_referensi) AS no_referensi'),
                DB::raw('MIN(t1.tanggal) AS tanggal'),
                DB::raw('MIN(t1.nomor_identitas) AS nomor_identitas'),
                DB::raw('MIN(t1.tanggal_lahir) AS tanggal_lahir'),
                DB::raw('MIN(t1.tempat_lahir) AS tempat_lahir'),
                DB::raw('MIN(t1.kota_kelahiran) AS kota_kelahiran'),
                DB::raw('MIN(t1.alamat_penerima) AS alamat_penerima'),
                DB::raw('MIN(t1.telepon_penerima) AS telepon_penerima'),
                DB::raw('MIN(t1.negara_pengirim) AS negara_pengirim'),
                DB::raw('MIN(t1.kode_pos_penerima) AS kode_pos_penerima')
            )
            ->leftJoin('transaksi_valid as t2', function ($join) {
                $join->on('t1.nama_penerima', '=', 't2.nama_penerima')
                    ->where('t2.tanggal', '<=', $this->tanggal_dari . ' 23:59:59');
            })
            ->whereBetween('t1.tanggal', [
                $this->tanggal_dari . ' 00:00:00',
                $this->tanggal_sampai . ' 23:59:59'
            ])
            ->whereNull('t2.nama_penerima')
            ->groupBy('t1.nama_penerima');
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Penerima',
            'No Referensi',
            'Tanggal',
            'Nomor Identitas',
            'Tanggal Lahir',
            'Tempat Lahir',
            'Kota Kelahiran',
            'Alamat',
            'Telepon',
            'Negara Pengirim',
            'Kode Pos',
        ];
    }
}
