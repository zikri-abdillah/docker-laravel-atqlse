<?php

namespace App\Models;

use CodeIgniter\Model;

class mandatory extends Model
{
    protected $table         = 't_mandatory';
    protected $primaryKey = 'id';
    protected $notAllowedFields = ['id'];    
    protected $returnType   = 'object';
    protected $useTimestamps = false;
}