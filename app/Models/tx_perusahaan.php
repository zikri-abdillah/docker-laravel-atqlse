<?php

namespace App\Models;

use CodeIgniter\Model;

class tx_perusahaan extends Model
{
    protected $table            = 'm_perusahaan';
    protected $primaryKey       = 'id';
    protected $notAllowedFields = ['id'];  
    protected $allowedFields    ;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true; 
    protected $deletedField     = 'deletedAt'; 
    protected $useTimestamps    = false;
    protected $createdField     = 'tglInsert';
    protected $updatedField     = 'lastUpdate';

    protected $validationRules = [ 
        'bentukPersh'   => 'required',
        'nama'          => 'required',
        // 'npwp'       => 'required|numeric|max_length[16]|is_unique[m_perusahaan.npwp, id, {id}]',
        'npwp'          => 'required|numeric|max_length[16]',
        'nib'           => 'required', 
        'email'         => 'required|valid_email',
        'telp'          => 'required|min_length[5]',
        'picNama'       => 'required',
        'picJabatan'    => 'required',
        'picTelp'       => 'required|min_length[5]',
        'picEmail'      => 'required|valid_email',
    ];

    protected $validationMessages = [ 
        'bentukPersh'   => [
            'required'      => 'Bentuk usaha tidak boleh kosong minimal 15 karakter',  
        ],
        'nama'          => [
            'required'      => 'Nama perusahaan tidak boleh kosong',  
        ],
        'npwp'           => [
            'required'      => 'NPWP tidak boleh kosong minimal 15 karakter',
            'numeric'       => 'NPWP hanya boleh angka minimal 15 karakter', 
            'max_length'    => 'NPWP maksimal 16 karakter', 
        ],
        'nib'           => [
            'required'      => 'NIB tidak boleh kosong minimal 9 angka',
            'numeric'       => 'NIB hanya boleh angka minimal 8 angka', 
        ], 
        'email'         => [
            'required'      => 'Email tidak boleh kosong', 
            'min_length'    => 'Email maksimal 50 karakter', 
            'valid_email'   => 'Email harus valid', 
        ],
        'telp'          => [
            'required'      => 'Nomor telephone tidak boleh kosong minimal 9 angka',
            'numeric'       => 'Nomor telephone hanya boleh angka minimal 8 angka', 
            'min_length'    => 'Telp minimal 5 karakter',
        ],
        'picNama'       => [
            'required'      => 'Nama PIC tidak boleh kosong',  
        ],
        'picJabatan'    => [
            'required'      => 'Jabatan PIC tidak boleh kosong',  
        ],
        'picEmail'      => [
            'required'      => 'Email PIC tidak boleh kosong', 
            'min_length'    => 'Email PIC maksimal 50 karakter', 
            'valid_email'   => 'Email PIC harus valid', 
        ],
        'picTelp'       => [
            'required'      => 'Nomor telephone PIC tidak boleh kosong minimal 9 angka',
            'numeric'       => 'Nomor telephone PIC hanya boleh angka minimal 8 angka', 
            'min_length'    => 'NPWP minimal 11 karakter',
        ],
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