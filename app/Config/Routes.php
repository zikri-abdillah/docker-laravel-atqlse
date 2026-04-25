<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth\Login::index');
$routes->post('login/act', 'Auth\Login::login_act');
$routes->post('login/getCaptcha', 'Auth\Login::get_captcha');
$routes->get('logout', 'Auth\Login::logout');
$routes->get('registrasi', 'Auth\Login::registrasi');
$routes->post('registrasi/save', 'Auth\Login::save_registrasi');
$routes->get('forgot-password', 'Auth\Login::forgot_password');
 
$routes->get('filenotfound', 'Auth\Utils::filenotfound');
$routes->post('back', 'Auth\Utils::back');

// $routes->get('internal/beranda', 'Internal\Beranda::index');
// $routes->get('internal/admin/beranda', 'Internal\Admin\Beranda::index');
// $routes->get('client/beranda', 'Client\Beranda::index');


 
/**
 * Routes ekspor coal
 */
$routes->group('ekspor/coal/ls', static function ($routes) {
   $routes->get('/', 'Internal\Lse\Batubara\Ls::index/konsep');
   $routes->get('(input)', 'Internal\Lse\Batubara\Ls::$1');
   $routes->get('(konsep|proses|terbit)', 'Internal\Lse\Batubara\Ls::index/$1');
   $routes->post('save', 'Internal\Lse\Batubara\Ls::save');
   $routes->post('delete', 'Internal\Lse\Batubara\Ls::delete');
   $routes->post('delete_file', 'Internal\Lse\Batubara\Ls::delete_file');
   $routes->post('perubahan', 'Internal\Lse\Batubara\Ls::create_perubahan');
   $routes->post('view_file', 'Internal\Lse\Batubara\Ls::view_file');
   $routes->post('list/(konsep|proses|terbit)', 'Internal\Lse\Batubara\Ls::list/$1');
   $routes->post('(edit|view|batal|add_ntpn|add_komoditas|get_komoditas|get_ntpn|uploadDok|list_dok|save_dok_pilih|edit_dok_nsw|save_dok_nsw|get_list_dok|delete_dok_ref|del_ntpn|edit_ntpn|delete_hs|edit_hs|add_package|get_package|del_package|edit_package|add_container|get_container|edit_container|del_container|updeteDok|add_kalori|get_kalori|edit_kalori|delete_kalori|rollback)', 'Internal\Lse\Batubara\Ls::$1');
});

$routes->group('ekspor/coal/ls/pengajuan', static function ($routes) {
   $routes->get('/', 'Internal\Lse\Batubara\Permohonan::index');
   $routes->post('list', 'Internal\Lse\Batubara\Permohonan::list');
   $routes->post('view', 'Internal\Lse\Batubara\Permohonan::view');
   $routes->post('create-ls', 'Internal\Lse\Batubara\Permohonan::create_ls');
});
  
$routes->group('ekspor/rekapitulasi/lse', static function ($routes) {
   $routes->get('/', 'Internal\Lse\Rekapitulasi\Lse::index/terbit');   
   $routes->post('list', 'Internal\Lse\Rekapitulasi\Lse::list'); 
   $routes->post('export', 'Internal\Lse\Rekapitulasi\Excel::index');
});

$routes->group('ekspor/rekapitulasi/laporan-bulanan', static function ($routes) {
   $routes->get('/', 'Internal\Lse\Rekapitulasi\Bulanan::index');
   $routes->post('list', 'Internal\Lse\Rekapitulasi\Bulanan::list');
   $routes->post('draft-summary', 'Internal\Lse\Rekapitulasi\Bulanan::draft_summary');
   $routes->post('save-check', 'Internal\Lse\Rekapitulasi\Bulanan::save_check');
   $routes->post('save-draft', 'Internal\Lse\Rekapitulasi\Bulanan::save_draft');
   $routes->post('delete', 'Internal\Lse\Rekapitulasi\Bulanan::delete');
   $routes->post('detail', 'Internal\Lse\Rekapitulasi\Bulanan::list_detail');
   $routes->post('edit', 'Internal\Lse\Rekapitulasi\Bulanan::edit');
   $routes->post('delete-file', 'Internal\Lse\Rekapitulasi\Bulanan::delete_file');
   $routes->post('attach', 'Internal\Lse\Rekapitulasi\Bulanan::view_file');
   $routes->post('print', 'Internal\Lse\Rekapitulasi\Bulanan::print');
});


