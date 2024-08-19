<?php

namespace App\Models;

use CodeIgniter\Model;

class UserLokasiModel extends Model
{
    protected $table = 'user_lokasi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['Nama', 'nama_lokasi'];

    // Function to get all user-lokas
    public function getAllUserLokasi()
    {
        return $this->findAll();
    }
}
