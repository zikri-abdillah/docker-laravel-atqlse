<?php

namespace App\Models;

use CodeIgniter\Model;

class jenisContainer extends Model
{
    protected $table         = 'm_jenis_container';
    protected $primaryKey = 'id';
    protected $notAllowedFields = ['id'];
    protected $returnType   = 'object';
    protected $useTimestamps = false;

    protected function initialize()
    {
        $db = db_connect();
        $fields = $db->query('SHOW COLUMNS FROM '.$this->table)->getResult();
        foreach ($fields as $key => $field) {
            if(!in_array($field->Field,$this->notAllowedFields))
                $allowed[] = $field->Field;
        }
        $this->allowedFields = $allowed;
    }
}