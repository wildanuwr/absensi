<?php

namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    private function loadUserData($id)
    {
        $userModel = new UserModel();
        $user = $userModel->getUserById($id);

        if (!$user) {
            return [
                'nama' => 'Nama tidak ditemukan',
                'email' => 'No email provided',
                'role' => 'No role assigned'
            ];
        }

        return $user;
    }

    public function index()
    {
        $session = session();
        $id = $session->get('id');
        $nama = $session->get('Nama');

        if (!$id) {
            return redirect()->to('/'); // Redirect ke halaman login jika user_id tidak ada di sesi
        }

        $data['user'] = $this->loadUserData($id);
        $data['Nama'] = $this->loadUserData($nama);
        $data['judul'] = 'Home';
        $data['menu'] = 'home';
        $data['page'] = 'user/user_home';

        return view('user/includes/template_user', $data);
    }
    public function userprofile()
    {
        $session = session();
        $id = $session->get('user_id');
        $data['user'] = $this->loadUserData($id);
        $data['menu'] = 'profile';
        $data['judul'] = 'Profile';
        $data['page'] = 'user/user_profile';

        return view('user/includes/template_user', $data);
    }

    public function block()
    {
        return view('block_akses');
    }
}
