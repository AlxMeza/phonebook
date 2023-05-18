<?php

namespace App\Models;

use CodeIgniter\Model;

class Contact extends Model
{
    protected $table = 'contacts';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'users_key', 'name', 'lastname','email', 'phone'];
}
