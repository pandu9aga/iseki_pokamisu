<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class DataExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected Collection $rows;

    protected array $colMap = [
        0 => 'tanggal', 1 => 'no', 2 => 'no_instruksi', 3 => 'tipe_traktor',
        4 => 'no_produksi', 5 => 'sign', 6 => 'permasalahan', 7 => 'keterangan',
        8 => 'jenis_penanganan', 9 => 'pic_repair', 10 => 'kategori',
        11 => 'team', 12 => 'pic',
    ];

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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $lastCol = chr(65 + count($this->colMap) - 1);
                $lastRow = $this->rows->count() + 1;

                foreach (range('A', $lastCol) as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                $sheet->setAutoFilter('A1:' . $lastCol . $lastRow);
                $sheet->getStyle('A1:' . $lastCol . '1')->getFont()->setBold(true);

                $contrastText = function ($hex) {
                    $hex = ltrim($hex, '#');
                    if (strlen($hex) === 3) {
                        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
                    }
                    if (strlen($hex) < 6) return 'FF000000';
                    $r = hexdec(substr($hex, 0, 2));
                    $g = hexdec(substr($hex, 2, 2));
                    $b = hexdec(substr($hex, 4, 2));
                    $lum = 0.299 * $r + 0.587 * $g + 0.114 * $b;
                    return $lum > 140 ? 'FF000000' : 'FFFFFFFF';
                };

                $rowIdx = 2;
                foreach ($this->rows as $row) {
                    foreach ($this->colMap as $colIdx => $col) {
                        $colLetter = chr(65 + $colIdx);
                        $colorField = $col . '_color';
                        $color = $row->$colorField ?? null;

                        if (!$color || $color === '#000000') continue;

                        $argb = 'FF' . ltrim($color, '#');

                        if ($col === 'kategori') {
                            $sheet->getStyle($colLetter . $rowIdx)->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setARGB($argb);
                            $sheet->getStyle($colLetter . $rowIdx)->getFont()
                                ->getColor()->setARGB($contrastText($color));
                        } else {
                            $sheet->getStyle($colLetter . $rowIdx)->getFont()
                                ->getColor()->setARGB($argb);
                        }
                    }
                    $rowIdx++;
                }
            },
        ];
    }
}
