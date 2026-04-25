<?php

namespace App\Models;

use CodeIgniter\Model;

class modaTransport extends Model
{
    protected $table         = 'm_moda_transport';
    protected $notAllowedFields = ['id'];
    protected $returnType   = 'object';
    protected $useTimestamps = false;
}