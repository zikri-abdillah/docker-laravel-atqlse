<?php

namespace App\Models;

use CodeIgniter\Model;

class incoterm extends Model
{
    protected $table         = 'm_incoterm';
    protected $primaryKey = 'id';
    protected $notAllowedFields = ['id'];    
    protected $returnType   = 'object';
    protected $useTimestamps = false;
}