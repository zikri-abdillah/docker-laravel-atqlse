<?php

namespace App\Models;

use CodeIgniter\Model;

class port extends Model
{
    protected $table         = 'm_port';
    protected $primaryKey = 'id';
    protected $notAllowedFields = ['id'];    
    protected $returnType   = 'object';
    protected $useTimestamps = false;
}