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
            'Nama'     => $this->request->getPost('Nama'),
            'jabatan'  => $this->request->getPost('jabatan'),
            'email'    => $this->request->getPost('email'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'no_hp'    => $this->request->getPost('no_hp'),
            'foto'     => 'profile.png', // Sesuaikan dengan upload file jika ada
            'role'     => $this->request->getPost('role')
        ];

        if ($userModel->insert($newUserData) === false) {
            // Jika insert gagal, tampilkan error
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }

        session()->setFlashdata('success', 'Berhasil Menambahkan User.');

        // Redirect ke halaman user list
        return redirect()->to('admin/manajementuser');
    }

    public function getUserData($id)
    {
        $userModel = new UserModel();
        $data['user'] = $userModel->find($id);
        return view('admin/includes/editprofile', $data);
    }


    public function editUser($id)
    {
        $userModel = new UserModel();
        $user = $userModel->find($id);

        // Pastikan data adalah array
        if (is_array($user)) {
            // Kirim data ke view
            return view('admin/includes/editprofile', ['user' => $user]);
        } else {
            throw new \Exception("Data tidak ditemukan");
        }
    }


    public function updateUser($id)
    {
        $userModel = new UserModel();

        // Ambil data lama pengguna berdasarkan ID
        $existingUser = $userModel->find($id);

        // Persiapkan data baru
        $newUserData = [
            'Nama'     => $this->request->getPost('Nama'),
            'jabatan'  => $this->request->getPost('jabatan'),
            'email'    => $this->request->getPost('email'),
            'foto'    => $this->request->getPost('foto'),
            'no_hp'    => $this->request->getPost('no_hp'),
            'role'     => $this->request->getPost('role')
        ];

        // Cek apakah password diisi, jika tidak gunakan password lama
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $newUserData['password'] = password_hash($password, PASSWORD_DEFAULT);
        } else {
            $newUserData['password'] = $existingUser['password'];
        }

        // Tambahkan foto jika diperlukan
        if ($this->request->getFile('foto')->isValid()) {
            $foto = $this->request->getFile('foto');
            $fotoName = $foto->getRandomName();
            $foto->move(FCPATH . 'img/profile/', $fotoName);
            $newUserData['foto'] = $fotoName;
        } else {
            $newUserData['foto'] = $existingUser['foto'];
        }

        // Update data pengguna
        if ($userModel->update($id, $newUserData) === false) {
            // Jika update gagal, tampilkan error
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }

        session()->setFlashdata('success', 'Berhasil Update.');

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
