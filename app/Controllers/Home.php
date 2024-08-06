<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('admin/manajemen');
    }

    public function userdashboard()
    {
        $data = [
            'judul' => 'Home',
            'menu' => 'home',
            'page' => 'user/user_home'
        ];
        return view('user/includes/template_user', $data);
    }

    public function userprofile()
    {
        $data = [
            'judul' => 'Profile',
            'menu' => 'profile',
            'page' => 'user/user_profile'
        ];
        return view('user/includes/template_user', $data);
    }
}
