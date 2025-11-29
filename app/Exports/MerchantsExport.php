<?php

namespace App\Exports;

use App\Models\Merchant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MerchantsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Merchant::orderBy('id')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Merchant',
            'Kategori',
            'Nama PIC',
            'WA PIC',
            'Daerah',
            'Detail Alamat',
            'Latitude',
            'Longitude',
            'Link Google Maps',
            'Link Blanjapoin',
            'Logo Merchant',
        ];
    }

    /**
     * @param Merchant $merchant
     * @return array
     */
    public function map($merchant): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $merchant->nama_merchant ?? '-',
            $merchant->kategori ?? '-',
            $merchant->nama_pic ?? '-',
            $merchant->wa_pic ?? '-',
            $merchant->daerah ?? '-',
            $merchant->detail_daerah ?? '-',
            $merchant->lat ?? '-',
            $merchant->long ?? '-',
            $merchant->link_gmap ?? '-',
            $merchant->link_blanjapoin ?? '-',
            $merchant->logo_merchant ? asset('storage/' . $merchant->logo_merchant) : '-',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