/**
 * Routes master data
 */
$routes->group('management/client', static function ($routes) {
   $routes->get('/', 'Internal\Master\Client::index');  
   $routes->post('list', 'Internal\Master\Client::list');   
   $routes->get('(add)', 'Internal\Master\Client::$1'); 
   $routes->post('(save|edit|delete|detail|listDok)', 'Internal\Master\Client::$1');
   $routes->post('change-status', 'Internal\Master\Client::change_status'); 
});
 
$routes->group('management/cabang', static function ($routes) {
   $routes->get('/', 'Internal\Master\Cabang::index');  
   $routes->post('list', 'Internal\Master\Cabang::list');   
   $routes->post('(save|edit|delete)', 'Internal\Master\Cabang::$1');  
});

$routes->group('management/user', static function ($routes) {
   $routes->get('/', 'Internal\Master\User::index');      
   $routes->get('add', 'Internal\Master\User::add');   
   $routes->post('(list|save|edit|delete|detail|change_status)', 'Internal\Master\User::$1');  
   $routes->post('save-pu', 'Internal\Master\User::save_pu');
});

$routes->group('management/penandatangan', static function ($routes) {
   $routes->get('/', 'Internal\Master\Penandatangan::index');  
   $routes->post('list', 'Internal\Master\Penandatangan::list'); 
   $routes->post('(save|edit|delete)', 'Internal\Master\Penandatangan::$1');  
});

$routes->group('management/kota', static function ($routes) {
   $routes->get('/', 'Internal\Master\Kota::index');  
   $routes->post('list', 'Internal\Master\Kota::list'); 
   $routes->post('(save|edit|delete)', 'Internal\Master\Kota::$1');  
});

$routes->group('management/npwp', static function ($routes) {
   $routes->get('/', 'Internal\Master\Npwp::index');
   $routes->post('list', 'Internal\Master\Npwp::list');
   $routes->post('(save|edit|delete)', 'Internal\Master\Npwp::$1');
});

/**
 * Routes services
 */
$routes->group('services/checkizin', static function ($routes) {
   $routes->get('/', 'Internal\Services\Izin::index');
   $routes->post('(act)', 'Internal\Services\Izin::$1');
});

$routes->group('services/checkntpn', static function ($routes) {
   $routes->get('/', 'Internal\Services\Ntpn::index');
   $routes->post('(act)', 'Internal\Services\Ntpn::$1');
});

$routes->group('services/rekap', static function ($routes) {
   $routes->get('/', 'Internal\Services\Rekap::index');
   $routes->get('act', 'Internal\Services\Rekap::$1');
});

$routes->group('services/asuransi', static function ($routes) {
   $routes->get('(find|survey)', 'Internal\Services\Asuransi::$1');
   $routes->post('(actfind|actsurvey)', 'Internal\Services\Asuransi::$1');
});

$routes->group('services/sendsimbara', static function ($routes) {
   $routes->get('/', 'Internal\Services\Batubara::sendsimbara');
});

$routes->group('services/laporan', static function ($routes) {
   $routes->post('send', 'Internal\Services\Laporan_bulanan::send');
});

$routes->group('services/xml', static function ($routes) {
   $routes->post('coal/send_inatrade', 'Internal\Services\Batubara_xml::send_inatrade');
   $routes->get('coal/test_send_inatrade', 'Internal\Services\Batubara_xml::test_send_inatrade');
   //$routes->get('/', 'Internal\Services\Batubara::sendsimbara');
   $routes->post('mineral/send_inatrade', 'Internal\Services\Mineral_xml::send_inatrade');
   $routes->get('mineral/test_send_inatrade', 'Internal\Services\Mineral_xml::test_send_inatrade');
});


