<?php

namespace App\Controllers\Resources;

use CodeIgniter\RESTful\ResourceController;


class Config extends ResourceController
{
	public function lse_action()
    {
    	$statusProses = '';
    	$arrPermohonanOnline = [];
    	$config = config('App');
    	if(!empty($this->request->getPost('idLs'))){
	    	$idLs = decrypt_id($this->request->getPost('idLs'));
	    	$lsModel = model('tx_lseHdr')->find($idLs);
	    	$statusProses = $lsModel->statusProses;
	    	if( in_array($lsModel->idJenisLS, $arrPermohonanOnline) )
	    		$config->online_submission = true;
    	}

    	$btnAction = $this->form_action($statusProses);
    	$btn['btn'] = $btnAction;
    	return $this->response->setJSON($btn);
    }

	private function form_action($statusProses)
	{
		$userRole = session('sess_role');
		$config = config('App');

		$btnAction = new \stdClass();
 
		// kembali to halaman sebelumnya
		$btnAction->back 	= '	<li aria-hidden="false" aria-disabled="false">
									<a class="btn btn-dark btn-sm my-1" href="javascript:void(0)" onclick="GoBack();return false;" role="menuitem">
									<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-back-up-double" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
										<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
										<path d="M13 14l-4 -4l4 -4"></path>
										<path d="M8 14l-4 -4l4 -4"></path>
										<path d="M9 10h7a4 4 0 1 1 0 8h-1"></path>
									</svg> Kembali
									</a>
								</li>';

		// simpan menjadi status draft
		$btnAction->simpan 	= '	<li aria-hidden="false" aria-disabled="false">
									<a class="btn btn-secondary btn-sm my-1 btn-save" data-checkizin="N" data-action="'.encrypt_id('SAVE').'" href="javascript:void(0)" role="menuitem">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-device-floppy" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
											<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
											<path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" />
										</svg> Simpan Draft
									</a>
								</li>';

		// simpan dan kirim ke supervisor
		$btnAction->next 	= '	<li aria-hidden="false" aria-disabled="false">
									<a class="btn btn-primary btn-sm my-1 btn-save" data-checkizin="Y" data-action="'.encrypt_id('SEND').'" href="javascript:void(0)" role="menuitem">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-brand-telegram" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
											<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 10l-4 4l6 6l4 -16l-18 7l4 2l2 6l3 -4" />
										</svg> Kirim
									</a>
								</li>';

		// simpan dan kembalikan ke status refused
		$btnAction->tolak 	= '	<li aria-hidden="false" aria-disabled="false">
									<a class="btn btn-warning btn-sm my-1 btn-save" data-checkizin="N" data-action="'.encrypt_id('REFUSE').'" href="javascript:void(0)" role="menuitem">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ban" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
											<path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
											<path d="M5.7 5.7l12.6 12.6" />
										</svg> Tolak
									</a>
								</li>';

		// simpan dan update status terbit
		$btnAction->terbit 	= '	<li aria-hidden="false" aria-disabled="false">
									<a class="btn btn-success btn-sm my-1 btn-terbit" data-checkizin="Y" data-action="'.encrypt_id('ISSUED').'" href="javascript:void(0)" role="menuitem">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-mail-forward" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
											<path stroke="none" d="M0 0h24v24H0z" fill="none"/>
											<path d="M12 18h-7a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v7.5" />
											<path d="M3 6l9 6l9 -6" /><path d="M15 18h6" /><path d="M18 15l3 3l-3 3" />
										</svg> Terbit
									</a>
								</li>';

		// Pencabutan LS yang sudah terbit, hanya update status
		$btnAction->cabut 	= '	<li aria-hidden="false" aria-disabled="false">
									<a class="btn btn-danger btn-sm my-1 btn-revoke" data-checkizin="N" data-action="'.encrypt_id('REVOKE').'" href="javascript:void(0)" role="menuitem">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
											<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
											<path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
											<path d="M18 13.3l-6.3 -6.3"></path>
										</svg> Cabut
									</a>
								</li>';

		// action cetak
		$btnAction->cetak 	= '	<li aria-hidden="false" aria-disabled="false">
									<a onclick="print()" class="btn btn-info btn-sm my-1 btn-cetak" href="javascript:void(0)" role="menuitem">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-printer" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
											<path stroke="none" d="M0 0h24v24H0z" fill="none"/>
											<path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
											<path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
											<path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
										</svg> Cetak
									</a>
								</li>';

		if($statusProses == 'ISSUED'){
    		$btnAction->tolak = $btnAction->terbit = $btnAction->next = $btnAction->simpan = '';
    	}

    	if(in_array($userRole,[3,6])) // operator
    	{
    		if(!$config->online_submission)
    			$btnAction->tolak = '';

    		if($config->approve_spv)
	    		$btnAction->terbit = '';
	    	else
	    		$btnAction->next = '';

    		if($statusProses == 'REVIEW'){
    			$btnAction->next = $btnAction->simpan = '';
    		}

    	}
    	else if(in_array($userRole,[4,7])) // supervisor
    	{
    		// $btnAction->simpan = '';
    		$btnAction->next = '';

    		if(!$config->approve_spv){
	    		$btnAction->terbit = $btnAction->tolak = '';
    		}

    		if($statusProses == 'PROCESS'){
    			$btnAction->tolak = $btnAction->simpan = $btnAction->terbit = '';
    		}
    		else if($statusProses == 'REVIEW'){
    			if(!$config->spv_can_edit)
    				$btnAction->simpan = '';
    		}
    		else if($statusProses == 'REFUSED'){
    			$btnAction->tolak = $btnAction->simpan = $btnAction->terbit = '';
    		}
    	}

    	$btn = $btnAction->back.$btnAction->tolak.$btnAction->simpan.$btnAction->next.$btnAction->terbit.$btnAction->cetak;
    	return $btn;
	}
}