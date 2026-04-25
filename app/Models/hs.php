<?php

namespace App\Models;

use CodeIgniter\Model;

class hs extends Model
{
    protected $table         = 'm_hs';
    protected $notAllowedFields = ['id'];    
    protected $returnType   = 'object';
    protected $useTimestamps = false;
}