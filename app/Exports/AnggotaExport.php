<?php

namespace App\Exports;

use App\Models\Anggota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AnggotaExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // return Anggota::all();
        return Anggota::select(
            'no',
            'kode_kel',
            'norek',
            'tgl_join',
            'cif',
            'nama',
            'unit',
            'deal_type',
            'alamat',
            'desa',
            'kecamatan',
            'kota',
            'rtrw',
            'kode_pos',
            'no_hp',
            'hp_pasangan',
            'kelamin',
            'tempat_lahir',
            'tgl_lahir',
            'ktp',
            'kewarganegaraan',
            'status_menikah',
            'agama',
            'ibu_kandung',
            'npwp',
            'source_income',
            'pendidikan',
            'waris',
            'pekerjaan_pasangan',
            'cao',
            'status'
            )->get();
    }

    public function headings(): array
    {
        return [
            'No Anggota',
            'Kode Kelompok',
            'No Rekening',
            'Tanggal Join',
            'CIF',
            'Nama',
            'Unit',
            'Deal Type',
            'Alamat',
            'Desa',
            'Kecamatan',
            'Kota',
            'RT/RW',
            'Kode Pos',
            'No Hp',
            'No Hp Pasangan',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'NIK',
            'Kewarganegaraan',
            'Status Menikah',
            'Agama',
            'Ibu Kandung',
            'Npwp',
            'Source Income',
            'Pendidikan',
            'Nama Pasangan',
            'Pekerjaan Pasangan',
            'CAO',
            'Status'
        ];
    }
}
