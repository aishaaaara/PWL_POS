<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $user = UserModel::findOrFail(Auth::id());

        $breadcrumb = (object) [
            'title' => 'Data Profile',
            'list' => [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'profile', 'url' => url('/profile')]
            ]
        ];

        $activeMenu = 'profile';

        return view('profile', compact('user'),[
            'breadcrumb' => $breadcrumb, 
            'activeMenu' => $activeMenu
        ]);
    }
    public function update(Request $request, $id)
    {
        request()->validate([
            'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id', 
            'nama'     => 'required|string|max:100',
            'old_password' => 'nullable|string',
            'password' => 'nullable|min:5',
        ]);

        $user = UserModel::find($id);

        $user->username = $request->username;
        $user->nama = $request->nama;

        if ($request->filled('old_password')) {
            if (Hash::check($request->old_password, $user->password)) {
                $user->update([
                    'password' => Hash::make($request->password)
                ]);
            } else {
                return back()
                    ->withErrors(['old_password' => __('Please enter the correct password')])
                    ->withInput();
            }
        }

        if (request()->hasFile('avatar')) {
            if($user->avatar && file_exists(storage_path('app/public/photos/' . $user->avatar))){
                Storage::delete('app/public/photos/'.$user->avatar);
            }

            $file = $request->file('avatar');
            $fileName = $file->hashName() . '.' . $file->getClientOriginalExtension();
            $request->avatar->move(storage_path('app/public/photos'), $fileName);
            $user->avatar = $fileName;
        }


        $user->save();

    return back()->with('status', 'Berhasil di update');
    }
}