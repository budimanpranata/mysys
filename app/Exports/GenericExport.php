<?php
namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class GenericExport implements FromCollection, WithHeadings, WithEvents
{
    protected $data;
    protected $title;

    public function __construct($data, $title = 'Report Data')
    {
        // Pastikan data jadi collection
        $this->data  = collect($data);
        $this->title = $title;
    }

    public function collection()
    {
        return $this->data;
    }

    // Header kolom
    public function headings(): array
    {
        if ($this->data->isNotEmpty()) {
            return array_keys((array) $this->data->first());
        }
        return [];
    }

    // Tambahkan judul lewat AfterSheet
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Sisipkan baris kosong di atas untuk judul
                $sheet->insertNewRowBefore(1, 1);

                // Set judul di A1
                $sheet->setCellValue('A1', $this->title);

                // Merge judul sampai kolom terakhir
                $lastColumn = $sheet->getHighestColumn();
                $sheet->mergeCells("A1:{$lastColumn}1");

                // Styling judul
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
            },
        ];
    }
}



// namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
// use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\WithCustomStartCell;
// use Illuminate\Support\Collection;

// class GenericExport implements FromCollection, WithHeadings, WithCustomStartCell
// {
//     protected $data;
//     protected $title;

//     public function __construct($data, $title = 'Report Data')
//     {
//         $this->data  = collect($data);
//         $this->title = $title;
//     }

//     public function collection()
//     {
//         return $this->data;
//     }

//     // Mulai dari cell A2, jadi A1 dipakai untuk judul
//     public function startCell(): string
//     {
//         return 'A2';
//     }

//     public function headings(): array
//     {
//         if ($this->data->isNotEmpty()) {
//             return array_keys((array) $this->data->first());
//         }
//         return [];
//     }

//     // Tambahkan judul di row pertama
//     public function titleRow(): array
//     {
//         return [$this->title];
//     }
// }

