<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\LevelModel;
use App\Models\UseerModel;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(){
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar User yang terdaftar',
        ];

        $activeMenu = 'user';

        $level = LevelModel::all();

        return view('user.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    // // Ambil data user dalam bentuk json untuk datatables
    // public function list(Request $request)
    // {
    // $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
    //     ->with('level');

    // //Filter data user berdasarkan level_id
    // if($request->level_id) {
    //     $users->where('level_id', $request->level_id);
    // }

    // return DataTables::of($users)
    //     // Menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
    //     ->addIndexColumn()
    //     ->addColumn('aksi', function ($user) {
    //         // Menambahkan kolom aksi
    //         $btn = '<a href="' . url('/user/' . $user->user_id) . '" class="btn btn-info btn-sm">Detail</a> ';
    //         $btn .= '<a href="' . url('/user/' . $user->user_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
    //         $btn .= '<form class="d-inline-block" method="POST" action="' . url('/user/' . $user->user_id) . '">'
    //             . csrf_field() . method_field('DELETE') .
    //             '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button>
    //         </form>';

    //         return $btn;
    //     })
    //     ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi mengandung HTML
    //     ->make(true);
    // }

    //praktikum ajax
    // Ambil data user dalam bentuk JSON untuk DataTables
    public function list(Request $request)
    {
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
            ->with('level');

        // Filter data user berdasarkan level_id (jika ada)
        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users)
            ->addIndexColumn() // Menambahkan kolom index / nomor urut
            ->addColumn('aksi', function ($user) {
                // Tombol Detail menggunakan modal AJAX
                $btn = '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/show_ajax') . '\')"
                            class="btn btn-info btn-sm">Detail</button> ';

                // Tombol Edit menggunakan modal AJAX
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/edit_ajax') . '\')"
                            class="btn btn-warning btn-sm">Edit</button> ';

                // Tombol Hapus menggunakan modal AJAX
                $btn .= '<button onclick="modalAction(\'' . url('/user/' . $user->user_id . '/delete_ajax') . '\')"
                            class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn;
            })
            ->rawColumns(['aksi']) // Memberitahu bahwa kolom aksi mengandung HTML
            ->make(true);
    }


    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah User',
            'list' => ['Home', 'User', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah user baru',
        ];

        $level = LevelModel::all(); //ambil data level untuk ditampilkan di form
        $activeMenu = 'user'; // set menu yang sedang aktif

        return view('user.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'level' => $level]);
    }

    public function store(Request $request)
    {
        $request->validate([
            //username harus diisi, berupa string, dan minimal 3 karakter, dan harus unik di tabel m_user kolom username
            'username' => 'required|string|min:3|unique:m_user,username',
            'nama' => 'required|string|max:100', //nama harus diisi, berupa string, dan maksimal 100 karakter
            'password' => 'required|min:5', //password harus diisi, berupa string, dan minimal 5 karakter
            'level_id' => 'required|integer', //level_id harus diisi, berupa integer
        ]);

        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => bcrypt($request->password), //password dienkripsi
            'level_id' => $request->level_id,
        ]);

        return redirect('/user')->with('success', 'Data user berhasil disimpan!');
    }

    public function show(string $id)
    {
        $breadcrumb = (object) [
            'title' => 'Detail User',
            'list' => ['Home', 'User', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail user',
        ];

        $activeMenu = 'user'; // untuk set menu yang sedang aktif

        $user = UserModel::with('level')->find($id);

        return view('user.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'user' => $user
        ]);
    }

    public function edit(string $id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit User',
            'list' => ['Home', 'User', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit user',
        ];

        $activeMenu = 'user'; // untuk set menu yang sedang aktif

        $user = UserModel::find($id);
        $level = LevelModel::all();

        return view('user.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'user' => $user, 'level' => $level]);
    }

    // Menampilkan halaman form edit user menggunakan Ajax
    public function edit_ajax(string $id)
    {
        $user = UserModel::find($id);
        $level = LevelModel::select('level_id', 'level_nama')->get();

        return view('user.edit_ajax', [
            'user' => $user,
            'level' => $level
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            // username harus diisi, berupa string, minimal 3 karakter,
            // dan bernilai unik di tabel m_user kolom username kecuali untuk user dengan id yang sedang diedit
            'username'  => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id',
            'nama'      => 'required|string|max:100', // nama harus diisi, berupa string, dan maksimal 100 karakter
            'password'  => 'nullable|min:5',          // password bisa diisi (minimal 5 karakter) dan bisa tidak diisi
            'level_id'  => 'required|integer'         // level_id harus diisi dan berupa angka
        ]);

        UserModel::find($id)->update([
            'username' => $request->username,
            'nama'     => $request->nama,
            'password' => $request->password ? bcrypt($request->password) : UserModel::find($id)->password,
            'level_id' => $request->level_id
        ]);

        return redirect('/user')->with('success', 'Data user berhasil diubah');
    }

    public function destroy(string $id)
    {
        $check = UserModel::find($id);
        if (!$check) { // untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
            return redirect('/user')->with('error', 'Data user tidak ditemukan');
        }

        try {
            UserModel::destroy($id); // Hapus data level

            return redirect('/user')->with('success', 'Data user berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {

            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax(){
        $level = LevelModel::select('level_id','level_nama')->get();

        return view('user.create_ajax')->with('level', $level);
    }


    public function store_ajax(Request $request) {
        // Cek apakah request berupa AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|string|min:3|unique:m_user,username',
                'nama' => 'required|string|max:100',
                'password' => 'required|min:6'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }

            // Simpan data ke database
            UserModel::create([
                'level_id' => $request->level_id,
                'username' => $request->username,
                'nama' => $request->nama,
                'password' => bcrypt($request->password) // Mengenkripsi password
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }

        // Jika bukan request AJAX, redirect ke halaman utama
        return redirect('/user');
    }

    public function update_ajax(Request $request, $id)
    {
        // Cek apakah request berasal dari AJAX atau meminta JSON response
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_id' => 'required|integer',
                'username' => 'required|max:20|unique:m_user,username,' . $id . ',user_id',
                'nama' => 'required|max:100',
                'password' => 'nullable|min:6|max:20'
            ];

            // Validasi input
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // Menunjukkan field mana yang error
                ]);
            }

            // Cek apakah user dengan ID tersebut ada di database
            $user = UserModel::find($id);
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }

            // Jika password tidak diisi, hapus dari request agar tidak ikut diperbarui
            if (!$request->filled('password')) {
                $request->request->remove('password');
            }

            // Update data user
            $user->update($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diperbarui'
            ]);
        }

        // Jika bukan request AJAX, redirect ke halaman utama
        return redirect('/');
    }

    public function confirm_ajax(string $id)
    {
        $user = UserModel::find($id);
        return view('user.confirm_ajax', ['user' => $user]);
    }


    public function delete_ajax(Request $request, $id)
    {
        // Cek apakah request berasal dari AJAX
        if ($request->ajax() || $request->wantsJson()) {
            $user = UserModel::find($id);

            if ($user) {
                $user->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }

        return redirect('/');
    }

    public function show_ajax($id)
{
    $user = UserModel::with('level')->find($id);

    if (!$user) {
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan.'
        ]);
    }

    return view('user.show_ajax', compact('user'));
}

