<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
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

        $user = UserModel::firstOrNew([
                'username' => 'manager33',
                'nama' => 'Manager Tiga Tiga',
                'password' => Hash::make('12345'),
                'level_id' => 2
            ]);

        $user->save();
        return view('user', ['data' => $user]);
    }
}