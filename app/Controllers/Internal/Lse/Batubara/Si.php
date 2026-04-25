<?php

namespace App\Controllers\Internal\Lse\Batubara;

use App\Controllers\BaseController;
use App\Models\TxLseHdrModel as TxLseHdrModel;
// helper('encryption');

class Si extends BaseController
{
    protected $pageTitle;

    public function input()
    {
        $cabangModel = model('cabangModel');

        $cabang = $cabangModel->find(2);
        $req = $this->request->getPost();
        $page = ['page_title'   => 'Input Shipping Instruction'];

        $param['addJS'] = '<script src="' . base_url() . '/assets/plugins/formwizard/jquery.smartWizard.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/formwizard/fromwizard.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/jquery-steps/jquery.steps.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/parsleyjs/parsley.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/select2/select2.full.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/js/ekspor/coal/input.js"></script>';

        $param['content'] = $this->render('ekspor.batubara.si.input', $page);
        return $this->render('layout.template', $param);
    }

    public function list()
    {
        $req = $this->request->getPost();
        $data = [
            'blog_title'   => 'My Blog Title',
            'blog_heading' => 'My Blog Heading',
        ];

        $param['addJs'] = '<script src="' . base_url() . '/js/ekspor/coal.js"></script>';
        $param['content'] = $this->render('ekspor.coal.listls', $data);
        return $this->render('layout.template', $param);
    }
}
