(function ($) {
	"use strict";
	/* Start form wizard */
	$('#input-wizard').steps({
		headerTag: 'h4',
		bodyTag: 'div',
		autoFocus: true,
		titleTemplate: '<span class="number">#index#<\/span> <span class="title">#title#<\/span>',
		enableAllSteps: true,
		enableFinishButton: true,
		labels: {finish: "Next"}
	});
	$('a[href="#finish"]').parent().addClass('d-none disabled').attr("aria-disabled","true");
	$('a[href="#previous"]').parent().addClass('d-none disabled').attr("aria-disabled","true");
	$('a[href="#next"]').parent().addClass('d-none disabled').attr("aria-disabled","true");
	/* End form wizard */

})(jQuery);

$(document).ready(function() { 
	var idLs = $('#idData').val();
	var idJenisLS = $('#i_idJenisLS').val();
	getActionButton(idLs,idJenisLS);

	// initselectdua(element, url, filter='',minlength=3, parent='')
	initselectdua('.select-ttd',baseurl+'select/ttd','',0);
	initselectdua('.select-cabang',baseurl+'select/cabang','',0);
	initselectdua('.select-jenis-iup',baseurl+'select/jenisiup','',0);
	initselectdua('.select-propinsi',baseurl+'select/propinsi','',0);
	initselectdua('.select-kota',baseurl+'select/kota');
	initselectdua('.select-kota-ln',baseurl+'select/kota','ln');
	initselectdua('.select-negara',baseurl+'select/negara','',0);
	initselectdua('.select-incoterm',baseurl+'select/incoterm','',0);
	initselectdua('.select-port',baseurl+'select/port','',3);
	initselectdua('.select-port-id',baseurl+'select/port','id');
	initselectdua('.select-port-ln',baseurl+'select/port','ln',3);
	initselectdua('.select-moda',baseurl+'select/moda','',0);
	initselectdua('.select-currency',baseurl+'select/currency','',0);
	initselectdua('.select-satuan',baseurl+'select/satuan','',2);
	initselectdua('.select-eksportir',baseurl+'select/perusahaan','',2);
	initselectdua('#t_negaraPenerbit',baseurl+'select/negara','',0,'modalNewDok');
	initselectdua('#t_idJenisDok',baseurl+'select/jenisdok','',0,'modalNewDok');
	initselectdua('#p_unit',baseurl+'select/package','',0,'modalPackage');
	initselectdua('#cnt_jenis',baseurl+'select/container','',0,'modalContainer');
	// initselectdua('#k_postarif',baseurl+'select/hs',$("i_idJenisLS").val(),0);
	initselectdua('#k_ntpn',baseurl+'select/ntpn',$('#idData').val(),0);
	
	initselectdua('#t_negaraPenerbitNsw',baseurl+'select/negara','',0,'modalDokNsw'); 
	initselectdua('#t_idJenisDokNsw',baseurl+'select/jenisdok','',0,'modalDokNsw');
	initselectdua('#d_idJenisDok',baseurl+'select/jenisdok','',0,'modalPilihDok');
 
	$('#k_postarif').select2({ 
	  	ajax		: {
			type	: 'POST',
			url		: baseurl+'select/hs',
			delay	: 500,
			dataType: 'json',
	    	data	: function (params) {
	      	var query = {
						csrf_appls	: csrfName,
						filter		: $("#i_idJenisLS").val(),
						npwpPE1		: $("#i_npwp").val(),
						noPE		: $("#noPE").val(),
						tglPE		: $("#tglPE").val(),
						tglAkhirPE	: $("#tglAkhirPE").val(),
						idData		: $("#idData").val(),
	      	}
	     	 	return query;
	    	},
	    	processResults: function (data, params) {
				params.page = params.page || 1;
				return {
					results: data.data
				};
      		},cache: false
	  	}
	});
 
	if(idLs)
	{ 
		getDokumen(idLs);
		getKomoditas(idLs);
		getPackage(idLs);
		getContainer(idLs);
	}
	$('#input-wizard').removeClass('d-none');
	$('#viewFile').hide()
});

function getActionButton(idLs,idJenisLS)
{
	$.post( baseurl+'config/lse-action', { csrf_appls: csrfName, idLs:idLs, idJenisLS:idJenisLS})
  .done(function( resp ) {
  	$(".btn-action").remove();
    $('ul[aria-label=Pagination] li:nth-child(1)').after('<div class="d-flex justify-content-center gap-3 btn-action">'+resp.btn+'</div>');
  });
}

