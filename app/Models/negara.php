<?php

namespace App\Models;

use CodeIgniter\Model;

class negara extends Model
{
    protected $table         = 'm_negara';
    protected $primaryKey = 'id';
    protected $notAllowedFields = ['id'];    
    protected $returnType   = 'object';
    protected $useTimestamps = false;
}