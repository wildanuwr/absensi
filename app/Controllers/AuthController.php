<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AuthModel;

class AuthController extends BaseController
{
    public function login()
    {
        helper(['form']);
        return view('login');
    }

    public function loginAuth()
    {
        $session = session();
        $model = new AuthModel();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $data = $model->getUser($email);

        if ($data) {
            $pass = $data['password'];
            $authenticatePassword = password_verify($password, $pass);
            if ($authenticatePassword) {
                $ses_data = [
                    'id' => $data['id'],
                    'email' => $data['email'],
                    'role' => $data['role'],
                    'logged_in' => TRUE
                ];
                $session->set($ses_data);

                if ($data['role'] == 1) {
                    return redirect()->to('admin/dashboard'); // Redirect ke halaman admin
                } else if ($data['role'] == 2) {
                    return redirect()->to('user/dashboard'); // Redirect ke halaman user
                }
            } else {
                $session->setFlashdata('msg', 'Password Salah');
                return redirect()->back();
            }
        } else {
            $session->setFlashdata('msg', 'Email Tidak Terdaftar');
            return redirect()->back();
        }
    }

    public function logout()
    {
        $session = session();
        $session->destroy();
        return redirect()->to('/'); // Redirect ke halaman login
    }
}
