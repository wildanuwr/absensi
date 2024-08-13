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
}
