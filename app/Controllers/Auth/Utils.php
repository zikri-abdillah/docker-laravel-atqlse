<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;


class Utils extends BaseController
{    

    public function filenotfound()
    {
        $this->response->setStatusCode(404);
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }
}