// send inatrade json
$routes->group('services/coal', static function ($routes) {
   $routes->post('send_inatrade', 'Internal\Services\Batubara::send_inatrade');
   $routes->get('test_send_doc_simbara', 'Internal\Services\Batubara::test_send_doc_simbara');
   $routes->post('test_send_doc_simbara', 'Internal\Services\Batubara::test_send_doc_simbara');
   //$routes->get('/', 'Internal\Services\Batubara::sendsimbara');
   // $routes->post('mineral/send_inatrade', 'Internal\Services\Batubara::send_inatrade');
   // $routes->get('mineral/test_send_inatrade', 'Internal\Services\Batubara::test_send_inatrade');
});

/**
 * Routes api (server)
 */
$routes->post('api/(check_izin)', 'Resources\Api::$1');
$routes->get('api/rekap/mkt/(:any)/(:any)/(:any)', 'Resources\Api::rekap_mkt/$1/$2/$3');
//$routes->get('file/lse', 'Resources\File::lse');
$routes->get('file/lse/(:any)', 'Resources\File::lse/$1');
$routes->get('file/pendukung/(:any)', 'Resources\File::pendukung/$1');
$routes->get('view/file/(:any)', 'Resources\File::lse/$1');
$routes->get('docref/(:any)', 'Resources\File::pendukung/$1');
$routes->get('docfile/(:any)', 'Resources\File::file_v1/$1'); // handle link file ls v1
$routes->get('summary/(:any)', 'Resources\File::file_rekap_v1/$1'); // handle rekap file ls v1
$routes->get('rekap-bulanan/(:any)', 'Resources\File::laporan_bulanan/$1'); // handle rekap file ls v1

$routes->group('api/simbara', static function ($routes) {
   $routes->post('kirimPermohonanSurveyor', 'Resources\Simbara\Permohonan::received');
   $routes->post('kirimDokumenTambahan', 'Resources\Simbara\Dokumen::doktambahan');
   $routes->get('getResponseSurveyor', 'Resources\Simbara\Status::get');
   $routes->post('sendResponseSurveyor', 'Resources\Simbara\Status::send');
});

$routes->group('rest/serv', static function ($routes) {
   $routes->post('kirimPermohonanSurveyor', 'Resources\Simbara\Permohonan::received');
   $routes->post('kirimDokumenTambahan', 'Resources\Simbara\Dokumen::doktambahan');
   $routes->get('getResponseSurveyor', 'Resources\Simbara\Status::get');
   $routes->post('sendResponseSurveyor', 'Resources\Simbara\Status::send');
});

/**
 * Routes api (client)
 */
$routes->group('api/simbara', static function ($routes) {
   $routes->get('send-status/(:any)', 'Internal\Services\Simbara_send_status::send/$1');
   $routes->post('send-pengembalian', 'Internal\Services\Simbara_send_status::send_pengembalian');
});

/** 
 * Routes Client
 */ 
$routes->group('client/lse', static function ($routes) { 
   $routes->get('/', 'Client\Lse::index/konsep');
   $routes->get('(draft|konsep|proses|terbit)', 'Client\Lse::index/$1'); 
   $routes->post('list/(draft|konsep|proses|terbit)', 'Client\Lse::list/$1');

   $routes->get('(input)', 'Client\Lse::$1');
   $routes->post('perubahan', 'Client\Lse::create_perubahan'); 
   $routes->post('(save|view|edit|delete|view_file|get_list_dok|save_dok_pilih|delete_dok_ref|add_package|get_package|del_package|edit_package|add_container|get_container|edit_container|del_container|add_komoditas|get_komoditas|delete_hs|edit_hs)', 'Client\Lse::$1'); 
   
   $routes->get('online', 'Client\Permohonan::index');
   $routes->post('online/list', 'Client\Permohonan::list');
   $routes->post('online/view', 'Client\Permohonan::view');
});
 
