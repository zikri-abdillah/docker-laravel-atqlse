<?php

namespace App\Models;

use CodeIgniter\Model;

class currency extends Model
{
    protected $table         = 'm_currency';
    protected $notAllowedFields = ['id'];    
    protected $returnType   = 'object';
    protected $useTimestamps = false;
}