<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AbsensiModel;
use App\Models\LokasiModel;
use App\Models\UserLokasiModel;

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
        $date_end = date('Y-m-d', strtotime('+30 days')); // 30 hari ke depan

        $absenModel = new AbsensiModel();

        // Query untuk menghitung jumlah kehadiran dan izin
        $builder = $absenModel->builder();
        $builder->select('COUNT(*) AS jumlah_hadir, 
                          SUM(CASE WHEN jam_masuk = "izin" or jam_keluar = "izin" THEN 1 ELSE 0 END) AS jumlah_izin');
        $builder->where('Nama', $nama);
        $builder->where('tanggal >=', $date);
        $builder->where('tanggal <=', $date_end);
        $query = $builder->get();

        $result = $query->getRowArray(); // Mengambil hasil perhitungan sebagai array

        // Query untuk mendapatkan semua baris jam masuk dan jam keluar hari ini
        $builder2 = $absenModel->builder();
        $builder2->select('jam_masuk, jam_keluar, tanggal');
        $builder2->where('Nama', $nama);
        $builder2->where('tanggal', $date);
        $query2 = $builder2->get();

        $results = $query2->getResultArray(); // Mengambil semua hasil absensi sebagai array

        // Periksa apakah ada hasil dan atur variabel yang sesuai
        $data['user'] = $user;
        $data['judul'] = 'Home';
        $data['menu'] = 'home';
        $data['page'] = 'user/user_home';
        $data['absensi'] = $results; // Kirim hasil absensi ke view
        $data['jumlah_hadir'] = $result['jumlah_hadir'];
        $data['jumlah_izin'] = $result['jumlah_izin'];

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

    public function izin()
    {
        $session = session();
        $id = $session->get('id');
        $data['user'] = $this->loadUserData($id);
        $data['menu'] = 'izin';
        $data['judul'] = 'Izin';
        $data['page'] = 'user/user_izin';

        return view('user/includes/template_user', $data);
    }

    public function submit_absen()
    {
        $response = [
            'status' => 'error',
            'message' => 'Terjadi kesalahan yang tidak diketahui.'
        ];

        try {
            $absenModel = new AbsensiModel();
            $userLokasiModel = new UserLokasiModel();
            $lokasiModel = new LokasiModel();

            $session = session();
            $id = $session->get('id');
            $nama = $session->get('Nama');

            // Generate nama file acak untuk foto
            $date = date('YmdHis');
            $randomName = $nama . '_' . $date . '.png';

            // Ambil lokasi pengguna saat ini dari form
            $latitude = $this->request->getPost('latitude');
            $longitude = $this->request->getPost('longitude');

            // Ambil lokasi yang ditetapkan untuk pengguna dari database
            $userLokasi = $userLokasiModel->where('Nama', $nama)->first();
            if (!$userLokasi) {
                throw new \RuntimeException('Data lokasi pengguna tidak ditemukan. Silakan Hubungi Admin');
            }

            $lokasi = $lokasiModel->where('nama_lokasi', $userLokasi['nama_lokasi'])->first();
            if (!$lokasi) {
                throw new \RuntimeException('Lokasi tidak ditemukan.');
            }

            // Hitung jarak antara lokasi pengguna saat ini dengan lokasi yang ditetapkan
            $distance = $this->calculateDistance($latitude, $longitude, $lokasi['latitude'], $lokasi['longitude']);
            if ($distance > $lokasi['radius']) {
                throw new \RuntimeException('Lokasi Anda berada di luar jangkauan.');
            }

            // Proses upload file
            $foto = $this->request->getFile('foto');
            if ($foto->isValid() && !$foto->hasMoved()) {
                $filePath = FCPATH . 'img/foto/';
                $foto->move($filePath, $randomName);

                // Cek apakah pengguna sudah absen masuk hari ini
                $existingRecord = $absenModel->where(['Nama' => $nama, 'tanggal' => date('Y-m-d')])->first();

                if ($existingRecord) {
                    // Jika sudah ada record, update record yang ada
                    $data = [
                        'jam_keluar' => $this->request->getPost('jam_keluar'),
                        'foto_keluar' => $randomName,  // Simpan foto untuk jam keluar
                        'lokasi_keluar' => $latitude . ',' . $longitude  // Simpan lokasi untuk jam keluar
                    ];

                    // Gunakan `update()` untuk memperbarui record yang ada
                    $absenModel->update($existingRecord['id'], $data);
                } else {
                    // Jika belum ada record, simpan record baru
                    $data = [
                        'Nama' => $nama,
                        'jam_masuk' => $this->request->getPost('jam_masuk'),
                        'foto_masuk' => $randomName,  // Simpan foto untuk jam masuk
                        'lokasi_masuk' => $latitude . ',' . $longitude,  // Simpan lokasi untuk jam masuk
                        'tanggal' => date('Y-m-d'),
                    ];

                    // Gunakan `insert()` untuk menambahkan record baru
                    $absenModel->insert($data);
                }

                $response = [
                    'status' => 'success',
                    'message' => 'Absen berhasil disimpan!'
                ];
            } else {
                throw new \RuntimeException('Kesalahan dalam mengupload file.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error in submit_absen: ' . $e->getMessage());
            $response['message'] = $e->getMessage();
        }

        return $this->response->setJSON($response);
    }


    public function history()
    {
        $session = session();
        $id = $session->get('id');
        $nama = $session->get('Nama');
        $data['user'] = $this->loadUserData($id);
        $data['menu'] = 'history';
        $data['judul'] = 'Histori Absensi';
        $data['page'] = 'user/user_history';

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
        // Tambahkan kondisi untuk memfilter berdasarkan id pengguna yang login
        $builder->where('absen.Nama', $nama);
        $builder->groupBy('absen.Nama, absen.tanggal');
        $query = $builder->get();
        $results = $query->getResultArray();

        // Menyimpan hasil query ke dalam variabel $data['absensi']
        $data['absensi'] = $results;

        return view('user/includes/template_user', $data);
    }

    // Function to calculate distance between two points
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Earth radius in meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance;
    }


    public function submit_izin()
    {
        try {
            $absenModel = new AbsensiModel();

            $session = session();
            $id = $session->get('id');
            $nama = $session->get('Nama');
            $tanggal = $this->request->getPost('tanggal');

            // Cek apakah sudah ada jam masuk untuk nama dan tanggal ini
            $existingAbsen = $absenModel->where('Nama', $nama)
                ->where('tanggal', $tanggal)
                ->first();

            if ($existingAbsen) {
                // Jika sudah ada jam masuk, update jam_keluar dengan izin
                $data = [
                    'jam_keluar' => $this->request->getPost('jam_keluar'),
                    'keterangan' => $this->request->getPost('keterangan'),
                ];
                $absenModel->update($existingAbsen['id'], $data);
            } else {
                // Jika belum ada jam masuk, simpan jam_masuk sebagai izin
                $data = [
                    'Nama' => $nama,
                    'jam_masuk' => $this->request->getPost('jam_masuk'),
                    'tanggal' => $tanggal,
                    'keterangan' => $this->request->getPost('keterangan'),
                ];
                $absenModel->save($data);
            }

            // Return a JSON response
            $session->setFlashdata('status', 'success');
            $session->setFlashdata('message', 'Izin berhasil disimpan!');
            // Redirect ke halaman user dashboard
            return redirect()->to('user/dashboard');
        } catch (\Exception $e) {
            // Return error response
            return $this->response->setJSON(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function cek_jam_masuk()
    {
        $nama = $this->request->getGet('nama');
        $date = $this->request->getGet('date');

        if (!$nama || !$date) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Parameter tidak lengkap.']);
        }

        $model = new AbsensiModel();

        // Cek apakah ada absensi dengan Nama dan tanggal yang diberikan
        $absen = $model->where('Nama', $nama)
            ->where('tanggal', $date)
            ->first();

        if ($absen) {
            if ($absen['jam_keluar']) {
                // Jika jam keluar sudah diisi, berarti sudah absen penuh hari ini
                return $this->response->setJSON([
                    'status' => 'already_absent',
                    'message' => 'Anda sudah absen hari ini.'
                ]);
            } else {
                // Jika hanya jam masuk yang ada
                return $this->response->setJSON([
                    'status' => 'has_in',
                    'message' => 'Anda sudah melakukan check-in.'
                ]);
            }
        } else {
            // Jika belum ada data absensi hari ini
            return $this->response->setJSON([
                'status' => 'not_checked_in',
                'message' => 'Anda belum melakukan check-in hari ini.'
            ]);
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

        session()->setFlashdata('status', 'success');
        session()->setFlashdata('message', 'Berhasil diupdate!');
        // Redirect ke halaman user list
        return redirect()->to('user/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
    public function block()
    {
        return view('block_akses');
    }
}
