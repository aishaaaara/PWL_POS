<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserModel; // Pastikan model ini sesuai dengan tabel m_user
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Fungsi untuk menampilkan semua data pengguna
    public function index()
    {
        return UserModel::all();
    }

    // Fungsi untuk menyimpan data pengguna baru
    public function store(Request $request)
    {
        $data = [
            'level_id' => $request->input('level_id'),
            'username' => $request->input('username'),
            'nama' => $request->input('nama'),
            'password' => bcrypt($request->input('password')), // Enkripsi password
        ];

        $user = UserModel::create($data);
        return response()->json($user, 201);
    }

    // Fungsi untuk menampilkan data pengguna tertentu berdasarkan ID
    public function show(UserModel $user)
    {
        return response()->json($user);
    }

    // Fungsi untuk memperbarui data pengguna tertentu
    public function update(Request $request, UserModel $user)
    {
        $user->update($request->all());
        return response()->json($user);
    }

    // Fungsi untuk menghapus data pengguna tertentu
    public function destroy(UserModel $user)
    {
        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}
