<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleUser implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Ambil role dari session
        $role = session()->get('role');

        // Jika role bukan user (role 2), redirect ke halaman tidak ada akses
        if ($role !== '2') {
            return redirect()->to('/no_access');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak diperlukan aksi setelah request
    }
}
