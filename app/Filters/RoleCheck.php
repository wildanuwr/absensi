<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleCheck implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('/');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $session = session();
        if ($session->get('logged_in')) {
            if ($session->get('role') == 1) {
                return redirect()->to('/admin/dashboard');
                // Redirect ke halaman admin
            } else {
                return redirect()->to('/user/dashboard');
            }
        }
    }
}
