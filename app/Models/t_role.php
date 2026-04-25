<?php

namespace App\Models;

use CodeIgniter\Model;

class t_role extends Model
{
    protected $table            = 'm_role';
    protected $primaryKey       = 'id';
    protected $notAllowedFields = ['id'];    
    protected $returnType       = 'object';
    protected $useTimestamps    = false;
}