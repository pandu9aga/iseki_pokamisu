<?php

namespace App\Http\Controllers;

use App\Imports\DataImport;
use App\Models\ImportData;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;

class DataController extends Controller
{
    public function index()
    {
        $columns = [
            'no', 'instruksi', 'tipe_traktor', 'no_produksi',
            'sign', 'permasalahan', 'keterangan', 'jenis_penanganan',
            'pic_repair', 'kategori', 'team', 'pic'
        ];
        return view('pokamisu.index', compact('columns'));
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

        $rows = $worksheet->toArray();
        if (count($rows) < 2) {
            return back()->with('error', 'File Excel kosong atau hanya berisi header.');
        }

        $rawHeaders = $rows[0];
        $expected = [
            'no', 'instruksi', 'tipe_traktor', 'no_produksi',
            'sign', 'permasalahan', 'keterangan', 'jenis_penanganan',
            'pic_repair', 'kategori', 'team', 'pic'
        ];

        $colMap = $this->mapExcelHeaders($rawHeaders, $expected);

        $colLetters = 'ABCDEFGHIJKL';
        $colLetterMap = [];
        foreach ($expected as $i => $col) {
            $colLetterMap[$col] = $colLetters[$i] ?? '';
        }

        $inserted = 0;
        foreach ($rows as $rowIdx => $row) {
            if ($rowIdx === 0) continue;

            $data = [];
            foreach ($expected as $col) {
                $dbCol = str_replace(' ', '_', $col);
                $idx = $colMap[$col];
                $value = ($idx !== false && isset($row[$idx])) ? $row[$idx] : null;
                $data[$dbCol] = $value;

                $excelRow = $rowIdx + 1;
                $letter = $colLetterMap[$col];
                $color = '#000000';
                if ($letter && $value !== null && $value !== '') {
                    try {
                        $cell = $worksheet->getCell($letter . $excelRow);
                        $fontColor = $cell->getStyle()->getFont()->getColor();
                        if ($fontColor && $fontColor->getARGB() && $fontColor->getARGB() !== '00000000') {
                            $argb = $fontColor->getARGB();
                            $color = (strlen($argb) === 8) ? '#' . substr($argb, 2) : '#' . $argb;
                        }
                    } catch (\Exception $e) {}
                }
                $data[$dbCol . '_color'] = $color;
            }

            ImportData::create($data);
            $inserted++;
        }

        return redirect()->route('data.index')
            ->with('success', "Berhasil import $inserted baris data.");
    }