$routes->group('profile', static function ($routes) {  
   $routes->get('/', 'Client\Profile::index');
   $routes->get('edit', 'Client\Profile::edit');    
   $routes->post('(save|view|edit|savepu)', 'Client\Profile::$1'); 
});
 
$routes->group('beranda', static function ($routes) {
   $routes->get('internal', 'Internal\Beranda::internal');
   $routes->get('admin', 'Internal\Beranda::admin');
   $routes->get('client', 'Internal\Beranda::client');
   $routes->post('pengajuan-terakhir', 'Internal\Beranda::get_pengajuan_terakhir');
   $routes->post('penerbitan-terakhir', 'Internal\Beranda::get_penerbitan_terakhir');
   $routes->post('user-waiting', 'Internal\Beranda::get_user_waiting');
   $routes->post('view-lse', 'Internal\Beranda::view_lse');
   
});
 
$routes->post('select/(perusahaan|ttd|cabang|jenisiup|propinsi|kota|negara|incoterm|port|moda|currency|satuan|negara|jenisdok|hs|ntpn|package|container|role|type|seribarang|jenisls)', 'Resources\Select::$1');
$routes->post('dokpersh/(view|delete|get_list|edit_dok|uploadDok|updeteDok|check_dok)', 'Resources\Dokpersh::$1');
$routes->post('config/lse-action', 'Resources\Config::lse_action');
 
$routes->post('log/view_log', 'Resources\Log::view_log');
$routes->post('log/view-log-lnsw', 'Resources\Log::view_log_lnsw');
$routes->get('log/file/(:any)/(:any)/(:any)', 'Resources\File::log_file/$1/$2/$3');


/**
 * Routes print
 */
$routes->group('print', static function ($routes) {
   // $routes->post('mineral/pve', 'Print\Mineral::pve');
   // $routes->post('mineral/lse', 'Print\Mineral::lse');

   //$routes->post('mineral/pve', 'Print\Mineral::pve');
   $routes->post('coal/lse', 'Print\Batubara::lse');
});

$routes->get('lse/page/(:alphanum)', 'Client\Verify::index/$1');

// COA
$routes->group('ekspor/coa', static function ($routes) {
   $routes->get('/', 'Internal\Lse\Batubara\Coa::index');
   $routes->get('input', 'Internal\Lse\Batubara\Coa::input');
   $routes->post('ls-suggestions', 'Internal\Lse\Batubara\Coa::lsSuggestions');
   $routes->post('ls-reference', 'Internal\Lse\Batubara\Coa::lsReference');
   $routes->post('delete-file', 'Internal\Lse\Batubara\Coa::delete_file');
   $routes->post('perubahan', 'Internal\Lse\Batubara\Coa::create_perubahan');
   $routes->post('pembatalan', 'Internal\Services\Coa::cancel');
   $routes->post('send', 'Internal\Services\Coa::send');
   $routes->post('(list|save|edit|view|delete)', 'Internal\Lse\Batubara\Coa::$1');
});

// cow
$routes->group('ekspor/cow', static function ($routes) {
   $routes->get('/', 'Internal\Lse\Batubara\Cow::index');
   $routes->get('input', 'Internal\Lse\Batubara\Cow::input');
   $routes->post('ls-suggestions', 'Internal\Lse\Batubara\Cow::lsSuggestions');
   $routes->post('ls-reference', 'Internal\Lse\Batubara\Cow::lsReference');
   $routes->post('delete-file', 'Internal\Lse\Batubara\Cow::delete_file');
   $routes->post('perubahan', 'Internal\Lse\Batubara\Cow::create_perubahan');
   $routes->post('pembatalan', 'Internal\Services\Cow::cancel');
   $routes->post('send', 'Internal\Services\Cow::send');
   $routes->post('(list|save|edit|view|delete)', 'Internal\Lse\Batubara\Cow::$1');
});

$routes->get('doc/coa/(:any)', 'Resources\File::coa/$1');
$routes->get('doc/cow/(:any)', 'Resources\File::cow/$1');