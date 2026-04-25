<?php

namespace App\Models;

use CodeIgniter\Model;

class jenisIup extends Model
{
    protected $table         = 'm_jenis_iup';
    protected $notAllowedFields = ['id'];    
    protected $returnType   = 'object';
    protected $useTimestamps = false;
}