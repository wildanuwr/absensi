<?php

namespace App\Models;

use CodeIgniter\Model;

class LokasiModel extends Model
{
    protected $table = 'lokasi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_lokasi', 'latitude', 'longitude', 'radius'];

    public function simpanLokasi($data)
    {
        return $this->insert($data);
    }
    public function getAllLokasi()
    {
        return $this->findAll();
    }
}
