<?php

namespace App\Models;

use CodeIgniter\Model;

class t_pegawai extends Model
{
    protected $table            = 'm_pegawai';
    protected $primaryKey       = 'id';
    protected $notAllowedFields = ['id'];    
    protected $allowedFields; 
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $deletedField     = 'deletedAt';
    protected $useTimestamps    = false;

    protected $validationRules  = [
        'email'                 => 'required|valid_email',
        'telp'                  => 'required|numeric', 
    ]; 

    protected $validationMessages = [ 
        'email' => [
            'required'          => 'e-mail tidak boleh kosong',   
            'valid_email'       => 'email tidak valid',  
        ],
        'telp' => [
            'required'          => 'Nomor telephone tidak boleh kosong',   
            'is_unique'         => 'Nomor telephone harus angka',  
        ]
    ];
     
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