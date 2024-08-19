<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $table = 'user';
    protected $allowedFields = ['id', 'email', 'Nama', 'password', 'role',];

    public function getUser($email)
    {
        return $this->asArray()
            ->where(['email' => $email])
            ->first();
    }
}
