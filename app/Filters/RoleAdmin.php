<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleAdmin implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Ambil role dari session
        $role = session()->get('role');

        // Jika role bukan admin (role 1), redirect ke halaman tidak ada akses
        if ($role !== '1') {
            return redirect()->to('/no_access');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak diperlukan aksi setelah request
    }
}