$('#i_idJenisLS').on('change', function (e) {
	var isPE = $('#i_idJenisLS option:selected').data('izin');
	if(isPE == 'Y'){
		$("#k_kdSatuanBarang").prop('disabled',true);
		$("#lbl-notif-spe").html('Otomatis berdasarkan data Izin');
		$("#lbl-lse-spe").html('Komoditas memerlukan SPE. Untuk kelancaran proses, pastikan anda sudah melaporkan realisasi ekspor bulan lalu dan kuota SPE anda masih mencukupi');
	}
	else{
		$("#k_kdSatuanBarang").prop('disabled',false);
		$("#lbl-notif-spe").html('');
		$("#lbl-lse-spe").html('');
	}
});

$('#k_postarif').on('select2:select', function (e) {
	var isPE = $('#i_idJenisLS option:selected').data('izin');
	var data = e.params.data;
	if(isPE == 'Y')
	{
		var newOption = new Option(data.jns_satuan+' - '+data.uraiSatuan, data.jns_satuan, true, true);
		$('#k_kdSatuanBarang').html(newOption).trigger('change');
		$("#k_kdSatuanBarang").prop('disabled',true);
	}
	else{
		$("#k_kdSatuanBarang").prop('disabled',false);
	}

});

$('#i_idPersh').on('select2:select', function (e) {
	var data = e.params.data;
	if(data.id){
		$("#i_npwp").val(data.npwp);
		$("#i_nitku").val(data.nitku);
		$("#i_nib").val(data.nib);

		if ($('#i_idJnsIUP').find("option[value='" + data.idJenisIup + "']").length) {
		    $('#i_idJnsIUP').val(data.idJenisIup).trigger('change');
		} else {
		    var newOption = new Option(data.jenisIUP, data.idJenisIup, true, true);
			$('#i_idJnsIUP').append(newOption).trigger('change');
		}

		if ($('#i_kdProp').find("option[value='" + data.idProp + "']").length) {
		    $('#i_kdProp').val(data.idProp).trigger('change');
		} else {
		    var newOption = new Option(data.namaProp, data.idProp, true, true);
			$('#i_kdProp').append(newOption).trigger('change');
		}

		if ($('#i_kdKota').find("option[value='" + data.idKab + "']").length) {
		    $('#i_kdKota').val(data.idKab).trigger('change');
		} else {
		    var newOption = new Option(data.namaKab, data.idKab, true, true);
			$('#i_kdKota').append(newOption).trigger('change');
		}

		$("#i_alamatPersh").val(data.alamat);
		$("#i_kodepos").val(data.kodePos);
	}
});


$(".accor-style2").click(function (e) {
	$(this).find('i').toggleClass( 'fe-chevrons-down fe-chevrons-up' );
});

$(document).on("click", ".btn-save", function (e) { // <-- see second argument of .on
  e.preventDefault();
  act = $(this).data('action');
  save(act);
});

$(document).on("click", ".btn-terbit", function (e) { // <-- see second argument of .on
  e.preventDefault();
  act = $(this).data('action');
  swal_confirm('Konfrimasi Penerbitan LS','Pastikan data yang di input sudah benar.',function (confirm) {
	    if (confirm) {
				save(act);
	    }
  });
});

