<?php

namespace App\Imports;

use App\Models\ImportData;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class DataImport implements ToModel, WithHeadingRow
{
    protected $worksheet;

    public function __construct($worksheet)
    {
        $this->worksheet = $worksheet;
    }

    public function model(array $row)
    {
        $columns = [
            'no', 'instruksi', 'tipe_traktor', 'no_produksi',
            'sign', 'permasalahan', 'keterangan', 'jenis_penanganan',
            'pic_repair', 'kategori', 'team', 'pic'
        ];

        $data = [];

        foreach ($columns as $col) {
            $dbCol = str_replace(' ', '_', $col);
            $value = $row[$col] ?? $row[str_replace('_', ' ', $col)] ?? null;

            if (is_float($value) && $value > 1) {
                try {
                    $value = Date::excelToDateTimeObject($value)->format('Y-m-d');
                } catch (\Exception $e) {}
            }

            $data[$dbCol] = $value ?? '';
            $data[$dbCol . '_color'] = $this->getCellColor($col);
        }

        return new ImportData($data);
    }

    protected function getCellColor($column)
    {
        if (!$this->worksheet) {
            return '#000000';
        }

        $rowIndex = $this->getCurrentRowIndex();
        $colIndex = $this->getColumnIndex($column);

        if (!$colIndex) {
            return '#000000';
        }

        try {
            $cell = $this->worksheet->getCell($colIndex . $rowIndex);
            $color = $cell->getStyle()->getFont()->getColor();
            if ($color && $color->getARGB() && $color->getARGB() !== '00000000') {
                $argb = $color->getARGB();
                if (strlen($argb) === 8) {
                    return '#' . substr($argb, 2);
                }
                return '#' . $argb;
            }
        } catch (\Exception $e) {}

        return '#000000';
    }

    protected function getCurrentRowIndex()
    {
        return $this->rowIndex ?? 1;
    }

    public function setRowIndex($index)
    {
        $this->rowIndex = $index;
        return $this;
    }

    protected function getColumnIndex($column)
    {
        $map = [
            'no' => 'A',
            'instruksi' => 'B',
            'tipe_traktor' => 'C',
            'no_produksi' => 'D',
            'sign' => 'E',
            'permasalahan' => 'F',
            'keterangan' => 'G',
            'jenis_penanganan' => 'H',
            'pic_repair' => 'I',
            'kategori' => 'J',
            'team' => 'K',
            'pic' => 'L',
        ];

        return $map[$column] ?? null;
    }
}
