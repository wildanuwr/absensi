<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AbsensiModel;

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
        if (!$id) {
            return redirect()->to('/');
        }

        $user = $this->loadUserData($id);
        $nama = $user['Nama']; // Ambil nama dari data user
        $date = date('Y-m-d'); // Tanggal hari ini

        $absenModel = new AbsensiModel();

        // Query untuk mendapatkan semua baris jam masuk dan jam keluar
        $builder = $absenModel->builder();
        $builder->select('jam_masuk, jam_keluar, tanggal');
        $builder->where('Nama', $nama);
        $builder->where('tanggal', $date);
        $query = $builder->get();

        $results = $query->getResultArray(); // Mengambil semua hasil sebagai array

        // Periksa apakah ada hasil dan atur variabel yang sesuai
        $data['user'] = $user;
        $data['judul'] = 'Home';
        $data['menu'] = 'home';
        $data['page'] = 'user/user_home';
        $data['absensi'] = $results; // Kirim hasil ke view

        return view('user/includes/template_user', $data);
    }

    public function userprofile()
    {
        $session = session();
        $id = $session->get('id');
        $data['user'] = $this->loadUserData($id);
        $data['menu'] = 'profile';
        $data['judul'] = 'Profile';
        $data['page'] = 'user/user_profile';

        return view('user/includes/template_user', $data);
    }

    public function absen()
    {
        $session = session();
        $id = $session->get('id');
        $data['user'] = $this->loadUserData($id);
        $data['menu'] = 'absen';
        $data['judul'] = 'Absen';
        $data['page'] = 'user/absen';

        return view('user/includes/template_user', $data);
    }

    public function lokasi()
    {
        $session = session();
        $id = $session->get('id');
        $data['user'] = $this->loadUserData($id);
        $data['menu'] = 'lokasi';
        $data['judul'] = 'Lokasi';
        $data['page'] = 'user/user_lokasi';

        return view('user/includes/template_user', $data);
    }

    public function submit_absen()
    {
        try {
            $absenModel = new AbsensiModel();

            $session = session();
            $id = $session->get('id');
            $nama = $session->get('Nama');

            // Generate random file name
            $date = date('YmdHis'); // Format: 20240810120000
            $randomName = $nama . '_' . $date . '.png';

            // Handle file upload
            $foto = $this->request->getFile('foto');
            if ($foto->isValid() && !$foto->hasMoved()) {
                $filePath = FCPATH . 'img/foto/';
                $foto->move($filePath, $randomName);

                // Prepare data to save
                $jamMasuk = $this->request->getPost('jam_masuk');
                $jamKeluar = $this->request->getPost('jam_keluar');

                // Set jam_masuk to null if jam_keluar is provided
                if (!empty($jamKeluar)) {
                    $jamMasuk = '';
                }

                $data = [
                    'Nama' => $this->request->getPost('Nama'),
                    'jam_masuk' => $jamMasuk,
                    'jam_keluar' => $jamKeluar,
                    'foto' => $randomName,
                    'lokasi' => $this->request->getPost('lokasi'),
                    'tanggal' => date('Y-m-d'),
                ];

                $absenModel->save($data);
            } else {
                throw new \RuntimeException('File upload error.');
            }

            // Return a JSON response
            return $this->response->setJSON(['status' => 'success', 'message' => 'Absen berhasil disimpan!']);
        } catch (\Exception $e) {
            // Return error response
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function cek_jam_masuk()
    {
        // Ambil parameter dari query string
        $nama = $this->request->getGet('nama');
        $date = $this->request->getGet('date');

        // Validasi parameter
        if (!$nama || !$date) {
            return $this->response->setJSON(['error' => 'Missing parameters']);
        }

        $model = new AbsensiModel();

        // Query untuk memeriksa jam masuk
        $builder = $model->builder();
        $builder->select('jam_masuk');
        $builder->where('Nama', $nama);
        $builder->where('tanggal', $date);
        $query = $builder->get();

        $result = $query->getRowArray();

        if ($result) {
            return $this->response->setJSON(['jam_masuk' => $result['jam_masuk']]);
        } else {
            return $this->response->setJSON(['jam_masuk' => null]);
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
        return redirect()->to('user/dashboard');
    }

    public function block()
    {
        return view('block_akses');
    }
}
