<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
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

        $user = UserModel::findOrFail(1);
        return view('user', ['data' => $user]);
        
        // $user = UserModel::first();
        // $user = UserModel::firstWhere('level_id', 1);
        
    }
}