function save(act)
{
	const callback = function (resp) {
		$(".btn-save").empty();

		if(resp.code == '00' && resp.data.act == 'SEND')
		{
			var urlRedirect = baseurl+'client/lse/proses';
			alertRedirect(resp,5000,urlRedirect);
		}
		else{
			if(resp.code == '00'){
				$('#idData').val(resp.data.id);
				var idLs = $('#idData').val();
				var idJenisLS = $('#i_idJenisLS').val();
				
				if(resp.data.fileUrl){
					$("#i_fileLS").val('');
					$("#span-view-ls").removeClass('d-none');
					$("#span-file-ls").removeClass('d-none').addClass('d-none');
				}
				else{
					$("#span-view-ls").removeClass('d-none').addClass('d-none');
					$("#span-file-ls").removeClass('d-none');
				}
			}
			showAlert(resp);
			getActionButton(idLs,idJenisLS);
		}

	}

	var param = $("#form-main").serializeArray()
	var postdata = JSON.stringify(param);
	// var ttd = $("#i_idTtd").select2('data');
	var cabang = $("#i_idCabang").select2('data');

	// var propEks = $("#i_kdProp").select2('data');
	// var kotaEks = $("#i_kdKota").select2('data');
	// var jenisIUP = $("#i_idJnsIUP").select2('data');
	var kotaImp = $("#i_kdKotaImportir").select2('data');
	var negaraImp = $("#i_kdNegaraImportir").select2('data');
	var negaraTransit = $("#i_kodeNegaraTransit").select2('data');
	var negaraTujuan = $("#i_kodeNegaraTujuan").select2('data');
	var incoterm = $("#i_kodeIncoterm").select2('data');
	var modaTransport = $("#i_kodeModaTransport").select2('data'); 
	var lokasiPeriksa = $("#i_kodeLokasiPeriksa").select2('data');
	var portMuat = $("#i_kodePortMuat").select2('data');
	var portTransit = $("#i_kodePortTransit").select2('data');
	var portTujuan = $("#i_kodePortTujuan").select2('data');
	var benderaKapal = $("#i_kodeBenderaKapal").select2('data');


	var formData = new FormData();
	formData.append("act", act);
	formData.append("postdata", postdata);
  
	if(cabang)
		formData.append("namaCabang", cabang[0].text);
 
	formData.append("jenisIUP", $("#jenisIUP").val());
  
	if(kotaImp){
		formData.append("kdKotaImportirInatrade", kotaImp[0].kodeInatrade);
		formData.append("kotaImportir", kotaImp[0].namaKota);
	}

	if(negaraImp)
		formData.append("negaraImportir", negaraImp[0].nama);
	if(negaraTransit)
		formData.append("negaraTransit", negaraTransit[0].nama);
	if(negaraTujuan)
		formData.append("negaraTujuan", negaraTujuan[0].nama);

	if(incoterm)
		formData.append("incoterm", incoterm[0].text);

	if(modaTransport)
		formData.append("modaTransport", modaTransport[0].text);

	if(lokasiPeriksa)
		formData.append("lokasiPeriksa", lokasiPeriksa[0].namaPort);
	if(portMuat)
		formData.append("portMuat", portMuat[0].namaPort);
	if(portTransit)
		formData.append("portTransit", portTransit[0].namaPort);
	if(portTujuan)
		formData.append("portTujuan", portTujuan[0].namaPort);
	if(benderaKapal)
		formData.append("benderaKapal", benderaKapal[0].nama);

	// formData.append('fileLS',$('#i_fileLS')[0].files[0]);
	
	var url = baseurl + "client/lse/save";

	// postAjax(url,postData,callbacks,dataType='json',processData=false)
	postAjax(url,formData,callback);
}

function upload_dok() { 
	var idDok 		 	= $('#idDok').val();

	if (!idDok) {
		var url 		= baseurl + "dokpersh/uploadDok";
		var fileUpload 	= $('#t_fileDok')[0].files[0];
	} else {
		var url 		= baseurl + "dokpersh/updeteDok";
		var fileUpload 	= $('#t_fileDokUpdate')[0].files[0];
	}
  
	if ($('#cbx-pilih').is(":checked"))
	{
		var status_pilih = 1;
	} else {
		var status_pilih = 0;
	}

	var param 			= $("#form-dokumen").serializeArray()
	var postdata 		= JSON.stringify(param); 
	var idData 			= $('#idData').val();
	var npwp 			= $('#i_npwp').val(); 
	var idPerusahaan	= $('#i_idPersh').val(); 
	var formData 		= new FormData();
	formData.append('postdata', postdata);
  	formData.append('fileDok', fileUpload);
  	formData.append('csrf_appls', csrfName);
  	formData.append('idData', idData);
  	formData.append('npwp', npwp);
  	formData.append('idPerusahaan', idPerusahaan);
	formData.append('status_pilih', status_pilih);

	$.ajax({
	   	method		: "POST",
	    url			: url,
	    data		: formData,
	    cache		: false,
	    contentType	: false,
	    processData	: false,
	    success		: function(resp){
	      	showAlert(resp);

			if(resp.code == '00'){
				$('#modalNewDok').modal('hide'); 

				$('#form-dokumen')[0].reset();
				$('#viewFile').hide();
				$('#uploadFile').show();
				 
				$('#idDok').val(''); 	
				$("#t_idJenisDok").empty().trigger('change');
				$("#t_negaraPenerbit").empty().trigger('change'); 
				
				getDokumen(idData); 
			}
	    },
	    error		: function(xhr, status, error) {
	    	console.log(xhr.responseText);
	    }
	  });
}
 
