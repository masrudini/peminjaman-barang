<?php

namespace App\Exports;

use App\Models\Barang;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BarangExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents, WithStyles
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $barang = Barang::latest()->get()->map(function ($item) {
            return [
                'kode_barang' => $item->kode_barang,
                'nama_barang' => $item->nama_barang,
                'kategori' => $item->kategoriBarang->nama_kategori,
                'kondisi' => $item->kondisi,
                'tgl_masuk' => $item->tgl_masuk,
                'keterangan' => $item->keterangan,
            ];
        });

        return $barang;
    }

    // public function drawings()
    // {
    //     $drawings = [];
    //     $images = [];
    //     $barang = Barang::latest()->get();

    //     foreach ($barang as $index => $item) {
    //         if (!$item->qr_code) continue;

    //         $drawing = new Drawing();
    //         $drawing->setName('QR Code');
    //         $drawing->setDescription('QR Code');
    //         $drawing->setPath(storage_path('app/public/' . $item->qr_code)); // path ke file QR
    //         $drawing->setHeight(50); // Sesuaikan tinggi gambar
    //         $drawing->setCoordinates('F' . ($index + 2)); // QR Code di kolom F, dimulai dari baris 2

    //         $drawings[] = $drawing;

    //         if (!$item->image) continue;
    //         $image = new Drawing();
    //         $image->setName('Image');
    //         $image->setDescription('Image');
    //         $image->setPath(storage_path('app/public/' . $item->image)); // path ke file gambar
    //         $image->setHeight(50); // Sesuaikan tinggi gambar
    //         $image->setCoordinates('G' . ($index + 2)); // Gambar di kolom G, dimulai dari baris 2

    //         $images[] = $image;
    //     }

    //     return array_merge($drawings, $images);
    // }

    public function headings(): array
    {
        $data = ['Kode Barang', 'Nama Barang', 'Kategori', 'Kondisi', 'Tanggal Masuk', 'Keterangan'];

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->applyFromArray([
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => '233876', // Ganti dengan kode warna yang diinginkan
                ],
            ],
            'font' => [
                'bold' => true,
                'color' => [
                    'rgb' => 'FFFFFF', // Font color (white in this example)
                ],
            ],
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $lastColumn = $event->sheet->getHighestColumn();
                $lastRow = $event->sheet->getHighestRow();

                $range = 'A1:' . $lastColumn . $lastRow;

                $event->sheet->getStyle($range)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_MEDIUM,
                            'color' => ['argb' => '#000000'],
                        ],
                    ],
                ]);
            }
        ];
    }
}