    protected function mapExcelHeaders(array $rawHeaders, array $expected): array
    {
        $map = [];
        $normalizedHeaders = [];
        foreach ($rawHeaders as $i => $h) {
            $normalizedHeaders[$i] = $this->normalizeHeader((string) $h);
        }

        foreach ($expected as $col) {
            $search = $this->normalizeHeader($col);
            $found = false;
            foreach ($normalizedHeaders as $i => $nh) {
                if ($nh === $search) {
                    $map[$col] = $i;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $map[$col] = false;
            }
        }

        return $map;
    }

    protected function normalizeHeader(string $header): string
    {
        $h = preg_replace('/[.\s_-]+/', '', $header);
        $h = preg_replace('/[^a-zA-Z0-9]/', '', $h);
        return strtolower($h);
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

        return DataTables::of($query)
            ->addColumn('checkbox', function ($row) {
                return '<input type="checkbox" class="row-checkbox" value="' . $row->id . '">';
            })
            ->addColumn('action', function ($row) {
                return '<button class="btn btn-sm btn-outline-danger delete-row" data-id="' . $row->id . '">
                            <i class="bi bi-trash"></i>
                        </button>';
            })
            ->editColumn('no', function ($row) {
                return '<span class="cell-content" data-id="' . $row->id . '" data-column="no" data-color="' . $row->no_color . '" style="cursor:pointer;color:' . $row->no_color . '">' . e($row->no) . '</span>';
            })
            ->editColumn('instruksi', function ($row) {
                return '<span class="cell-content" data-id="' . $row->id . '" data-column="instruksi" data-color="' . $row->instruksi_color . '" style="cursor:pointer;color:' . $row->instruksi_color . '">' . e($row->instruksi) . '</span>';
            })
            ->editColumn('tipe_traktor', function ($row) {
                return '<span class="cell-content" data-id="' . $row->id . '" data-column="tipe_traktor" data-color="' . $row->tipe_traktor_color . '" style="cursor:pointer;color:' . $row->tipe_traktor_color . '">' . e($row->tipe_traktor) . '</span>';
            })
            ->editColumn('no_produksi', function ($row) {
                return '<span class="cell-content" data-id="' . $row->id . '" data-column="no_produksi" data-color="' . $row->no_produksi_color . '" style="cursor:pointer;color:' . $row->no_produksi_color . '">' . e($row->no_produksi) . '</span>';
            })
            ->editColumn('sign', function ($row) {
                return '<span class="cell-content" data-id="' . $row->id . '" data-column="sign" data-color="' . $row->sign_color . '" style="cursor:pointer;color:' . $row->sign_color . '">' . e($row->sign) . '</span>';
            })
            ->editColumn('permasalahan', function ($row) {
                return '<span class="cell-content" data-id="' . $row->id . '" data-column="permasalahan" data-color="' . $row->permasalahan_color . '" style="cursor:pointer;color:' . $row->permasalahan_color . '">' . e($row->permasalahan) . '</span>';
            })
            ->editColumn('keterangan', function ($row) {
                return '<span class="cell-content" data-id="' . $row->id . '" data-column="keterangan" data-color="' . $row->keterangan_color . '" style="cursor:pointer;color:' . $row->keterangan_color . '">' . e($row->keterangan) . '</span>';
            })
            ->editColumn('jenis_penanganan', function ($row) {
                return '<span class="cell-content" data-id="' . $row->id . '" data-column="jenis_penanganan" data-color="' . $row->jenis_penanganan_color . '" style="cursor:pointer;color:' . $row->jenis_penanganan_color . '">' . e($row->jenis_penanganan) . '</span>';
            })
            ->editColumn('pic_repair', function ($row) {
                return '<span class="cell-content" data-id="' . $row->id . '" data-column="pic_repair" data-color="' . $row->pic_repair_color . '" style="cursor:pointer;color:' . $row->pic_repair_color . '">' . e($row->pic_repair) . '</span>';
            })
            ->editColumn('kategori', function ($row) {
                return '<span class="cell-content" data-id="' . $row->id . '" data-column="kategori" data-color="' . $row->kategori_color . '" style="cursor:pointer;color:' . $row->kategori_color . '">' . e($row->kategori) . '</span>';
            })
            ->editColumn('team', function ($row) {
                return '<span class="cell-content" data-id="' . $row->id . '" data-column="team" data-color="' . $row->team_color . '" style="cursor:pointer;color:' . $row->team_color . '">' . e($row->team) . '</span>';
            })
            ->editColumn('pic', function ($row) {
                return '<span class="cell-content" data-id="' . $row->id . '" data-column="pic" data-color="' . $row->pic_color . '" style="cursor:pointer;color:' . $row->pic_color . '">' . e($row->pic) . '</span>';
            })
            ->filterColumn('no', function ($query, $keyword) {
                $query->where('no', 'like', "%{$keyword}%");
            })
            ->filterColumn('instruksi', function ($query, $keyword) {
                $query->where('instruksi', 'like', "%{$keyword}%");
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
            ->rawColumns(['checkbox', 'action', 'no', 'instruksi', 'tipe_traktor', 'no_produksi', 'sign', 'permasalahan', 'keterangan', 'jenis_penanganan', 'pic_repair', 'kategori', 'team', 'pic'])
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
        $record->{$request->column} = $request->value;
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
}