function showUploadDok()
{
	var idLs = $('#idData').val();
	var npwp = $("#i_npwp").val();
	
	if(!idLs){
		var error = {code:"99", msg:"Silahkan simpan Draft LS terlebih dahulu", text:"Gagal"};
		showAlert(error);
	} else {
		var modalNewDok = new bootstrap.Modal(document.getElementById('modalNewDok'))
		modalNewDok.show(); 

		$('#lbl-npwp-dok').text(npwp); 
	}
}
 
function showPilihDok() {   
	var idLs = $('#idData').val();

	if ($.fn.DataTable.isDataTable('#table-pilih-dok')) {
		$('#table-pilih-dok').DataTable().destroy();
	}

	if(idLs){
		var modalPilihDok = new bootstrap.Modal(document.getElementById('modalPilihDok'))
		modalPilihDok.show();

		var idLs = $("#idData").val();
		var npwp = $("#i_npwp").val();
		   
		new DataTable('#table-pilih-dok', {
			serverSide: true, 
			searching: true,
			ordering: false,
			processing: true,   
			scrollX: true, 
			paging:true,
			ajax: {
				url: baseurl + 'dokpersh/get_list',
				type: 'POST', 
				data: { idLs: idLs, npwp : npwp, csrf_appls: csrfName },   
			},
			"drawCallback": function (settings) {  
				var val 		= settings.json.idCheckbox; 
				var idAll 		= settings.json.idAll;  
				var idChecked 	= $("#idChecked").val();
				 
				if(idChecked == ''){
					$("#idChecked").val(val);
				} else {
					var arrServer = idAll.slice(0,-1).split(',');  

					for (var i = 0; i < arrServer.length; i++) { 
						var id 	 	 = arrServer[i];
						var status 	 = ""; 
						var formData = new FormData();
						formData.append('csrf_appls', csrfName);
						formData.append("idChecked", idChecked.slice(0,-1)); 
						formData.append("idDok", id);
						formData.append("status", 'cek');

						$.ajax({
							url			: baseurl + "dokpersh/check_dok",
							method		: 'POST',
							data		: formData, 
							processData	: false,
							contentType	: false, 
							async		: false,  
							success		: function(response){  
								if(response == '1'){  
									$("#ckbox-dok_"+i).attr('checked','checked');
								} else if(response == '0'){   
									$("#ckbox-dok_"+i).removeAttr('checked');
								} 
							},
							error: function(xhr, status, error) {
								console.log(error)
							}
						});  
					} 
				}
 
			},
			"columnDefs": [
				{ "width": "5%", "targets": 0 },
				{ "width": "5%", "targets": 1 },
				{ "width": "20%", "targets": 2 },
				{ "width": "10%", "targets": 3 },
				{ "width": "15%", "targets": 4 },
				{ "width": "10%", "targets": 5 },
				{ "width": "10%", "targets": 6 },
				{ "width": "20%", "targets": 7 }
			],
			"columns": [
				{ className: "text-center align-top" },
				{ className: "text-center align-top" },
				{ className: "text-nowrap align-top" },
				{ className: "text-nowrap align-top" },
				{ className: "text-nowrap align-top" },
				{ className: "align-top"},
				{ className: "align-top" }, 
				{ className: "text-center align-top" }
			],
		}); 

	} else {
		var error = {code:"99", msg:"Silahkan simpan Draft LS terlebih dahulu", text:"Gagal"};
		showAlert(error);
	} 
}

function pilihDokAct()
{
	const callback = function (resp) {
		showAlert(resp);
		if(resp.code == '00'){
			// getDokumen(resp.data.iddata);
			getDokumen($('#idData').val());
			$(".btn-dismiss-modalPilihDok").trigger('click');
			$('#modalPilihDok').modal('hide');
			$('#idChecked').val('');
		}
	}

	var checkedVals = $('.ckbox-dok:checkbox:checked').map(function() {
	    return $(this).data('iddata');
	}).get();
 
	var formData = new FormData();;
	formData.append('idData', $("#idData").val());
	formData.append('dokpilih', $("#idChecked").val().slice(0,-1));
	// formData.append('dokpilih',JSON.stringify(checkedVals)); 

	var url 	= baseurl + "client/lse/save_dok_pilih";
	postAjax(url,formData,callback);
}
  
