<?php

namespace App\Controllers\Print;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use setasign\Fpdi\Tcpdf\Fpdi;
use Dompdf\Dompdf;
use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Common\EccLevel;

class Batubara extends BaseController
{
    function __construct(){

    }

    public function pve()
    {
        helper('filesystem');

        $req = $this->request->getPost();
        if(!isset($req['id']) || empty($req['id'])){
            exit;
        }
        try {
            $idLs = decrypt_id($req['id']);

            $datals = model('tx_lseHdr')->find($idLs);
            $dataPerusahaan = $this->db->table('m_perusahaan')->where('id',$datals->idPersh)->get()->getRow();
            $param['datals'] = $datals;
            $param['barangs'] = $this->db->table('tx_lsedtlhs')->where('idLs',$datals->id)->get()->getResult();;
            $param['dataPerusahaan'] = $dataPerusahaan;

            // from m_jenis_iup
            $arrayIUPOP = [1,2,3,5,7,8];
            $arrayIUPOPKPP = [4,6];

            $noIupOp = $noIupAngkutJual = NULL;
            if(in_array($datals->idJnsIUP,$arrayIUPOP)){
                $noIupOp = $datals->noIUP;
            }
            else if(in_array($datals->idJnsIUP,$arrayIUPOPKPP)){
                $noIupAngkutJual = $datals->noIUP;
            }
            $param['noIupOp'] = $noIupOp;
            $param['noIupAngkutJual'] = $noIupAngkutJual;


            $pdf = new Fpdi('PORTRAIT', PDF_UNIT, 'LETTER', true, 'UTF-8', false);
            $pdf->SetCreator('Aplikasi LSE Mineral PT  Indo Borneo Inspeksi Service');
            $pdf->SetAuthor('PT  Indo Borneo Inspeksi Service');
            $pdf->SetTitle('PVE-PPHPP');
            $pdf->SetSubject('PVE-PPHPP');
            $pdf->setPrintHeader(false);
            $pdf->SetMargins(4,5,5,5);

            $pdf->AddPage();
            $pdf->SetLineStyle( array( 'width' => 0.5, 'color' => array(0,0,0)));
            $pdf->Line(5,5,$pdf->getPageWidth()-5,5);
            $pdf->Line($pdf->getPageWidth()-5,5,$pdf->getPageWidth()-5,$pdf->getPageHeight()-5);
            $pdf->Line(5,$pdf->getPageHeight()-5,$pdf->getPageWidth()-5,$pdf->getPageHeight()-5);
            $pdf->Line(5,5,5,$pdf->getPageHeight()-5);

            $content = $this->render('client.lse.print_pve',$param);
            $pdf->writeHTML($content, true, false, true, false, '');
            $this->response->setContentType('application/pdf');
            $pdf->Output('PVE-PPHPP', 'I');

        } catch (Exception $e) {

        }
    }

    public function lse()
    {
        helper('filesystem');

        $req = $this->request->getPost();
        if(!isset($req['id']) || empty($req['id'])){
            exit;
        }
        try {
            $idLs = decrypt_id($req['id']);

            $datals = model('tx_lseHdr')->find($idLs);
            $dataPerusahaan = $this->db->table('m_perusahaan')->where('id',$datals->idPersh)->get()->getRow();
            $param['datals'] = $datals;
            $param['barangs'] = $this->db->table('tx_lsedtlhs')->where('idLs',$datals->id)->get()->getResult();
            $param['royaltis'] = $this->db->table('tx_lse_ntpn')->where('idLs',$datals->id)->where('noNtpn !=','SELISIHLEBIHMUAT')->get()->getResult();
            $param['kaloris'] = $this->db->table('tx_lse_kalori')->where('idLs',$datals->id)->get()->getResult();
            $param['asalbarangs'] = $this->db->table('tx_lse_ntpn')->select('npwp,nama,sum(royalti) as royalti, currency,namaProp')->where('idLs',$datals->id)->where('noNtpn !=','SELISIHLEBIHMUAT')->groupBy('npwp,currency,idProp')->groupBy('currency')->get()->getResult();
            $param['opsiCetak'] = $this->db->table('tx_opsi_cetak')->where('idData',$datals->id)->get()->getRow();
 
            $nptns = $this->db->table('tx_lse_ntpn')->where('idLs',$datals->id)->get()->getResult();
            if(count($nptns) > 0){
                foreach ($nptns as $key => $ntpn) {
                    if (strpos($ntpn->noNtpn, "LEBIH") == FALSE) { 
                    // if($ntpn->noNtpn !== 'SELISIHLEBIHMUAT'){
                        $param['noNtpn'][] = $ntpn->noNtpn;
                        $param['tglNtpn'][] = formatDate($ntpn->tglNtpn);
                    }
                }

                //$param['tglNtpn'] = array_map('reverseDate', $param['tglNtpn']);
            } else {
                $param['noNtpn'] = [];
                $param['tglNtpn'] = [];
            }


            $param['dataPerusahaan'] = $dataPerusahaan;

                        // from m_jenis_iup
            $arrayIUPOP = [1,2,3,5,7,8];
            $arrayIUPOPKPP = [4,6];

            $noIupOp = $tglIupOp = $noIupAngkutJual = $tglIupAngkutJual =NULL;
            if(in_array($datals->idJnsIUP,$arrayIUPOP)){
                $noIupOp = $datals->noIUP;
                $tglIupOp = $datals->tglIUP;
            }
            else if(in_array($datals->idJnsIUP,$arrayIUPOPKPP)){
                $noIupAngkutJual = $datals->noIUP;
                $tglIupAngkutJual = $datals->tglIUP;
            }
            $param['noIupOp'] = $noIupOp;
            $param['tglIupOp'] = $tglIupOp;
            $param['noIupAngkutJual'] = $noIupAngkutJual;
            $param['tglIupAngkutJual'] = $tglIupAngkutJual;

            // qrcode start
            $qrdata = $this->db->table('t_lse_qrcode')->where('idls',$idLs)->orderBy('id desc')->get()->getRow();
            if($qrdata)
            {
                $options = new QROptions;
                $options->eccLevel      = EccLevel::H;
                $options->version    = 10;

                $data   = QRCODE_DOMAIN.$qrdata->url;
                $qrcode = (new QRCode($options))->render($data);
                $param['qrcode'] = $qrcode;
            }
            // qrcode end

            $dompdf = new Dompdf();
            $dompdf->setPaper('legal', 'portrait');
            // $customPaper = array(0,0,360,360);
            // $dompdf->setPaper($customPaper);
            $content = $this->render('print.print_lse_batubara',$param);
            //echo $content;exit;
            $dompdf->loadHtml($content);
            $dompdf->render();

            if(!empty($datals->noLs))
                $filename = str_replace(array("\\", "/", ":", "*", "?", "\"", "<", ">", "|"), "-", $datals->noLs.'.pdf');
            else
                $filename = str_replace(array("\\", "/", ":", "*", "?", "\"", "<", ">", "|"), "-", $datals->draftNo.'.pdf');
            $this->response->setContentType('application/pdf');
            $dompdf->stream($filename,array("Attachment" => false));

        } catch (Exception $e) {

        }
    }
}

?>