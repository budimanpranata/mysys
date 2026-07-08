<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;

class NeracaExport implements FromCollection, WithHeadings, WithColumnWidths, WithTitle
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        $rows = collect();

        foreach ($this->data['aktiva'] as $row) {
            $rows->push($this->rowFromObject($row));
        }
        $rows->push(['', '', 'TOTAL AKTIVA', $this->data['totalAktiva']->saldo_awal, $this->data['totalAktiva']->mut_debet, $this->data['totalAktiva']->mut_kredit, $this->data['totalAktiva']->saldo_akhir]);
        $rows->push(['', '', '', '', '', '', '']);

        foreach ($this->data['pasiva'] as $row) {
            $rows->push($this->rowFromObject($row));
        }
        $rows->push(['', '', 'TOTAL PASIVA', $this->data['totalPasiva']->saldo_awal, $this->data['totalPasiva']->mut_debet, $this->data['totalPasiva']->mut_kredit, $this->data['totalPasiva']->saldo_akhir]);
        $rows->push(['', '', '', '', '', '', '']);

        foreach ($this->data['rugiLaba'] as $row) {
            $rows->push($this->rowFromObject($row));
        }

        foreach ($this->data['admin'] as $row) {
            $rows->push($this->rowFromObject($row));
        }

        $rows->push(['', '', 'SHU OPERASIONAL', $this->data['shuOps']->saldo_awal, $this->data['shuOps']->mut_debet, $this->data['shuOps']->mut_kredit, $this->data['shuOps']->saldo_akhir]);
        $rows->push(['', '', 'SHU NON OPERASIONAL', abs($this->data['shuNonOps']->saldo_awal), abs($this->data['shuNonOps']->mut_debet), abs($this->data['shuNonOps']->mut_kredit), abs($this->data['shuNonOps']->saldo_akhir)]);
        $rows->push(['', '', 'SHU TAHUN BERJALAN SEBELUM PAJAK', $this->data['shuSebelumPajak']->saldo_awal, $this->data['shuSebelumPajak']->mut_debet, $this->data['shuSebelumPajak']->mut_kredit, $this->data['shuSebelumPajak']->saldo_akhir]);
        $rows->push(['', '', 'ESTIMASI TAKSIRAN PAJAK PENGHASILAN', $this->data['estimasiPajak']->saldo_awal, $this->data['estimasiPajak']->mut_debet, $this->data['estimasiPajak']->mut_kredit, $this->data['estimasiPajak']->saldo_akhir]);
        $rows->push(['', '', 'SHU TAHUN BERJALAN SETELAH PAJAK', $this->data['shuSetelahPajak']->saldo_awal, $this->data['shuSetelahPajak']->mut_debet, $this->data['shuSetelahPajak']->mut_kredit, $this->data['shuSetelahPajak']->saldo_akhir]);

        return $rows;
    }

    private function rowFromObject($row): array
    {
        return [
            $row->LINE_BALANCE,
            $row->kode_rekening,
            $row->nama_rekening,
            $row->saldo_awal,
            $row->mut_debet,
            $row->mut_kredit,
            $row->saldo_akhir,
        ];
    }

    public function headings(): array
    {
        return ['Type COA', 'COA', 'Deskripsi', 'Saldo Awal', 'Mutasi Debet', 'Mutasi Kredit', 'Saldo Akhir'];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 12,
            'B' => 12,
            'C' => 45,
            'D' => 18,
            'E' => 18,
            'F' => 18,
            'G' => 18,
        ];
    }

    public function title(): string
    {
        return 'Neraca';
    }
}