function addKomoditas()
{
	const callback = function (resp) {
		if(resp.code == '00')
		{
			$(".btn-save-hs").removeClass('disabled d-none');
			$(".btn-update-hs").removeClass('disabled d-none').addClass('disabled d-none');
			$("#form-komoditas").trigger('reset');
			$("#k_postarif, #k_ntpn, #k_kdSatuanBarang, #k_kdNegaraAsal, #k_currencyHargaBarang").val('').trigger('change');
			getKomoditas(idLs);
		}

		showAlert(resp);
	}

	var idLs = $('#idData').val();
	if(!idLs){
		var error = {code:"99", msg:"Untuk dapat input NTPN, silahkan simpan Draft LS terlebih dahulu", text:"Tidak dapat tambah ntpn"};
		showAlert(error);
	}
	else{
		var param = $("#form-komoditas").serializeArray()
		var postdata = JSON.stringify(param);

		var formData = new FormData();

		formData.append("postdata", postdata);
		formData.append("idLs", idLs);

		var url = baseurl + "client/lse/add_komoditas";
		postAjax(url,formData,callback);
	}
}

function getKomoditas(idLs)
{
	const callback = function (resp) {
		$("#table-komoditas tbody").html(resp.content);
	}
	var formData = new FormData();
	formData.append("idLs", idLs);

	var url = baseurl + "client/lse/get_komoditas";
	postAjax(url,formData,callback);
}

function getDokumen(idLs)
{
	const callback = function (resp) {
		$("#tab-referensi tbody").html(resp.content);
	}

	var formData = new FormData();
	formData.append("idLs", idLs);

	var url = baseurl + "client/lse/get_list_dok";
	postAjax(url,formData,callback);
}

function del_dok_ref(idRef)
{
	const callback = function (resp) {
		showAlert(resp);
		getDokumen($('#idData').val())
	}

	swal_confirm('Konfirmasi hapus','Hapus dari pilihan referensi ?',function (confirm) {
	    if (confirm) {

			var formData = new FormData();
			formData.append("idRef", idRef);

			var url = baseurl + "client/lse/delete_dok_ref";
			postAjax(url,formData,callback);
	    }
    });
}

function del_dok_persh(idDok)
{
  const callback = function (resp) {
    showAlert(resp);
	
	$('#modalPilihDok').modal('hide');
	showPilihDok();
  }

  swal_confirm('Konfirmasi hapus','Hapus dokumen ? Setelah dihapus dokumen akan hilang dan tidak dapat dikembalikan',function (confirm) {
    if (confirm) {

	    var formData = new FormData();
	    formData.append("idDok", idDok);
	    formData.append("withdata", true);

	    postAjax(baseurl+'dokpersh/delete',formData,callback);
    }
  });
}

function view_dok_persh(idRef)
{
	$.redirect(baseurl+'dokpersh/view', {idRef: idRef, csrf_appls: csrfName}, "POST", "_blank");
}

function edit_hs(id)
{
	const callback = function (resp) {
		fillKomoditas(resp);
		$(".btn-save-hs").removeClass('disabled d-none').addClass('disabled d-none');
		$(".btn-update-hs").removeClass('disabled d-none');

		$('#komoditas_collapse').collapse('show');
		$('html, body').animate({
	        scrollTop: $("#form-komoditas").offset().top - 100
	    }, 200);
	}

	$(".btn-save-hs").removeClass('disabled d-none');
	$(".btn-update-hs").removeClass('disabled d-none').addClass('disabled d-none');
	$("#form-komoditas").trigger('reset');
	$("#k_postarif, #k_kdSatuanBarang, #k_kdNegaraAsal, #k_currencyHargaBarang").val('').trigger('change');
	$("#k_ntpn").empty().trigger('select2:change');

	swal_confirm('Konfirmasi','Edit komoditas ?',function (confirm) {
	    if (confirm) {

			var formData = new FormData();
			formData.append("id", id);
			formData.append("idLs", $("#idData").val());

			var url = baseurl + "client/lse/edit_hs";
			postAjax(url,formData,callback);
	    }
    });
}

