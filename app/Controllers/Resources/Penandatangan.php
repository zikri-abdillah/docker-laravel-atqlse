<?php

namespace App\Controllers\Resources;

use CodeIgniter\RESTful\ResourceController;


class Penandatangan extends ResourceController
{
    function __construct(){
        $this->penandatanganModel = model('App\Models\penandatangan');
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
            $this->penandatanganModel->like('nama',$q,'both');
        }

        $arrPenandatangan = $this->penandatanganModel->select('id, nama as text')->where('isActive', 'Y')->findAll();
        $return['data'] = $arrPenandatangan;
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

        try
        {

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
