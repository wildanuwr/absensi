<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $allowedFields = ['Nama', 'jabatan', 'email', 'password', 'no_hp', 'foto', 'role'];

    public function getUserById($id)
    {
        return $this->where('id', $id)->first();
    }

    public function getAllUsers()
    {
        return $this->findAll();
    }
}