function fillKomoditas(data)
{
	var newOption = new Option(data.hscode, data.postarif, true, true);
	$('#k_postarif').html(newOption).trigger('change');

	var newOption = new Option(data.kdSatuanBarang+' - '+data.uraiSatuanBarang, data.kdSatuanBarang, true, true);
	$('#k_kdSatuanBarang').html(newOption).trigger('change');

	var newOption = new Option(data.negaraAsal, data.kdNegaraAsal, true, true);
	$('#k_kdNegaraAsal').html(newOption).trigger('change');

	var newOption = new Option(data.currencyHargaBarang+' - '+data.uraiCurrency, data.currencyHargaBarang, true, true);
	$('#k_currencyHargaBarang').html(newOption).trigger('change');

	var arrOption = [];
    data.ntpns.forEach(function (item, index) {
      arrOption[index] = new Option(item.noNtpn, item.id, true, true);
    });
    $('#k_ntpn').html(arrOption).trigger('change');

	$("#idPosTarif").val(data.id);
	$("#k_jumlahBarang").val(data.jumlahBarang).trigger('keyup');
	$("#k_beratBersih").val(data.beratBersih).trigger('keyup');
	$("#k_uraianBarang").val(data.uraianBarang);
	$("#k_sepesifikasi").val(data.sepesifikasi);
	$("#k_noIup").val(data.noIup);
	$("#k_tglIup").val(data.tglIup);
	$("#k_hargaBarang").val(data.hargaBarang).trigger('keyup');
	$("#k_hargaBarangIdr").val(data.hargaBarangIdr).trigger('keyup');
	$("#k_hargaBarangUsd").val(data.hargaBarangUsd).trigger('keyup');
	$("#k_casNo").val(data.casNo);
	$("#k_icumsasNo").val(data.icumsasNo);
	$("#k_ashraeNo").val(data.ashraeNo);
}

function showModalPackage()
{
	var idLs = $('#idData').val();
	// var idLs = 1;
	if(!idLs){
		var error = {code:"99", msg:"Silahkan simpan Draft LS terlebih dahulu", text:"Gagal"};
		showAlert(error);
	}
	else{
		var modal = new bootstrap.Modal(document.getElementById('modalPackage'))
		modal.show();
	}
}

function add_package() {
	$(".btn-package").removeClass('disabled').addClass('disabled');
	const callback = function (resp) {
		$(".btn-package").removeClass('disabled');
		$("#form-package").trigger('reset');
		$("#p_unit, #idPackage").val('').trigger('change');
		showAlert(resp);
		getPackage($('#idData').val());
		$('#modalPackage').modal('hide');
	}

	var idData = $('#idData').val();
	var formData = new FormData();
  formData.append('csrf_appls', csrfName);
  formData.append('idLs',idData);
  formData.append('idPackage',$('#idPackage').val());
  formData.append('p_jml',$('#p_jml').val());
  formData.append('p_unit',$('#p_unit').val());
  formData.append('p_packageInfo',$('#p_packageInfo').val());

  var url = baseurl + "client/lse/add_package";
	postAjax(url,formData,callback);

}

function getPackage(idLs)
{
	const callback = function (resp) {
		$("#table-package tbody").html(resp.content);
	}

	var formData = new FormData();
	formData.append("idLs", idLs);

	var url = baseurl + "client/lse/get_package";
	postAjax(url,formData,callback);
}

function del_package(id)
{
	const callback = function (resp) {
		showAlert(resp);
		getPackage($("#idData").val());
	}

	swal_confirm('Konfirmasi Hapus','Hapus package ?',function (confirm) {
	  if (confirm) {

		var formData = new FormData();
		formData.append("id", id);
		formData.append("idLs", $("#idData").val());

		var url = baseurl + "client/lse/del_package";
		postAjax(url,formData,callback);
	  }
	});
}

function edit_package(id)
{
	const callback = function (resp) {
		$("#idPackage").val(resp.id);
		$("#p_jml").val(resp.jml);
		var newOption = new Option(resp.unit+' - '+resp.uraiUnit, resp.unit, true, true);
		$('#p_unit').append(newOption).trigger('change');
		$("#p_packageInfo").val(resp.packageInfo);

		var modal = new bootstrap.Modal(document.getElementById('modalPackage'))
		modal.show();
	}

	swal_confirm('Konfirmasi','Edit package ?',function (confirm) {
	    if (confirm) {
				var formData = new FormData();
				formData.append("id", id);
				formData.append("idLs", $("#idData").val());

				var url = baseurl + "client/lse/edit_package";
				postAjax(url,formData,callback);
		  }
    });
}

function showModalContainer()
{
	var idLs = $('#idData').val();
	if(!idLs){
		var error = {code:"99", msg:"Silahkan simpan Draft LS terlebih dahulu", text:"Gagal"};
		showAlert(error);
	}
	else{
		var modal = new bootstrap.Modal(document.getElementById('modalContainer'))
		modal.show();
	}
}


