<?php

namespace App\Models;

use CodeIgniter\Model;

class t_dokpersh extends Model
{
    protected $table            = 't_dokpersh';
    protected $primaryKey       = 'id';
    protected $notAllowedFields = ['id'];    
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deletedAt';
    protected $useTimestamps    = false;

    protected function initialize()
    {
        $db     = db_connect();
        $fields = $db->query('SHOW COLUMNS FROM '.$this->table)->getResult();

        foreach ($fields as $key => $field) {
            if(!in_array($field->Field,$this->notAllowedFields))
                $allowed[]  = $field->Field;
        }
        $this->allowedFields = $allowed;
    }
}