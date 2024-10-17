<?php

namespace App\Http\Controllers;

class WelcomeController extends Controller
{
    public function index(){
    
        $breadcrumb = (object) [
            'title' => 'Welcome',
            'list' => [
                ['name' => 'Home', 'url' => url('/')],
                ['name' => 'welcome', 'url' => url('/welcome')]
            ]
        ];

        $activeMenu = 'dashboard';
        return view('welcome', ['breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu]);
    }
}
?>