function add_container() {
	$(".btn-container").removeClass('disabled').addClass('disabled');
	const callback = function (resp) {
		$(".btn-container").removeClass('disabled');
		$("#form-container").trigger('reset');
		$("#cnt_jenis, #idContainer").val('').trigger('change');
		showAlert(resp);
		getContainer($('#idData').val());
		$('#modalContainer').modal('hide');
	}

	var idData = $('#idData').val();
	var formData = new FormData();
  formData.append('csrf_appls', csrfName);
  formData.append('idLs',idData);
  formData.append('idContainer',$('#idContainer').val());
  formData.append('cnt_jenis',$('#cnt_jenis').val());
  formData.append('cnt_nomor',$('#cnt_nomor').val());

  var url = baseurl + "client/lse/add_container";
	postAjax(url,formData,callback);

}

function getContainer(id)
{
	const callback = function (resp) {
		$("#table-container tbody").html(resp.content);
	}

	var idLs = $('#idData').val();
	var formData = new FormData();
	formData.append("idLs", idLs);

	var url = baseurl + "client/lse/get_container";
	postAjax(url,formData,callback);
}


function del_container(id)
{
	const callback = function (resp) {
		showAlert(resp);
		getContainer($("#idData").val());
	}

	swal_confirm('Konfirmasi Hapus','Hapus container ?',function (confirm) {
	  if (confirm) {

		var formData = new FormData();
		formData.append("id", id);
		formData.append("idLs", $("#idData").val());

		var url = baseurl + "client/lse/del_container";
		postAjax(url,formData,callback);
	  }
	});
}

function edit_container(id)
{
	const callback = function (resp) {
		$("#idContainer").val(resp.id);
		$("#cnt_nomor").val(resp.nomor);
		var newOption = new Option(resp.kode+' - '+resp.keterangan+' - '+resp.panjang+'X'+resp.tinggi+' Ft ', resp.idJenis, true, true);
		$('#cnt_jenis').append(newOption).trigger('change');

		var modal = new bootstrap.Modal(document.getElementById('modalContainer'))
		modal.show();
	}

	swal_confirm('Konfirmasi','Edit container ?',function (confirm) {
	    if (confirm) {
				var formData = new FormData();
				formData.append("id", id);
				formData.append("idLs", $("#idData").val());

				var url = baseurl + "client/lse/edit_container";
				postAjax(url,formData,callback);
		  }
    });
}


function del_hs(id)
{
	const callback = function (resp) {
		showAlert(resp);
		getKomoditas($("#idData").val());
	}

	swal_confirm('Konfirmasi Hapus','Hapus komoditas ?',function (confirm) {
	    if (confirm) {
				var formData = new FormData();
				formData.append("id", id);
				formData.append("idLs", $("#idData").val());

				var url = baseurl + "client/lse/delete_hs";
				postAjax(url,formData,callback);
	    }
    });
}

// 2023-10-30
function edit_dok_persh(id)
{
	const callback = function (resp) { 
		$('#modalPilihDok').modal('hide');

		let arrAwal 		= resp.tglDokumen.split("-");
		let tglAwal 		= arrAwal[2];
		let bulanAwal 		= arrAwal[1];
		let tahunAwal 		= arrAwal[0];
		let tglDokumen 		= tglAwal+'-'+bulanAwal+'-'+tahunAwal;

		let arrAkhir 		= resp.tglAkhirDokumen.split("-");
		let tglAkhir 		= arrAkhir[2];
		let bulanAkhir 		= arrAkhir[1];
		let tahunAkhir 		= arrAkhir[0];
		let tglAkhirDokumen = tglAkhir+'-'+bulanAkhir+'-'+tahunAkhir;

		let htmlView		= 	'<a class="btn d-w-md btn-warning" onclick="view_dok_persh('+ "'" + resp.id + "'" +')"><i class="fa fa-eye me-1"></i>View</a>';  
		var modalNewDok 	= new bootstrap.Modal(document.getElementById('modalNewDok'))
		modalNewDok.show();

		initselectdua('#t_idJenisDok',baseurl+'select/jenisdok','',0,'modalNewDok');
		initselectdua('#t_negaraPenerbit',baseurl+'select/negara','',0,'modalNewDok');

		$("#idDok").val(resp.id); 
 
		if ($('#t_idJenisDok').find("option[value='" + resp.idJenisDok + "']").length) {
			$('#t_idJenisDok').val(resp.idJenisDok).trigger('change'); 
		} else {  
			var newOption = new Option(resp.jenisDok, resp.idJenisDok, true, true); 
			$('#t_idJenisDok').append(newOption).trigger('change');
		} 

		if ($('#t_negaraPenerbit').find("option[value='" + resp.negaraPenerbit + "']").length) {
			$('#t_negaraPenerbit').val(resp.negaraPenerbit).trigger('change'); 
		} else {  
			var newOption = new Option(resp.nama_negara, resp.negaraPenerbit, true, true); 
			$('#t_negaraPenerbit').append(newOption).trigger('change');
		} 
  
		$("#t_noDokumen").val(resp.noDokumen);  
		$("#t_tglDokumen").val(tglDokumen); 
		$("#t_tglAkhirDokumen").val(tglAkhirDokumen); 
 
		$('#uploadFile').hide();
		$('#viewFile').show();
		$("#lingFile").html(htmlView);
	}

	swal_confirm('Konfirmasi','Edit dokumen referensi ?',function (confirm) {
	    if (confirm) {
				var formData = new FormData(); 
				formData.append("id", id);

				var url = baseurl + "dokpersh/edit_dok";
				postAjax(url,formData,callback);
		  }
    });
} 
  
