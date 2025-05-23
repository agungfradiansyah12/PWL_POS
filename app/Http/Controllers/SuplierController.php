<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuplierModel;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;



class SuplierController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Suplier',
            'list' => ['Home', 'Suplier']
        ];

        $page = (object) [
            'title' => 'Daftar Suplier yang tersedia',
        ];

        $activeMenu = 'suplier';

        $suplier = SuplierModel::all();

        return view('suplier.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'suplier' => $suplier,
            'activeMenu' => $activeMenu
        ]);
    }

    // public function list(Request $request)
    // {
    //     $suplier = SuplierModel::select('suplier_id', 'nama_suplier', 'alamat_suplier', 'telepon_suplier');

    //     return DataTables::of($suplier)
    //         ->addIndexColumn()
    //         ->addColumn('aksi', function ($row) {
    //             $btn = '<a href="' . url('/suplier/' . $row->suplier_id) . '" class="btn btn-info btn-sm">Detail</a> ';
    //             $btn .= '<a href="' . url('/suplier/' . $row->suplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
    //             $btn .= '<form class="d-inline-block" method="POST" action="' . url('/suplier/' . $row->suplier_id) . '">'
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
            'title' => 'Tambah Suplier',
            'list' => ['Home', 'Suplier', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah suplier baru',
        ];

        $activeMenu = 'suplier';

        return view('suplier.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_suplier' => 'required|string|max:100',
            'alamat_suplier' => 'nullable|string|max:255',
            'telepon_suplier' => 'nullable|string|max:20'
        ]);

        SuplierModel::create([
            'nama_suplier' => $request->nama_suplier,
            'alamat_suplier' => $request->alamat_suplier,
            'telepon_suplier' => $request->telepon_suplier
        ]);

        return redirect('/suplier')->with('success', 'Data suplier berhasil disimpan!');
    }

    public function show(string $id)
    {
        $breadcrumb = (object) [
            'title' => 'Detail Suplier',
            'list' => ['Home', 'Suplier', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail suplier',
        ];

        $activeMenu = 'suplier';

        $suplier = SuplierModel::find($id);

        return view('suplier.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'suplier' => $suplier
        ]);
    }

    public function edit(string $id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit Suplier',
            'list' => ['Home', 'Suplier', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Suplier',
        ];

        $activeMenu = 'suplier'; // untuk set menu yang sedang aktif

        $suplier = SuplierModel::find($id);

        if (!$suplier) {
            return redirect('suplier')->with('error', 'Data suplier tidak ditemukan.');
        }

        return view('suplier.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'suplier' => $suplier
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_suplier'    => 'required|string|max:100',
            'alamat_suplier'  => 'required|string|max:255',
            'telepon_suplier' => 'required|string|max:15|unique:m_suplier,telepon_suplier,' . $id . ',suplier_id'
        ]);

        $suplier = SuplierModel::find($id);

        if (!$suplier) {
            return redirect('suplier')->with('error', 'Data suplier tidak ditemukan.');
        }

        $suplier->update([
            'nama_suplier'    => $request->nama_suplier,
            'alamat_suplier'  => $request->alamat_suplier,
            'telepon_suplier' => $request->telepon_suplier
        ]);

        return redirect('suplier')->with('success', 'Data suplier berhasil diperbarui.');
    }


    public function destroy(string $id)
    {
        $check = SuplierModel::find($id);
        if (!$check) {
            return redirect('/suplier')->with('error', 'Data suplier tidak ditemukan');
        }

        try {
            SuplierModel::destroy($id);
            return redirect('/suplier')->with('success', 'Data suplier berhasil dihapus!');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/suplier')->with('error', 'Data suplier gagal dihapus karena masih terkait dengan data lain.');
        }
    }

    public function list(Request $request)
    {
        $m_suplier = SuplierModel::select('suplier_id', 'nama_suplier', 'alamat_suplier', 'telepon_suplier');

        // Filter berdasarkan nama_suplier jika ada
        if ($request->suplier_id) {
            $m_suplier->where('suplier_id', $request->suplier_id );
        }

        // if ($request->kategori_id) {
        //     $barang->where('kategori_id', $request->kategori_id);
        // }

        return DataTables::of($m_suplier)
            ->addIndexColumn() // Menambahkan nomor urut otomatis
            ->addColumn('aksi', function ($suplier) {
                // Tombol Detail menggunakan modal AJAX
                $btn = '<button onclick="modalAction(\'' . url('/suplier/' . $suplier->suplier_id . '/show_ajax') . '\')"
                            class="btn btn-info btn-sm">Detail</button> ';

                // Tombol Edit menggunakan modal AJAX
                $btn .= '<button onclick="modalAction(\'' . url('/suplier/' . $suplier->suplier_id . '/edit_ajax') . '\')"
                            class="btn btn-warning btn-sm">Edit</button> ';

                // Tombol Hapus menggunakan modal AJAX
                $btn .= '<button onclick="modalAction(\'' . url('/suplier/' . $suplier->suplier_id . '/delete_ajax') . '\')"
                            class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu DataTables bahwa kolom ini berisi HTML
            ->make(true);
    }

    public function create_ajax()
    {
        return view('suplier.create_ajax');
    }

    public function edit_ajax(string $id)
    {
        $suplier = SuplierModel::find($id);
        return view('suplier.edit_ajax', compact('suplier'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $validator = Validator::make($request->all(), [
                'nama_suplier' => 'required|string|max:100|unique:m_suplier,nama_suplier',
                'alamat_suplier' => 'required|string|max:255',
                'telepon_suplier' => 'required|string|max:20'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            SuplierModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data suplier berhasil disimpan'
            ]);
        }

        return redirect('/suplier');
    }

    // Memperbarui data suplier dengan AJAX
    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'nama_suplier' => 'required|string|max:100',
                'alamat_suplier' => 'nullable|string|max:255',
                'telepon_suplier' => 'nullable|string|max:20'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            // Cari data supplier berdasarkan ID
            $suplier = SuplierModel::find($id);
            if (!$suplier) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            // Update data supplier
            $suplier->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diperbarui'
            ]);
        }

        return redirect('/suplier');
    }

    public function confirm_ajax(string $id)
    {
        $suplier = SuplierModel::find($id);
        return view('suplier.confirm_ajax', compact('suplier'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $suplier = SuplierModel::find($id);

            if ($suplier) {
                $suplier->delete();
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

        return redirect('/suplier');
    }

    public function show_ajax($id)
    {
        $suplier = SuplierModel::find($id);
        if (!$suplier) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        return view('suplier.show_ajax', compact('suplier'));
    }

    public function import()
{
    return view('suplier.import'); // Menampilkan view import untuk Suplier
}

public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'file_suplier' => ['required', 'mimes:xlsx', 'max:1024']
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_suplier');
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, false, true, true);
        $insert = [];

        if (count($data) > 1) { // Mengecek apakah ada data di file Excel
            foreach ($data as $baris => $value) {
                if ($baris > 1) {
                    $insert[] = [
                        'nama_suplier' => $value['A'],
                        'alamat_suplier' => $value['B'],
                        'telepon_suplier' => $value['C'],
                        'created_at' => now(),
                    ];
                }
            }

            if (count($insert) > 0) {
                SuplierModel::insertOrIgnore($insert); // Menyisipkan data ke database tanpa duplikasi
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

    return redirect('/'); // Jika bukan request Ajax, redirect ke halaman utama
}

public function export_excel(){
    // Ambil data barang yang akan di-export
    $suplier = SuplierModel::select(
        'nama_suplier',
        'alamat_suplier',
        'telepon_suplier',
 )
     ->orderBy('suplier_id')
     ->get();

     $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
     $sheet = $spreadsheet->getActiveSheet(); // Ambil sheet yang aktif

     $sheet->setCellValue('A1', 'No');
     $sheet->setCellValue('B1', 'Nama Suplier');
     $sheet->setCellValue('C1', 'Alamat Suplier');
     $sheet->setCellValue('D1', 'Telepon Suplier');

     $sheet->getStyle('A1:F1')->getFont()->setBold(true);

     $no = 1; // Nomor data dimulai dari 1
     $baris = 2; // Baris data dimulai dari baris ke-2

     foreach ($suplier as $key => $value) {
         $sheet->setCellValue('A' . $baris, $no);
         $sheet->setCellValue('B' . $baris, $value->nama_suplier);
         $sheet->setCellValue('C' . $baris, $value->alamat_suplier);
         $sheet->setCellValue('D' . $baris, $value->telepon_suplier);

         $baris++;
         $no++;
     }

     foreach (range('A', 'D') as $columnID) {
         $sheet->getColumnDimension($columnID)->setAutoSize(true); // Set auto size untuk kolom
     }

     $sheet->setTitle('Data Suplier'); // Set title sheet

     $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
     $filename = "Data Suplier " . date('Y-m-d H:i:s') . ".xlsx";

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
     $suplier = SuplierModel::select('nama_suplier',
        'alamat_suplier',
        'telepon_suplier')
         ->orderBy('nama_suplier')
         ->get();

     // use Barryvdh\DomPDF\Facade\Pdf;
     $pdf = Pdf::loadView('suplier.export_pdf', ['suplier' => $suplier]);
     $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
     $pdf->setOption('isRemoteEnabled', true); // set true jika ada gambar dari url
     $pdf->render();

     return $pdf->stream('Data Suplier ' . date('Y-m-d H:i:s') . '.pdf');
 }

}
