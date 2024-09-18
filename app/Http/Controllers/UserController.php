<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {

        
        $user = UserModel::firstOrNew(
            [
                'username' => 'manager33',
                'nama' => 'Manager Tiga Tiga',
                'password' => Hash::make('12345'),
                'level_id' => 2

            ],
        );
        $user->save();
        return view('user', ['data' => $user]);



        // $data = [
        //     'level_id' => 2,
        //     'username' => 'manager_tiga',
        //     'nama' => 'Manager 3',
        //     'password' => Hash::make('12345')
        // ];
        // UserModel::create($data); 

        //     $data = [
        //         'nama' => 'Pelanggan Pertama',
        //     ];
        //     UserModel::where('username', 'customer-1')->update($data); //update data user

        // $user = UserModel::where('level_id', 2)-> count();
        // $user = UserModel::findOrFail(1);
        // $user = UserModel::first();
        // $user = UserModel::firstWhere('level_id', 1);
        

        // $user = UserModel::firstOrCreate(
        //     [
        //         'username' => 'manager22',
        //         'nama' => 'Manager Dua Dua',
        //         'password' => Hash::make('12345'),
        //         'level_id' => 2
        //     ],
        // );

        
        // $user = UserModel::firstOrNew(
        //     [
        //         'username' => 'manager',
        //         'nama' => 'Manager',
        //     ],
        // );
    }
}