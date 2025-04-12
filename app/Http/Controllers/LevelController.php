<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\LevelModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;


class LevelController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Daftar Level yang tersedia',
        ];

        $activeMenu = 'level';

        $levels = LevelModel::all();

        return view('level.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'levels' => $levels,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data level dalam bentuk JSON untuk DataTables
    // public function list(Request $request)
    // {
    //     $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');

    //     return DataTables::of($levels)
    //         ->addIndexColumn()
    //         ->addColumn('aksi', function ($level) {
    //             $btn = '<a href="' . url('/level/' . $level->level_id) . '" class="btn btn-info btn-sm">Detail</a> ';
    //             $btn .= '<a href="' . url('/level/' . $level->level_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
    //             $btn .= '<form class="d-inline-block" method="POST" action="' . url('/level/' . $level->level_id) . '">'
    //                 . csrf_field() . method_field('DELETE') .
    //                 '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button>
    //             </form>';

    //             return $btn;
    //         })
    //         ->rawColumns(['aksi'])
    //         ->make(true);
    // }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah level baru',
        ];

        $activeMenu = 'level';

        return view('level.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_kode' => 'required|string|max:10|unique:m_level,level_kode',
            'level_nama' => 'required|string|max:100'
        ]);

        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama
        ]);

        return redirect('/level')->with('success', 'Data level berhasil disimpan!');
    }

    public function show(string $id)
    {
        $breadcrumb = (object) [
            'title' => 'Detail Level',
            'list' => ['Home', 'Level', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail level',
        ];

        $activeMenu = 'level';

        $level = LevelModel::find($id);

        return view('level.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'level' => $level
        ]);
    }

    public function edit(string $id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit level',
        ];

        $activeMenu = 'level';

        $level = LevelModel::find($id);

        return view('level.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'level' => $level
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'level_kode' => 'required|string|max:10|unique:m_level,level_kode,' . $id . ',level_id',
            'level_nama' => 'required|string|max:100'
        ]);

        LevelModel::find($id)->update([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama
        ]);

        return redirect('/level')->with('success', 'Data level berhasil diubah!');
    }

    public function destroy(string $id)
    {
        $check = LevelModel::find($id);
        if (!$check) {
            return redirect('/level')->with('error', 'Data level tidak ditemukan');
        }

        try {
            LevelModel::destroy($id);
            return redirect('/level')->with('success', 'Data level berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/level')->with('error', 'Data level gagal dihapus karena masih terkait dengan data lain.');
        }
    }

    public function list(Request $request)
{
    $levels = LevelModel::select('level_id', 'level_kode', 'level_nama');

    // Filter berdasarkan level_id jika ada
    if ($request->level_id) {
        $levels->where('level_id', $request->level_id);
    }

    return DataTables::of($levels)
        ->addIndexColumn() // Menambahkan nomor urut otomatis
        ->addColumn('aksi', function ($level) {
            // Tombol Detail menggunakan modal AJAX
            $btn = '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/show_ajax') . '\')"
                        class="btn btn-info btn-sm">Detail</button> ';

            // Tombol Edit menggunakan modal AJAX
            $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/edit_ajax') . '\')"
                        class="btn btn-warning btn-sm">Edit</button> ';

            // Tombol Hapus menggunakan modal AJAX
            $btn .= '<button onclick="modalAction(\'' . url('/level/' . $level->level_id . '/delete_ajax') . '\')"
                        class="btn btn-danger btn-sm">Hapus</button> ';

            return $btn;
        })
        ->rawColumns(['aksi']) // Memberitahu DataTables bahwa kolom ini berisi HTML
        ->make(true);
}

    public function create_ajax()
    {
        return view('level.create_ajax');
    }

    public function edit_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.edit_ajax', compact('level'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'level_kode' => 'required|string|max:10|unique:m_level,level_kode',
                'level_nama' => 'required|string|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            LevelModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data level berhasil disimpan'
            ]);
        }

        return redirect('/level');
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'level_kode' => 'required|string|max:10|unique:m_level,level_kode,' . $id . ',level_id',
                'level_nama' => 'required|string|max:100'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $level = LevelModel::find($id);
            if (!$level) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            $level->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diperbarui'
            ]);
        }

        return redirect('/level');
    }

    public function confirm_ajax(string $id)
    {
        $level = LevelModel::find($id);
        return view('level.confirm_ajax', compact('level'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $level = LevelModel::find($id);

            if ($level) {
                $level->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return redirect('/level');
    }

    public function show_ajax($id)
    {
        $level = LevelModel::find($id);
        if (!$level) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        return view('level.show_ajax', compact('level'));
    }

    public function import()
    {
        return view('level.import'); // Pastikan path file view sesuai
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi file yang di-upload
            $rules = [
                'file_level' => ['required', 'mimes:xlsx', 'max:1024'] // Maksimum 1MB
            ];

            $validator = Validator::make($request->all(), $rules);

            // Jika validasi gagal
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            // Proses file yang di-upload
            $file = $request->file('file_level');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true); // Hanya membaca data, tidak membaca format
            $spreadsheet = $reader->load($file->getRealPath()); // Membaca file Excel
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true); // Mengubah sheet ke array
            $insert = [];

            // Pastikan ada lebih dari satu baris (header + data)
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) { // Mulai dari baris kedua (setelah header)
                        // Sesuaikan kolom dengan field yang ada pada tabel Level
                        $insert[] = [
                            'level_kode' => $value['A'], // Misalnya level_name ada di kolom A
                            'level_nama' => $value['B'], // Misalnya description ada di kolom B
                            'created_at' => now(),
                        ];
                    }
                }

                // Menyimpan data ke database (gunakan insertOrIgnore untuk menghindari duplikasi)
                if (count($insert) > 0) {
                    LevelModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }

        return redirect('/');
    }

    public function export_excel(){
        // Ambil data barang yang akan di-export
        $level = levelModel::select(
            'level_kode',
            'level_nama',
         )
         ->orderBy('level_id')
         ->get();

         // Load library Excel
         $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
         $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif

         // Set header kolom
         $sheet->setCellValue('A1', 'No');
         $sheet->setCellValue('B1', 'Kode Level');
         $sheet->setCellValue('C1', 'Nama Level');

         // Bold header
         $sheet->getStyle('A1:C1')->getFont()->setBold(true);

         $no = 1; // Nomor data dimulai dari 1
         $baris = 2; // Baris data dimulai dari baris ke-2

         foreach ($level as $key => $value) {
             $sheet->setCellValue('A' . $baris, $no);
             $sheet->setCellValue('B' . $baris, $value->level_kode);
             $sheet->setCellValue('C' . $baris, $value->level_nama);

             $baris++;
             $no++;
         }

         foreach (range('A', 'C') as $columnID) {
             $sheet->getColumnDimension($columnID)->setAutoSize(true); // Set auto size untuk kolom
         }

         $sheet->setTitle('Data Level'); // Set title sheet

         $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
         $filename = "Data level " . date('Y-m-d H:i:s') . ".xlsx";

         // Header untuk download file Excel
         header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
         header("Content-Disposition: attachment;filename=\"{$filename}\"");
         header("Cache-Control: max-age=0");
         header("Cache-Control: max-age=1");
         header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
         header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
         header("Cache-Control: cache, must-revalidate");
         header("Pragma: public");

         $writer->save('php://output');
         exit;
         // End function export_excel
     }

    public function export_pdf()
    {
        $level = LevelModel::select('level_kode',
            'level_nama',)
            ->orderBy('level_kode')
            ->get();

         // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('level.export_pdf', ['level' => $level]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption('isRemoteEnabled', true); // set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data Level ' . date('Y-m-d H:i:s') . '.pdf');
    }

}

// class LevelController extends Controller
// {

    // public function index() {
        //   DB::insert('insert into m_level(level_kode, level_nama,created_at) values(?,?,?)', ['cus', 'Pelanggan', now()]);
        //   return "insert data baru berhasil";

        // $row = DB:: update( 'update m_level set level_nama = ? where level_kode = ?', ['Customer', 'CUS']);
        // return 'Update data berhasil. Jumlah data yang diupdate:'.$row.'baris';

        // $row = DB::delete('delete from m_level where level_kode =?',['CUS']);
        // return 'Delete data berhasill. Jumlah data yang dihapus:'.$row.'baris';

    //     $data = DB::select('select * from m_level');
    //     return view('level', ['data' => $data]);

    // }
// }
