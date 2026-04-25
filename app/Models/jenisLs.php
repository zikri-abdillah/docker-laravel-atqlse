<?php

namespace App\Models;

use CodeIgniter\Model;

class jenisLs extends Model
{
    protected $table         = 'm_jenisls';
    protected $notAllowedFields = ['id'];    
    protected $returnType   = 'object';
    protected $useTimestamps = false;
}