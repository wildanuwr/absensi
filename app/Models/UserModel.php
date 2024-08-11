<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'Nama', 'email', 'password', 'role', 'tanggal'];

    public function getUserById($id)
    {
        return $this->where('id', $id)->first();
    }
}

