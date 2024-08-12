<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\AbsensiModel;

class AdminController extends BaseController
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
        if (!$id) {
            return redirect()->to('/');
        }

        $user = $this->loadUserData($id);
        $data['user'] = $user;
        $data['judul'] = 'Home';
        $data['menu'] = 'home';
        $data['page'] = 'user/user_home';

        // Load AbsensiModel
        $absenModel = new AbsensiModel();

        // Query untuk menggabungkan jam_masuk, jam_keluar, foto, dan lokasi dalam satu baris
        $builder = $absenModel->builder();
        $builder->select("
        absen.Nama,
        absen.tanggal,
        MAX(CASE WHEN absen.jam_masuk IS NOT NULL THEN absen.jam_masuk END) as jam_masuk,
        MAX(CASE WHEN absen.jam_keluar IS NOT NULL THEN absen.jam_keluar END) as jam_keluar,
        MAX(CASE WHEN absen.jam_masuk IS NOT NULL THEN absen.foto END) as foto_masuk,
        MAX(CASE WHEN absen.jam_keluar IS NOT NULL THEN absen.foto END) as foto_pulang,
        MAX(CASE WHEN absen.jam_masuk IS NOT NULL THEN absen.lokasi END) as lokasi_masuk,
        MAX(CASE WHEN absen.jam_keluar IS NOT NULL THEN absen.lokasi END) as lokasi_pulang
    ");
        $builder->groupBy('absen.Nama, absen.tanggal');
        $query = $builder->get();
        $results = $query->getResultArray();

        // Menyimpan hasil query ke dalam variabel $data['absensi']
        $data['absensi'] = $results;

        // Load view dengan data
        echo view('admin/dashboard', $data); // Pastikan nama view sesuai
    }


    public function manajementuser()
    {
        $session = session();
        $id = $session->get('id');
        if (!$id) {
            return redirect()->to('/');
        }

        $user = $this->loadUserData($id);
        $data['user'] = $user;
        $data['judul'] = 'Manajement User';

        $userModel = new UserModel();
        $data['users'] = $userModel->getAllUsers();

        // Mengirim data ke view
        echo view('admin/manajementuser', $data);
    }

    public function addUser()
    {
        $userModel = new UserModel();

        $newUserData = [
            'Nama'     => 'Nama User Baru',
            'jabatan'  => 'Jabatan User Baru',
            'email'    => 'email@contoh.com',
            'password' => password_hash('password_user', PASSWORD_DEFAULT),
            'no_hp'    => '08123456789',
            'foto'     => 'path/to/foto.jpg',
            'role'     => 'user_role'
        ];

        $userModel->insert($newUserData);

        // Redirect ke halaman user list
        return redirect()->to('admin/manajementuser');
    }


    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
    public function block()
    {
        echo view('block_akses');
    }
}