$('#reset-form-upload, #close-form-upload').click(function () {
	$('#form-dokumen')[0].reset();
	$('#viewFile').hide();
	$('#uploadFile').show();
	 
	$('#idDok').val(''); 	
	$("#t_idJenisDok").empty().trigger('change');
	$("#t_negaraPenerbit").empty().trigger('change');
}); 
 
// 2023-11-17   
function resetFormKomoditas()
{
// $('#reset-form-komoditas').click(function () { 
	$('#form-komoditas')[0].reset(); 

	$('#idPosTarif').val('');
	$("#k_postarif").empty().trigger('change');
	$("#k_kdSatuanBarang").empty().trigger('change');
	$("#k_ntpn").empty().trigger('change');
	$("#k_kdNegaraAsal").empty().trigger('change');
	$("#k_currencyHargaBarang").empty().trigger('change');

	$(".btn-save-hs").removeClass('disabled d-none');
	$(".btn-update-hs").removeClass('disabled d-none').addClass('disabled d-none');
// });
}

function create_perubahan()
{
	swal_confirm('Konfrimasi Perubahan LS','Buat perubahan terhadap LS ini ? Data perubahan akan otomatis dibuat pada menu LS Konsep',function (confirm) {
	    if (confirm) {
	    	const callback = function (resp) {
					if(resp.code == '00'){
							$(".btn-cabut").addClass('disabled d-none');
							$(".btn-perubahan").addClass('disabled d-none');
							var urlRedirect = baseurl+'client/lse/konsep';
							alertRedirect(resp,5000,urlRedirect);
					}
					else{
						showAlert(resp);
					}
				}

				var formData = new FormData();
				formData.append('idLs',$("#idData").val());
				var url = baseurl + "client/lse/perubahan";
				postAjax(url,formData,callback);
	    }
  });
}
 
// 2023-11-27   
$(document).on("click", ".btn-delete-file", function (e) {
	swal_confirm('Konfrimasi Hapus','Hapus file LS ?.',function (confirm) {
	    if (confirm) {
	    	const callback = function (resp) {
					showAlert(resp);
					if(resp.code == '00'){
							$("#i_fileLS").val('');
							$("#span-view-ls").removeClass('d-none').addClass('d-none');
							$("#span-file-ls").removeClass('d-none');
					}
				}

				var formData = new FormData();
				formData.append('idLs',$("#idData").val());
				var url = baseurl + "client/lse/delete_file";
				postAjax(url,formData,callback);
	    }
  });
});

$(document).on("click", ".btn-view-file", function (e) {
	$.redirect(baseurl+'client/lse/view_file', {id: $("#idData").val(), csrf_appls: csrfName}, "POST", "_blank");
}); 
 
// 2024-01-31  
function ckbox_dok(row)
{ 
	var val 	  = $("#ckbox-dok_"+row).data('iddata');
	var idChecked = $('#idChecked').val().slice(0,-1);

	if($("#ckbox-dok_"+row).is(":checked"))
    {  
		var status = 'tambah'; 
    } else { 
		var status = 'hapus'; 
	}
 
	const callback = function (resp) {  
		$('#idChecked').val(resp);
	}
 
	var formData = new FormData();
	formData.append("idChecked", idChecked); 
	formData.append("idDok", val);
	formData.append("status", status);

	var url = baseurl + "dokpersh/check_dok";
	postAjax(url,formData,callback);
} 

$('#close-form-pilih').click(function () {  
	$('#idChecked').val('');   
}); 