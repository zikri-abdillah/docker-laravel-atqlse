<?php

namespace App\Models;

use CodeIgniter\Model;

class satuan extends Model
{
    protected $table         = 'm_satuan';
    protected $primaryKey = 'id';
    protected $notAllowedFields = ['id'];    
    protected $returnType   = 'object';
    protected $useTimestamps = false;
}