<?php

namespace App\Http\Controllers;

use App\Imports\DataImport;
use App\Models\ImportData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DataController extends Controller
{
    protected $kategoriList = [
        'lupa dirakit', 'pengencangan', 'check sheet', 'cek pen',
        'perakitan', 'penyetelan', 'cat', 'masking', 'part',
        'telat supply', 'telat request', 'lain-lain'
    ];

    protected $teamList = [
        'MC', 'QC', 'Line A', 'Line B', 'Transmisi', 'Engine',
        'Sub', 'Main', 'Inspeksi', 'Mower', 'DST'
    ];

    public function getPICList()
    {
        try {
            return DB::connection('mysql_rifa')
                ->table('employees')
                ->whereNotNull('nama')
                ->where('nama', '!=', '')
                ->orderBy('nik')
                ->pluck('nama')
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    protected $bgColorColumns = ['kategori'];

    private function isBgColorColumn($column)
    {
        return in_array($column, $this->bgColorColumns);
    }

    private function contrastTextColor($hex)
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        if (strlen($hex) < 6) {
            return '#000000';
        }
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));
        $luminance = 0.299 * $r + 0.587 * $g + 0.114 * $b;
        return $luminance > 140 ? '#000000' : '#ffffff';
    }

    private function cellStyle($column, $color)
    {
        $color = $color ?: '#000000';
        if ($this->isBgColorColumn($column)) {
            if ($color === '#000000') {
                return 'background-color:#ffffff;color:#000000';
            }
            return 'background-color:' . $color . ';color:' . $this->contrastTextColor($color);
        }
        return 'color:' . $color;
    }

    public function index()
    {
        $kategoriList = $this->kategoriList;
        $teamList = $this->teamList;
        $picList = $this->getPICList();
        return view('pokamisu.index', compact('kategoriList', 'teamList', 'picList'));
    }

    public function importForm()
    {
        return view('pokamisu.import');
    }

    public function importStore(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();

        $tanggal = null;
        try {
            $b7 = $worksheet->getCell('B7')->getValue();
            if ($b7 && preg_match('/\d{4}-\d{2}-\d{2}/', $b7, $m)) {
                $tanggal = $m[0];
            }
        } catch (\Exception $e) {}

        $rows = $worksheet->toArray();
        if (count($rows) < 9) {
            return back()->with('error', 'File Excel tidak memiliki data di baris 9 atau lebih.');
        }

        $cols = [
            1 => 'no',       // B
            2 => 'no_instruksi', // C
            3 => 'tipe_traktor', // D
            4 => 'no_produksi',  // E
            5 => 'sign',     // F
            6 => 'permasalahan', // G
            7 => 'keterangan', // H
            8 => 'jenis_penanganan', // I
            9 => 'pic_repair', // J
            10 => 'kategori', // K
            11 => 'team',     // L
        ];

        $colLetters = [1=>'B',2=>'C',3=>'D',4=>'E',5=>'F',6=>'G',7=>'H',8=>'I',9=>'J',10=>'K',11=>'L'];

        $bgColorColumns = ['kategori'];

        $inserted = 0;
        foreach ($rows as $rowIdx => $row) {
            if ($rowIdx < 8) continue;

            $data = ['tanggal' => $tanggal, 'tanggal_color' => null];

            foreach ($cols as $colIdx => $dbCol) {
                $value = isset($row[$colIdx]) ? $row[$colIdx] : null;
                if (is_string($value)) $value = trim($value);
                if ($value === '') $value = null;
                $data[$dbCol] = $value;

                $excelRow = $rowIdx + 1;
                $letter = $colLetters[$colIdx];
                $color = null;
                if ($letter && $value !== null) {
                    try {
                        $cell = $worksheet->getCell($letter . $excelRow);
                        if (in_array($dbCol, $bgColorColumns)) {
                            $fillColor = $cell->getStyle()->getFill()->getStartColor();
                            if ($fillColor && $fillColor->getARGB() && $fillColor->getARGB() !== '00000000') {
                                $argb = $fillColor->getARGB();
                                $color = (strlen($argb) === 8) ? '#' . substr($argb, 2) : '#' . $argb;
                            }
                        } else {
                            $fontColor = $cell->getStyle()->getFont()->getColor();
                            if ($fontColor && $fontColor->getARGB() && $fontColor->getARGB() !== '00000000') {
                                $argb = $fontColor->getARGB();
                                $color = (strlen($argb) === 8) ? '#' . substr($argb, 2) : '#' . $argb;
                            }
                        }
                    } catch (\Exception $e) {}
                }
                $data[$dbCol . '_color'] = $color;
            }

            if (array_filter(array_intersect_key($data, array_flip(['no','no_instruksi','tipe_traktor','no_produksi','sign','permasalahan','keterangan','jenis_penanganan','pic_repair','kategori','team'])), function($v) { return $v !== null; })) {
                ImportData::create($data);
                $inserted++;
            }
        }

        return redirect()->route('data.index')
            ->with('success', "Berhasil import $inserted baris data.");
    }

    public function dataTable(Request $request)
    {
        $query = ImportData::query();

        if ($request->has('colorFilters')) {
            foreach ($request->colorFilters as $col => $color) {
                $colorCol = $col . '_color';
                $query->where($colorCol, $color);
            }
        }

        if ($request->has('selectFilters')) {
            foreach ($request->selectFilters as $col => $val) {
                if ($val !== '') {
                    $query->where($col, $val);
                }
            }
        }

        return DataTables::of($query)
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-outline-danger delete-row" data-id="' . $row->id . '">
                            <i class="bi bi-trash"></i>
                        </button>';
            })
            ->addColumn('tanggal_display', function ($row) {
                $c = $row->tanggal_color ?? '#000000';
                return '<div class="cell-wrap"><span class="cell-content" data-id="' . $row->id . '" data-column="tanggal" data-color="' . $c . '" style="color:' . $c . '">' . e($row->tanggal) . '</span></div>';
            })
            ->editColumn('no', function ($row) {
                return '<div class="cell-wrap"><span class="cell-content" data-id="' . $row->id . '" data-column="no" data-color="' . $row->no_color . '" style="color:' . $row->no_color . '">' . e($row->no) . '</span></div>';
            })
            ->editColumn('no_instruksi', function ($row) {
                return '<div class="cell-wrap"><span class="cell-content" data-id="' . $row->id . '" data-column="no_instruksi" data-color="' . $row->no_instruksi_color . '" style="color:' . $row->no_instruksi_color . '">' . e($row->no_instruksi) . '</span></div>';
            })
            ->editColumn('tipe_traktor', function ($row) {
                return '<div class="cell-wrap"><span class="cell-content" data-id="' . $row->id . '" data-column="tipe_traktor" data-color="' . $row->tipe_traktor_color . '" style="color:' . $row->tipe_traktor_color . '">' . e($row->tipe_traktor) . '</span></div>';
            })
            ->editColumn('no_produksi', function ($row) {
                return '<div class="cell-wrap"><span class="cell-content" data-id="' . $row->id . '" data-column="no_produksi" data-color="' . $row->no_produksi_color . '" style="color:' . $row->no_produksi_color . '">' . e($row->no_produksi) . '</span></div>';
            })
            ->editColumn('sign', function ($row) {
                return '<div class="cell-wrap"><span class="cell-content" data-id="' . $row->id . '" data-column="sign" data-color="' . $row->sign_color . '" style="color:' . $row->sign_color . '">' . e($row->sign) . '</span></div>';
            })
            ->editColumn('permasalahan', function ($row) {
                return '<div class="cell-wrap"><span class="cell-content" data-id="' . $row->id . '" data-column="permasalahan" data-color="' . $row->permasalahan_color . '" style="color:' . $row->permasalahan_color . '">' . e($row->permasalahan) . '</span></div>';
            })
            ->editColumn('keterangan', function ($row) {
                return '<div class="cell-wrap"><span class="cell-content" data-id="' . $row->id . '" data-column="keterangan" data-color="' . $row->keterangan_color . '" style="color:' . $row->keterangan_color . '">' . e($row->keterangan) . '</span></div>';
            })
            ->editColumn('jenis_penanganan', function ($row) {
                return '<div class="cell-wrap"><span class="cell-content" data-id="' . $row->id . '" data-column="jenis_penanganan" data-color="' . $row->jenis_penanganan_color . '" style="color:' . $row->jenis_penanganan_color . '">' . e($row->jenis_penanganan) . '</span></div>';
            })
            ->editColumn('pic_repair', function ($row) {
                return '<div class="cell-wrap"><span class="cell-content" data-id="' . $row->id . '" data-column="pic_repair" data-color="' . $row->pic_repair_color . '" style="color:' . $row->pic_repair_color . '">' . e($row->pic_repair) . '</span></div>';
            })
            ->editColumn('kategori', function ($row) {
                $opts = ',' . implode(',', $this->kategoriList);
                return '<div class="cell-wrap"><span class="cell-content is-select" data-select="kategori" data-options="' . $opts . '" data-id="' . $row->id . '" data-column="kategori" data-color="' . $row->kategori_color . '" style="' . $this->cellStyle('kategori', $row->kategori_color) . '">' . e($row->kategori) . '</span></div>';
            })
            ->editColumn('team', function ($row) {
                $opts = ',' . implode(',', $this->teamList);
                return '<div class="cell-wrap"><span class="cell-content is-select" data-select="team" data-options="' . $opts . '" data-id="' . $row->id . '" data-column="team" data-color="' . $row->team_color . '" style="' . $this->cellStyle('team', $row->team_color) . '">' . e($row->team) . '</span></div>';
            })
            ->editColumn('pic', function ($row) {
                $opts = ',' . implode(',', $this->getPICList());
                return '<div class="cell-wrap"><span class="cell-content is-select" data-select="pic" data-options="' . $opts . '" data-id="' . $row->id . '" data-column="pic" data-color="' . $row->pic_color . '" style="' . $this->cellStyle('pic', $row->pic_color) . '">' . e($row->pic) . '</span></div>';
            })
            ->filterColumn('no', function ($query, $keyword) {
                $query->where('no', 'like', "%{$keyword}%");
            })
            ->filterColumn('no_instruksi', function ($query, $keyword) {
                $query->where('no_instruksi', 'like', "%{$keyword}%");
            })
            ->filterColumn('tipe_traktor', function ($query, $keyword) {
                $query->where('tipe_traktor', 'like', "%{$keyword}%");
            })
            ->filterColumn('no_produksi', function ($query, $keyword) {
                $query->where('no_produksi', 'like', "%{$keyword}%");
            })
            ->filterColumn('sign', function ($query, $keyword) {
                $query->where('sign', 'like', "%{$keyword}%");
            })
            ->filterColumn('permasalahan', function ($query, $keyword) {
                $query->where('permasalahan', 'like', "%{$keyword}%");
            })
            ->filterColumn('keterangan', function ($query, $keyword) {
                $query->where('keterangan', 'like', "%{$keyword}%");
            })
            ->filterColumn('jenis_penanganan', function ($query, $keyword) {
                $query->where('jenis_penanganan', 'like', "%{$keyword}%");
            })
            ->filterColumn('pic_repair', function ($query, $keyword) {
                $query->where('pic_repair', 'like', "%{$keyword}%");
            })
            ->filterColumn('kategori', function ($query, $keyword) {
                $query->where('kategori', 'like', "%{$keyword}%");
            })
            ->filterColumn('team', function ($query, $keyword) {
                $query->where('team', 'like', "%{$keyword}%");
            })
            ->filterColumn('pic', function ($query, $keyword) {
                $query->where('pic', 'like', "%{$keyword}%");
            })
            ->filterColumn('tanggal', function ($query, $keyword) {
                $query->where('tanggal', 'like', "%{$keyword}%");
            })
            ->rawColumns(['checkbox', 'action', 'tanggal_display', 'no', 'no_instruksi', 'tipe_traktor', 'no_produksi', 'sign', 'permasalahan', 'keterangan', 'jenis_penanganan', 'pic_repair', 'kategori', 'team', 'pic'])
            ->make(true);
    }

    public function getColors($column)
    {
        $colorCol = $column . '_color';
        $colors = ImportData::whereNotNull($colorCol)
            ->where($colorCol, '!=', '')
            ->distinct()
            ->pluck($colorCol);

        return response()->json($colors);
    }

    public function updateCellColor(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:pokamisu,id',
            'column' => 'required|string',
            'color' => 'required|string',
        ]);

        $record = ImportData::findOrFail($request->id);
        $colorColumn = $request->column . '_color';
        $record->$colorColumn = $request->color;
        $record->save();

        return response()->json(['success' => true]);
    }

    public function updateCellValue(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:pokamisu,id',
            'column' => 'required|string',
            'value' => 'nullable|string',
        ]);

        $record = ImportData::findOrFail($request->id);
        $val = $request->value;
        $record->{$request->column} = ($val === '') ? null : $val;
        $record->save();

        return response()->json(['success' => true]);
    }

    public function batchUpdateColor(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:pokamisu,id',
            'columns' => 'required|array',
            'columns.*' => 'string',
            'color' => 'required|string',
        ]);

        $records = ImportData::whereIn('id', $request->ids)->get();
        foreach ($records as $record) {
            foreach ($request->columns as $col) {
                $colorCol = $col . '_color';
                $record->$colorCol = $request->color;
            }
            $record->save();
        }

        return response()->json(['success' => true, 'updated' => count($records)]);
    }

    public function destroy($id)
    {
        $record = ImportData::findOrFail($id);
        $record->delete();

        return response()->json(['success' => true]);
    }

    public function batchDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:pokamisu,id',
        ]);

        $deleted = ImportData::whereIn('id', $request->ids)->delete();

        return response()->json(['success' => true, 'deleted' => $deleted]);
    }
}
