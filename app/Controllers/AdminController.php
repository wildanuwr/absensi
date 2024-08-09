<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class AdminController extends BaseController
{

    public function index()
    {
        echo view('admin/dashboard');
    }
    public function manajementuser()
    {
        echo view('admin/manajementuser');
    }
}
