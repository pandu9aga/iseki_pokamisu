<?php

namespace App\Exports;

use App\Models\ImportData;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DataExport implements FromCollection, WithHeadings, WithMapping
{
    protected Collection $rows;

    public function __construct(Collection $rows)
    {
        $this->rows = $rows;
    }

    public function collection()
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'Tanggal', 'No', 'No Instruksi', 'Tipe Traktor', 'No Produksi',
            'Sign', 'Permasalahan', 'Keterangan', 'Jenis Penanganan',
            'PIC Repair', 'Kategori', 'Team', 'PIC',
        ];
    }

    public function map($row): array
    {
        return [
            $row->tanggal,
            $row->no,
            $row->no_instruksi,
            $row->tipe_traktor,
            $row->no_produksi,
            $row->sign,
            $row->permasalahan,
            $row->keterangan,
            $row->jenis_penanganan,
            $row->pic_repair,
            $row->kategori,
            $row->team,
            $row->pic,
        ];
    }
}
