<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
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

    public function tambah(){
        return view('user_tambah');
    }

    public function tambah_simpan(Request $request){
        UserModel::create([
            'username' => $request->username,
            'nama' => $request->nama,
            'password' => Hash::make('$request->password'),
            'level_id' => $request->level_id
        ]);

        return redirect('/user');
    }

    public function ubah($id){
        $user = UserModel::find($id);
        return view('user_ubah', ['data' => $user]);
    }

    public function ubah_simpan($id,Request $request){
        $user = UserModel::find($id);

        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Hash::make('$request->password');
        $user->level_id = $request->level_id;
        $user->save();

        return redirect('/user');
    }

    public function hapus($id){
        $user = UserModel::find($id);
        $user->delete();

        return redirect('/user');
    }

    //2.7
    // public function index(){
    //     $user = UserModel::with('level')->get();
    //     dd($user);
    // }

    public function index(){
        $user = UserModel::with('level')->get();
        return view('user', ['data' => $user]);
    }
}
