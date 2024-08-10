<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id';
    protected $allowedFields = ['Nama', 'email', 'password', 'role'];

    public function getUserById($id)
    {
        return $this->where('id', $id)->first();
    }
}
