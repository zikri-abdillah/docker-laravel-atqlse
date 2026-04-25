<?php

namespace App\Models;

use CodeIgniter\Model;

class t_userType extends Model
{
    protected $table            = 'm_user_type';
    protected $primaryKey       = 'id';
    protected $notAllowedFields = ['id'];    
    protected $returnType       = 'object';
    protected $useTimestamps    = false;
}