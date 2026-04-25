<?php

namespace App\Models;

use CodeIgniter\Model;

class propinsi extends Model
{
    protected $table         = 'm_propinsi';
    protected $primaryKey = 'id';
    protected $notAllowedFields = ['id'];    
    protected $returnType   = 'object';
    protected $useTimestamps = false;
}