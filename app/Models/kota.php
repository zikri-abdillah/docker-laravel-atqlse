<?php

namespace App\Models;

use CodeIgniter\Model;

class kota extends Model
{     
    protected $table            = 'm_kota';
    protected $primaryKey       = 'id';
    protected $notAllowedFields = ['id'];
    protected $allowedFields;
    protected $returnType       = 'object';
    protected $useTimestamps    = false;

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