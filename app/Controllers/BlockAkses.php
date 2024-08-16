<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class BlockAkses extends BaseController
{
    public function show404()
    {
        echo view('block_akses');
    }
    
    public function redirectToDashboard()
    {
        $session = session();
        
        if (!$session->has('role')) {
            return redirect()->to('login');
        }

        $role = $session->get('role');

        // Cek role dan arahkan ke dashboard yang sesuai
        if ($role == 1) { // Admin
            return redirect()->to('admin/dashboard');
        } elseif ($role == 2) { // User
            return redirect()->to('user/dashboard');
        } else {
            return redirect()->to('home');
        }
    }
}
