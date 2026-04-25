<?php

namespace App\Controllers\Resources;

use CodeIgniter\RESTful\ResourceController;


class Cabang extends ResourceController
{
    function __construct(){
        $this->cabangModel = model('App\Models\cabang');
    }

    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        //
    }

    /**
     * Return the properties of a resource object
     *
     * @return mixed
     */
    public function show($id = null)
    {
        //
    }

    /**
     * Return a new resource object, with default properties
     *
     * @return mixed
     */
    public function new()
    {
        //
    }

    /**
     * Return resource, with select2 format
     *
     * @return json
     */
    public function selectdua()
    {
        $q = $this->request->getPost('q');
        if($q) {
            $this->cabangModel->like('nama',$q,'both');
        }

        $arrData = $this->cabangModel->select('id, cabang as text')->where('isActive', 'Y')->findAll();
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    /**
     * Create a new resource object, from "posted" parameters
     *
     * @return mixed
     */
    public function create()
    {
        $validation = \Config\Services::validation();
        $req = $this->request->getPost();
        $data = remove_prefix($req);

        $mandatory = [
            'cabang' => 'required|min_length[3]',
            'isActive' => 'max_length[1]'
        ];
        $errMsg = [
            'cabang' => [
                'required' => 'Nama cabang harus di isi',
                'min_length' => 'Nama cabang minimal 3 karakter',
            ],
            'isActive' => [
                'max_length' => 'Invalid status',
            ]
        ];

        $validation->setRules($mandatory,$errMsg);
        if (! $validation->run($req)) {
            print_r($validation->getErrors());
            exit('aaa');
        }

        try
        {
            $this->CabangModel->transException(true)->transStart();

            if ($insert = $this->CabangModel->save($data) === false) {
                print_r($this->CabangModel->errors());
            }
            $this->CabangModel->transComplete();
            $response = ['data' => '321a', 'code' => '321a','messages' => ['Error message 1','Error message 2',],];
            return $this->respond($response);

        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            exit('Exception Handler 1'.$e->getcode().' - '.$e->getMessage());
        } catch (\Exception $e) {
            exit('Exception Handler 2'.$e->getcode().' - '.$e->getMessage());
        }
    }

    /**
     * Return the editable properties of a resource object
     *
     * @return mixed
     */
    public function edit($id = null)
    {
        //
    }

    /**
     * Add or update a model resource, from "posted" properties
     *
     * @return mixed
     */
    public function update($id = null)
    {
        //
    }

    /**
     * Delete the designated resource object from the model
     *
     * @return mixed
     */
    public function delete($id = null)
    {
        //
    }

    /**
     * Return for select2 format
     *
     * @return mixed
     */
    public function select($id = null)
    {
        //
    }
}
