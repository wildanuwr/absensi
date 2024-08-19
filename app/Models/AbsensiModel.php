<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $table = 'absen';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'id',
        'Nama',
        'jam_masuk',
        'jam_keluar',
        'foto_keluar',
        'foto_masuk',
        'lokasi_masuk',
        'lokasi_keluar',
        'tanggal',
        'keterangan'
    ];

    public function saveAbsen($data)
    {
        return $this->save($data);
    }
}
