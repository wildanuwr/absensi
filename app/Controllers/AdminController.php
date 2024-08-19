<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\AbsensiModel;
use App\Models\LokasiModel;
use App\Models\UserLokasiModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
        MAX(CASE WHEN absen.jam_masuk IS NOT NULL THEN absen.foto_masuk END) as foto_masuk,
        MAX(CASE WHEN absen.jam_keluar IS NOT NULL THEN absen.foto_keluar END) as foto_keluar,
        MAX(CASE WHEN absen.jam_masuk IS NOT NULL THEN absen.lokasi_masuk END) as lokasi_masuk,
        MAX(CASE WHEN absen.jam_keluar IS NOT NULL THEN absen.lokasi_keluar END) as lokasi_keluar
    ");
        $builder->groupBy('absen.Nama, absen.tanggal');
        $query = $builder->get();
        $results = $query->getResultArray();

        // Menyimpan hasil query ke dalam variabel $data['absensi']
        $data['absensi'] = $results;

        // Load view dengan data
        echo view('admin/dashboard', $data); // Pastikan nama view sesuai
    }

    public function laporanabsensi()
    {
        $session = session();
        $id = $session->get('id');
        if (!$id) {
            return redirect()->to('/');
        }

        $user = $this->loadUserData($id);
        $data['user'] = $user;

        // Load AbsensiModel
        $absenModel = new AbsensiModel();

        // Ambil tanggal dari query parameter
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');

        // Query untuk menggabungkan jam_masuk, jam_keluar, foto, dan lokasi dalam satu baris
        $builder = $absenModel->builder();
        $builder->select("
            absen.Nama,
            absen.tanggal,
            MAX(CASE WHEN absen.jam_masuk IS NOT NULL THEN absen.jam_masuk END) as jam_masuk,
            MAX(CASE WHEN absen.jam_keluar IS NOT NULL THEN absen.jam_keluar END) as jam_keluar,
            MAX(CASE WHEN absen.jam_masuk IS NOT NULL THEN absen.foto_masuk END) as foto_masuk,
            MAX(CASE WHEN absen.jam_keluar IS NOT NULL THEN absen.foto_keluar END) as foto_keluar,
            MAX(CASE WHEN absen.jam_masuk IS NOT NULL THEN absen.lokasi_masuk END) as lokasi_masuk,
            MAX(CASE WHEN absen.jam_keluar IS NOT NULL THEN absen.lokasi_keluar END) as lokasi_keluar
        ");
        $builder->groupBy('absen.Nama, absen.tanggal');

        // Tambahkan filter berdasarkan tanggal jika ada
        if ($start_date && $end_date) {
            $builder->where('absen.tanggal >=', $start_date);
            $builder->where('absen.tanggal <=', $end_date);
        }

        $query = $builder->get();
        $results = $query->getResultArray();

        // Menyimpan hasil query ke dalam variabel $data['absensi']
        $data['absensi'] = $results;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;

        // Load view dengan data
        echo view('admin/laporanabsensi', $data); // Pastikan nama view sesuai
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

    public function manajementlokasi()
    {
        $session = session();
        $id = $session->get('id');
        if (!$id) {
            return redirect()->to('/');
        }

        $user = $this->loadUserData($id);
        $data['user'] = $user;
        $data['judul'] = 'Manajement Lokasi';

        $lokasimodel = new LokasiModel();
        $data['lokasi'] = $lokasimodel->getAllLokasi();

        // Mengirim data ke view
        echo view('admin/manajementlokasi', $data);
    }

    public function simpanlokasi()
    {
        $lokasiModel = new LokasiModel();

        $data = [
            'nama_lokasi' => $this->request->getPost('nama_lokasi'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
            'radius' => $this->request->getPost('radius'),
        ];

        $lokasiModel->simpanLokasi($data);
        session()->setFlashdata('success', 'Berhasil Menambahkan Lokasi.');
        return redirect()->to('admin/manajementlokasi');
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
    public function penempatan($id = null)
    {

        $session = session();
        $id = $session->get('id');
        if (!$id) {
            return redirect()->to('/');
        }

        $user = $this->loadUserData($id);
        $data['user'] = $user;

        $userModel = new UserModel();
        $data['userid'] = $userModel->find($id);
        $data['usernama'] = $userModel->getAllUsers();

        $lokasiModel = new LokasiModel();
        $data['lokasi'] = $lokasiModel->getAllLokasi();

        $UserLokasi = new UserLokasiModel();
        $data['user_lokasi'] = $UserLokasi->getAllUserLokasi();

        if ($id) {
            $userModel = new UserModel();
            $data['user'] = $userModel->find($id);
        } else {
            $data['user'] = null;
        }

        $data['judul'] = 'Penempatan Lokasi';

        return view('admin/penempatan', $data);
    }


    public function updatePenempatan($id)
    {
        $userLokasModel = new UserLokasiModel();
        $userModel = new UserModel();

        $data = [
            'Nama' => $this->request->getPost('Nama'),
            'nama_lokasi' => $this->request->getPost('nama_lokasi'),
        ];

        $userLokasModel->save($data);

        // Redirect or send a response
        return redirect()->to('admin/penempatan');
    }

    public function download_laporan()
    {
        // Ambil tanggal dari parameter GET
        $startDate = $this->request->getGet('start_date');
        $endDate = $this->request->getGet('end_date');

        // Load model
        $absenModel = new AbsensiModel();

        // Query untuk data absensi sesuai tanggal
        $builder = $absenModel->builder();
        $builder->select("
        absen.Nama,
        absen.tanggal,
        MAX(CASE WHEN absen.jam_masuk IS NOT NULL THEN absen.jam_masuk END) as jam_masuk,
        MAX(CASE WHEN absen.jam_keluar IS NOT NULL THEN absen.jam_keluar END) as jam_keluar,
        MAX(CASE WHEN absen.jam_masuk IS NOT NULL THEN absen.foto_masuk END) as foto_masuk,
        MAX(CASE WHEN absen.jam_keluar IS NOT NULL THEN absen.foto_keluar END) as foto_keluar,
        MAX(CASE WHEN absen.jam_masuk IS NOT NULL THEN absen.lokasi_masuk END) as lokasi_masuk,
        MAX(CASE WHEN absen.jam_keluar IS NOT NULL THEN absen.lokasi_keluar END) as lokasi_keluar
    ");
        $builder->groupBy('absen.Nama, absen.tanggal');

        if ($startDate && $endDate) {
            $builder->where('absen.tanggal >=', $startDate);
            $builder->where('absen.tanggal <=', $endDate);
        }

        $query = $builder->get();
        $results = $query->getResultArray();

        // Generate Excel file
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set header columns
        $sheet->setCellValue('A1', 'Nama');
        $sheet->setCellValue('B1', 'Tanggal');
        $sheet->setCellValue('C1', 'Jam Masuk');
        $sheet->setCellValue('D1', 'Foto Masuk');
        $sheet->setCellValue('E1', 'Lokasi Masuk');
        $sheet->setCellValue('F1', 'Jam Pulang');
        $sheet->setCellValue('G1', 'Foto Pulang');
        $sheet->setCellValue('H1', 'Lokasi Pulang');

        $sheet->getColumnDimension('D')->setWidth(50);
        $sheet->getColumnDimension('G')->setWidth(50);
        // Populate data
        $row = 2;
        foreach ($results as $data) {
            $sheet->setCellValue('A' . $row, $data['Nama']);
            $sheet->setCellValue('B' . $row, $data['tanggal']);
            $sheet->setCellValue('C' . $row, $data['jam_masuk']);
            $sheet->setCellValue('E' . $row, $data['lokasi_masuk']);
            $sheet->setCellValue('F' . $row, $data['jam_keluar']);
            $sheet->setCellValue('H' . $row, $data['lokasi_keluar']);

            // Insert images
            if ($data['foto_masuk']) {
                $imagePath = FCPATH . 'img/foto/' . $data['foto_masuk'];
                if (file_exists($imagePath)) {
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setName('Foto Masuk');
                    $drawing->setDescription('Foto Masuk');
                    $drawing->setPath($imagePath);
                    $drawing->setHeight(50);
                    $drawing->setCoordinates('D' . $row);
                    $drawing->setWorksheet($sheet);
                }
            }

            if ($data['foto_keluar']) {
                $imagePath = FCPATH . 'img/foto/' . $data['foto_keluar'];
                if (file_exists($imagePath)) {
                    $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                    $drawing->setName('Foto Pulang');
                    $drawing->setDescription('Foto Pulang');
                    $drawing->setPath($imagePath);
                    $drawing->setHeight(50);
                    $drawing->setCoordinates('G' . $row);
                    $drawing->setWorksheet($sheet);
                }
            }

            $row++;
        }

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        $filename = 'Laporan_Absensi_' . date('YmdHis') . '.xlsx';

        // Output file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
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