public function import()
{
    return view('user.import'); // Pastikan path file view sesuai
}

public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        // Validasi file yang di-upload
        $rules = [
            'file_user' => ['required', 'mimes:xlsx', 'max:1024'] // Maksimum 1MB
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
        $file = $request->file('file_user');
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
                    // Sesuaikan kolom dengan field yang ada pada tabel User
                    $insert[] = [
                        'level_id' => $value['A'], // Kolom A = level_id
                        'username' => $value['B'], // Kolom B = username
                        'nama' => $value['C'],     // Kolom C = nama
                        'password' => bcrypt($value['D']), // Kolom D = password, dienkripsi
                        'created_at' => now(),
                    ];
                }
            }

            // Menyimpan data ke database
            if (count($insert) > 0) {
                UserModel::insertOrIgnore($insert); // Menggunakan insertOrIgnore untuk menghindari duplikasi
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



}

//praktikum sebeumnya 4 atau 3
// class UserController extends Controller
// {
    // public function index(){
        // $user = UserModel::all();
        // return view('user', ['data' => $user]);

        // $data = [
        //     'username' => 'customer-1',
        //     'nama' => 'Pelanggan',
        //     'password' => Hash::make('12345'),
        //     'level_id' => 4
        // ];

        // UserModel::insert($data);

        // $user = UserModel::all();
        // return view('user', ['data' => $user]);

        // $data = [
        //     'nama' => 'Pelanggan Pertama',
        // ];
        // UserModel::where('username', 'customer-1')->update($data);

        //fillable
        // $data = [
        //     'level_id' => 2,
        //     'username' => 'manager_tiga',
        //     'nama' => 'Manager 3',
        //     'password' => Hash::make('12345'),
        // ];

        // UserModel::create($data);

        //find
        // $user = UserModel::find(1);

        //firstWhere
        // $user = UserModel::firstWhere('level_id', 1);


        //findOr
        // $user = UserModel::findOr(2, ['username', 'nama'], function () {
        //     abort(404);
        // });

        //findOrfail
        // $user = UserModel::findOrfail(41);

        // $user = UserModel::where('username', 'manager9')->firstOrFail();

        //Retreiving Aggregates
        // $user = UserModel:: count();
        // $user = UserModel::where('level_id', 2)->count();
        // dd($user);

        //first or Create
        // $user = UserModel::firstOrCreate([
        //     'username' => 'manager22',
        //     'nama' => 'Manager Dua Dua',
        //     'password' => Hash::make('12345'),
        //     'level_id' => 2
        // ]);

        // $user = UserModel::firstOrNew([
        //    'username' => 'manager',
        //    'nama' => 'Manager',
        // ]);

        // $user = UserModel::firstOrNew([
        //         'username' => 'manager33',
        //         'nama' => 'Manager Tiga Tiga',
        //         'password' => Hash::make('12345'),
        //         'level_id' => 2
        //     ]);

        // $user->save();
        // return view('user', ['data' => $user]);

        // Praktikum 2.5 – Attribute Changes
        //IsDirty-IsClean
        // $user = UserModel::create([
        //     'username' => 'manager44',
        //     'nama' => 'Manager44',
        //     'password' => Hash::make('12345'),
        //     'level_id' => 2
        // ]);

        // $user->username = 'manager45';

        // $user->isDirty();
        // $user->isDirty('username');
        // $user->isDirty('nama');
        // $user->isDirty(['nama', 'username']);

        // $user->isClean();
        // $user->isClean('username');
        // $user->isClean('nama');
        // $user->isClean(['nama', 'username']);

        // $user->save();

        // $user->isDirty();
        // $user->isClean();
        // dd($user->isDirty());

        //WasChanged-WasNotChanged
        // $user = UserModel::create([
        //     'username' => 'manager11',
        //     'nama' => 'Manager11',
        //     'password' => Hash::make('12345'),
        //     'level_id' => 2
        // ]);

        // $user->username = 'manager12';

        // $user->save();

        // $user->wasChanged();
        // $user->wasChanged('username');
        // $user->wasChanged('username', 'level_id');
        // $user->wasChanged('nama');
        // dd($user->wasChanged(['nama', 'username']));

        //praktikum 2.6
    //     $user = UserModel::all();
    //     return view('user', ['data' => $user]);
    // }

    // public function tambah(){
    //     return view('user_tambah');
    // }

    // public function tambah_simpan(Request $request){
    //     UserModel::create([
    //         'username' => $request->username,
    //         'nama' => $request->nama,
    //         'password' => Hash::make('$request->password'),
    //         'level_id' => $request->level_id
    //     ]);

    //     return redirect('/user');
    // }

    // public function ubah($id){
    //     $user = UserModel::find($id);
    //     return view('user_ubah', ['data' => $user]);
    // }

    // public function ubah_simpan($id,Request $request){
    //     $user = UserModel::find($id);

    //     $user->username = $request->username;
    //     $user->nama = $request->nama;
    //     $user->password = Hash::make('$request->password');
    //     $user->level_id = $request->level_id;
    //     $user->save();

    //     return redirect('/user');
    // }

    // public function hapus($id){
    //     $user = UserModel::find($id);
    //     $user->delete();

    //     return redirect('/user');
    // }

    // //2.7
    // // public function index(){
    // //     $user = UserModel::with('level')->get();
    // //     dd($user);
    // // }

    // public function index(){
    //     $user = UserModel::with('level')->get();
    //     return view('user', ['data' => $user]);
    // }
// }
