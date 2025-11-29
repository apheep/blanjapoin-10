<?php

namespace App\Exports;

use App\Models\Keyword;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MerchantKeywordsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $merchantId;
    protected $merchantName;

    public function __construct($merchantId, $merchantName = null)
    {
        $this->merchantId = $merchantId;
        $this->merchantName = $merchantName;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Keyword::with('merchant')
            ->where('merchant_key', $this->merchantId)
            ->orderBy('id')
            ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'Nama Produk',
            'Keyword ID',
            'CTA Link',
            'Redeem',
            'Diskon',
            'SKB',
            'Stock',
            'TRX',
            'Sisa Stock',
            'Start Date',
            'End Date',
            'Status',
            'Image',
        ];
    }

    /**
     * @param Keyword $keyword
     * @return array
     */
    public function map($keyword): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $keyword->nama_produk ?? '-',
            $keyword->keyword_id ?? '-',
            $keyword->cta_link ?? '-',
            $keyword->redeem ?? '-',
            $keyword->diskon ?? '-',
            $keyword->skb ?? '-',
            $keyword->stock ?? '-',
            $keyword->trx ?? '-',
            $keyword->sisa_stock ?? '-',
            $keyword->start_date ?? '-',
            $keyword->end_date ?? '-',
            ucfirst($keyword->status ?? 'pending'),
            $keyword->image ? asset('storage/' . $keyword->image) : '-',
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
