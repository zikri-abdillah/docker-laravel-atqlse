<?php

namespace App\Models;

use CodeIgniter\Model;

class t_izin_inatradeNegara extends Model
{
    protected $table            = 't_izin_inatradeNegara';
    protected $notAllowedFields = ['id'];
    protected $returnType       = 'object';
    protected $useTimestamps    = false;
    protected $allowedFields;

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