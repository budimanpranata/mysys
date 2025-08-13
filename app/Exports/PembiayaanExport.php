<?php

namespace App\Exports;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PembiayaanExport implements FromCollection, WithTitle, WithHeadings, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $status;
    protected $bulan;
    protected $tahun;

    public function __construct($status, $bulan, $tahun)
    {
        $this->status = $status;
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $query = DB::table('pembiayaan')
            ->join('anggota', 'pembiayaan.no_anggota', '=', 'anggota.no')
            ->join('ao', 'pembiayaan.cao', '=', 'ao.cao')
            ->leftJoin('kelompok', 'pembiayaan.code_kel', '=', 'kelompok.code_kel')
            ->leftJoin('tunggakan', 'pembiayaan.no_anggota', '=', 'tunggakan.norek')
            ->where('pembiayaan.unit', Auth::user()->unit)
            ->select([
                DB::raw('MAX(pembiayaan.unit) as unit'),
                'pembiayaan.no_anggota',
                DB::raw('MAX(pembiayaan.nama) as nama'),
                DB::raw('MAX(pembiayaan.code_kel) as code_kel'),
                DB::raw('MAX(pembiayaan.tgl_murab) as tgl_murab'),
                DB::raw('MAX(pembiayaan.tenor) as tenor'),
                DB::raw('MAX(pembiayaan.maturity_date) as maturity_date'),
                DB::raw('MAX(pembiayaan.plafond) as plafond'),
                DB::raw('MAX(pembiayaan.os - pembiayaan.saldo_margin) as saldo_pinjaman'),
                DB::raw('MAX(pembiayaan.os) as os'),
                DB::raw('MAX(pembiayaan.saldo_margin) as saldo_margin'),
                DB::raw('MAX(pembiayaan.ke) as ke'),
                DB::raw("MAX(CASE WHEN tunggakan.type = '01' THEN COALESCE(tunggakan.debet, 0) - COALESCE(tunggakan.kredit, 0) ELSE 0 END) as tunggakan_pokok"),
                DB::raw("MAX(CASE WHEN tunggakan.type = '02' THEN COALESCE(tunggakan.debet, 0) - COALESCE(tunggakan.kredit, 0) ELSE 0 END) as tunggakan_margin"),
                DB::raw('MAX(pembiayaan.gol) as gol'),
                DB::raw('MAX(pembiayaan.cao) as cao'),
                DB::raw('MAX(ao.nama_ao) as nama_ao'),
                DB::raw('MAX(kelompok.nama_kel) as nama_kelompok'),
                DB::raw('MAX(pembiayaan.cif) as cif'),
                DB::raw('MAX(anggota.alamat) as alamat'),
                DB::raw('MAX(pembiayaan.nama_usaha) as nama_usaha'),
                DB::raw('MAX(anggota.ktp) as ktp'),
                DB::raw('MAX(pembiayaan.status_app) as status'),
                DB::raw('MAX(ao.cao) as sumber_dana'),
                DB::raw('MAX(pembiayaan.bulat - pembiayaan.angsuran) as twm'),
                DB::raw('MAX((pembiayaan.bulat - pembiayaan.angsuran) * pembiayaan.run_tenor) as saldo_twm'),
                
            ])
            ->groupBy('pembiayaan.no_anggota');

        // Tambahkan filter berdasarkan status
        if ($this->status === 'current') {
            $query->whereDate('pembiayaan.buss_date', Carbon::now()->format('Y-m-d'));
        } elseif ($this->status === 'eom') {
            $bulanAngka = match(strtolower($this->bulan)) {
                'januari' => '01',
                'februari' => '02',
                'maret' => '03',
                'april' => '04',
                'mei' => '05',
                'juni' => '06',
                'juli' => '07',
                'agustus' => '08',
                'september' => '09',
                'oktober' => '10',
                'november' => '11',
                'desember' => '12',
                default => null,
            };

            $start = Carbon::createFromDate($this->tahun, $bulanAngka, 1)->startOfMonth();
            $end = Carbon::createFromDate($this->tahun, $bulanAngka, 1)->endOfMonth();
            $query->whereBetween('pembiayaan.buss_date', [$start, $end]);
        }

        return $query->get();
    }


    public function headings(): array
    {
        return [
            'UNIT',
            'NO. REKENING',
            'NAMA',
            'KELOMPOK',
            'TANGGAL PK',
            'TENOR',
            'JATUH TEMPO',
            'PLAFOND',
            'SALDO PINJAMAN',
            'SALDO OS',
            'SALDO MARGIN',
            'KE',
            'TUNGGAKAN POKOK',
            'TUNGGAKAN MARGIN',
            'GOL',
            'AO',
            'NAMA AO',
            'NAMA KELOMPOK',
            'CIF',
            'ALAMAT',
            'BIDANG USAHA',
            'NO KTP',
            'STATUS',
            'SUMBER DANA',
            'TWM',
            'SALDO TWM',
            'SISA TWM'
        ];
    }

    public function title(): string
    {
        return 'Nominative';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'] // white text
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF008000'] // green
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                ]
            ]
        ];
    }

}

