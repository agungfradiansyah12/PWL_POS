<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;


class BarangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];

        $page = (object) [
            'title' => 'Daftar Barang yang tersedia',
        ];

        $activeMenu = 'barang';

        $kategori = KategoriModel::all();

        return view('barang.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data barang dalam bentuk JSON untuk DataTables
    // public function list(Request $request)
    // {
    //     $barang = BarangModel::select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
    //         ->with('kategori');

    //     // Filter data barang berdasarkan kategori_id
    //     if ($request->kategori_id) {
    //         $barang->where('kategori_id', $request->kategori_id);
    //     }

    //     return DataTables::of($barang)
    //         ->addIndexColumn()
    //         ->addColumn('kategori_nama', function ($barang) {
    //             return $barang->kategori->kategori_nama ?? '-';
    //         })
    //         ->addColumn('aksi', function ($barang) {
    //             $btn = '<a href="' . url('/barang/' . $barang->barang_id) . '" class="btn btn-info btn-sm">Detail</a> ';
    //             $btn .= '<a href="' . url('/barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
    //             $btn .= '<form class="d-inline-block" method="POST" action="' . url('/barang/' . $barang->barang_id) . '">'
    //                 . csrf_field() . method_field('DELETE') .
    //                 '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus barang ini?\');">Hapus</button>
    //             </form>';

    //             return $btn;
    //         })
    //         ->rawColumns(['aksi'])
    //         ->make(true);
    // }


    public function list(Request $request)
    {
        $barang = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'kategori_id', 'harga_beli', 'harga_jual')
            ->with('kategori'); // Pastikan relasi kategori sudah terdefinisi

        // Filter data barang berdasarkan kategori_id (jika ada)
        if ($request->kategori_id) {
            $barang->where('kategori_id', $request->kategori_id);
        }

        return DataTables::of($barang)
            ->addIndexColumn() // Menambahkan kolom index / nomor urut
            ->addColumn('kategori_nama', function ($barang) {
                return $barang->kategori ? $barang->kategori->kategori_nama : 'Tidak ada kategori';
            }) // Menambahkan kolom kategori_nama
            ->addColumn('aksi', function ($barang) {
                // Tombol Detail menggunakan modal AJAX
                $btn = '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/show_ajax') . '\')"
                            class="btn btn-info btn-sm">Detail</button> ';

                // Tombol Edit menggunakan modal AJAX
                $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/edit_ajax') . '\')"
                            class="btn btn-warning btn-sm">Edit</button> ';

                // Tombol Hapus menggunakan modal AJAX
                $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/delete_ajax') . '\')"
                            class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi mengandung HTML
            ->make(true);
    }


    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah barang baru',
        ];

        $kategori = KategoriModel::all();
        $activeMenu = 'barang';

        return view('barang.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'kategori' => $kategori
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori_id'  => 'required|integer|exists:m_kategori,kategori_id',
            'barang_kode'  => 'required|string|max:10|unique:m_barang,barang_kode',
            'barang_nama'  => 'required|string|max:100',
            'harga_beli'   => 'required|integer|min:0',
            'harga_jual'   => 'required|integer|min:0',
        ]);

        BarangModel::create([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli'  => $request->harga_beli,
            'harga_jual'  => $request->harga_jual,
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil disimpan!');
    }

    public function show(string $id)
    {
        $breadcrumb = (object) [
            'title' => 'Detail Barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail barang',
        ];

        $activeMenu = 'barang';

        $barang = BarangModel::with('kategori')->find($id);

        return view('barang.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'barang' => $barang
        ]);
    }

    public function edit(string $id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit Barang',
            'list' => ['Home', 'Barang', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit barang',
        ];

        $activeMenu = 'barang';

        $barang = BarangModel::find($id);
        $kategori = KategoriModel::all();

        return view('barang.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'barang' => $barang,
            'kategori' => $kategori
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'kategori_id'  => 'required|integer|exists:m_kategori,kategori_id',
            'barang_kode'  => 'required|string|max:10|unique:m_barang,barang_kode,' . $id . ',barang_id',
            'barang_nama'  => 'required|string|max:100',
            'harga_beli'   => 'required|integer|min:0',
            'harga_jual'   => 'required|integer|min:0',
        ]);

        BarangModel::find($id)->update([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli'  => $request->harga_beli,
            'harga_jual'  => $request->harga_jual,
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil diperbarui');
    }

    public function destroy(string $id)
    {
        $barang = BarangModel::find($id);
        if (!$barang) {
            return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
        }

        try {
            BarangModel::destroy($id);
            return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terkait dengan tabel lain');
        }
    }

    public function create_ajax(){
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get(); // Mengambil data kategori
        return view('barang.create_ajax')->with('kategori', $kategori);
    }

    // Menyimpan data barang dengan AJAX
    public function store_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'kategori_id' => 'required|exists:m_kategori,kategori_id',
                'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode',
                'barang_nama' => 'required|string|max:100',
                'harga_beli' => 'required|integer|min:0',
                'harga_jual' => 'required|integer|min:0'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(),
                ]);
            }

            // Menyimpan data barang baru ke dalam database
            BarangModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data barang berhasil disimpan'
            ]);
        }

        return redirect('/barang');
    }


    public function edit_ajax(string $id) {
        $barang = BarangModel::find($id);
        if (!$barang) {
            return response()->json([
                'status' => false,
                'message' => 'Data barang tidak ditemukan'
            ]);
        }
        return view('barang.edit_ajax', compact('barang'));
    }


    public function update_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'kategori_id' => 'required|exists:m_kategori,kategori_id',
            'barang_kode' => 'required|string|max:10|unique:m_barang,barang_kode,' . $id . ',barang_id',
            'barang_nama' => 'required|string|max:100',
            'harga_beli' => 'required|integer|min:0',
            'harga_jual' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal.',
                'msgField' => $validator->errors()
            ]);
        }

        // Cek apakah barang dengan ID tersebut ada di database
        $barang = BarangModel::find($id);
        if (!$barang) {
            return response()->json([
                'status' => false,
                'message' => 'Data barang tidak ditemukan'
            ]);
        }

        // Update data barang secara selektif
        $barang->update([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data barang berhasil diperbarui'
        ]);
    }

    return redirect('/');
}


    // Menampilkan konfirmasi untuk menghapus barang
    public function confirm_ajax($id)
    {
        $barang = BarangModel::find($id);
        if (!$barang) {
            return response()->json([
                'status' => false,
                'message' => 'Data barang tidak ditemukan'
            ]);
        }

        return view('barang.confirm_ajax', ['barang' => $barang]);
    }

    // Menghapus data barang dengan AJAX
    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $barang = BarangModel::find($id);

            if ($barang) {
                $barang->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data barang berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data barang tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }

    // Menampilkan detail barang dengan AJAX
    public function show_ajax($id)
    {
        $barang = BarangModel::with('kategori')->find($id);

        if (!$barang) {
            return response()->json([
                'status' => false,
                'message' => 'Data barang tidak ditemukan'
            ]);
        }

        return view('barang.show_ajax', compact('barang'));
    }
}