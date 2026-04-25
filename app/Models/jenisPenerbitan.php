<?php

namespace App\Models;

use CodeIgniter\Model;

class jenisPenerbitan extends Model
{
    protected $table         = 'm_jenis_penerbitan';
    protected $notAllowedFields = ['id'];
    protected $returnType   = 'object';
    protected $useTimestamps = false;
}