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

	// mark mandatory input
	if(typeof mandatoryFields !== 'undefined'){

				if(typeof mandatoryFields['HEADER'] !== 'undefined')
				{
					for (var i = 0; i < mandatoryFields['HEADER'].length; i++) {
						$("#i_"+mandatoryFields['HEADER'][i]).parent().parent().children('.form-label').addClass('mandatory');
					}
				}

				if(typeof mandatoryFields['KOMODITAS'] !== 'undefined')
				{
					for (var i = 0; i < mandatoryFields['KOMODITAS'].length; i++) {
						$("#k_"+mandatoryFields['KOMODITAS'][i]).parent().parent().children('.form-label').addClass('mandatory');
					}
				}

				if(typeof mandatoryFields['NTPN'] !== 'undefined')
				{
					for (var i = 0; i < mandatoryFields['NTPN'].length; i++) {
						$("#n_"+mandatoryFields['NTPN'][i]).parent().parent().children('.form-label').addClass('mandatory');
					}
				}
	}

	$('.mandatory').append('<span class="text-danger"> <i class="fa fa-star"></i><span>')
	// end mark mandatory input

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
	initselectdua('#t_negaraPenerbitNsw',baseurl+'select/negara','',0,'modalDokNsw');
	initselectdua('#t_idJenisDok',baseurl+'select/jenisdok','',0,'modalNewDok');
	initselectdua('#t_idJenisDokNsw',baseurl+'select/jenisdok','',0,'modalDokNsw');
	initselectdua('#p_unit',baseurl+'select/package','',0,'modalPackage');
	initselectdua('#cnt_jenis',baseurl+'select/container','',0,'modalContainer');
	initselectdua('#k_postarif',baseurl+'select/hs','1',0);
	initselectdua('#k_ntpn',baseurl+'select/ntpn',$('#idData').val(),0);
	initselectdua('#kal_seriBarang',baseurl+'select/seribarang',$('#idData').val(),0);

	if(idLs)
	{
		getKalori(idLs);
		getNTPN(idLs);
		getDokumen(idLs);
		getKomoditas(idLs);
		getPackage(idLs);
		getContainer(idLs);
	}
	$('#input-wizard').removeClass('d-none');
	$('#viewFile').hide();

	// $.post( baseurl + "ekspor/coal/ls/get_mandatory_input", { csrf_appls: csrfName, section:'header'})
  // .done(function( resp ) {
  // 	//console.log(resp);
  // 	for (const property in resp) {
	// 	  console.log(resp[property]);
	// 	}
  // });

});

function getActionButton(idLs,idJenisLS)
{
	$.post( baseurl+'config/lse-action', { csrf_appls: csrfName, idLs:idLs, idJenisLS:idJenisLS})
  .done(function( resp ) {
  	$(".btn-action").remove();
    $('ul[aria-label=Pagination] li:nth-child(1)').after('<div class="d-flex justify-content-center gap-3 btn-action">'+resp.btn+'</div>');
  });
}

$('#i_idPersh').on('select2:select', function (e) {
	var data = e.params.data;
	if(data.id){
		$("#i_npwp").val(data.npwp);
		$("#i_npwp16").val(data.npwp16);
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
		$("#i_noIUP").val(data.noIUP);
		
		if(data.tglIUP !== ''){
			var date = data.tglIUP.split("-");
			var tgl = date[2] + '-' + date[1] + '-' + date[0];
 
			$("#i_tglIUP").val(tgl);
		}
		
		$("#i_npwp").trigger('change');
		$("#i_npwp16").trigger('change');
	}
});


$(".accor-style2").click(function (e) {
	$(this).find('i').toggleClass( 'fe-chevrons-down fe-chevrons-up' );
});

$(document).on("click", ".btn-save", function (e) { // <-- see second argument of .on
  e.preventDefault();
  var  act = $(this).data('action');
  var checkIzin = $(this).data('checkizin');
  save(act,checkIzin);
});

$(document).on("click", ".btn-terbit", function (e) { // <-- see second argument of .on
  e.preventDefault();
  var act = $(this).data('action');
  var checkIzin = $(this).data('checkizin');
  swal_confirm('Konfrimasi Penerbitan LS','Pastikan data yang di input sudah benar.',function (confirm) {
	    if (confirm) {
				save(act,checkIzin);
	    }
  });
});

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
				var url = baseurl + "ekspor/coal/ls/delete_file";
				postAjax(url,formData,callback);
	    }
  });
});

$(document).on("click", ".btn-view-file", function (e) {
	$.redirect(baseurl+'ekspor/coal/ls/view_file', {id: $("#idData").val(), csrf_appls: csrfName}, "POST", "_blank");
});

$(document).on("change", "#i_tglLs", function (e) {
	var tglAwalInput = $(this).val();
	if(tglAwalInput.length == 10)
	{
	  // Parsing input tanggal awal dengan format "dd-mm-yyyy"
	  var tglAwalParts = tglAwalInput.split("-");
	  var tglAwal = new Date(tglAwalParts[2], tglAwalParts[1] - 1, tglAwalParts[0]); // Bulan dimulai dari 0

	  var tglAkhir = new Date(tglAwal);
	  // Menetapkan tanggal yang sama di bulan selanjutnya
	  tglAkhir.setMonth(tglAkhir.getMonth() + 1);

	  // Format tanggal akhir menjadi string "dd-mm-yyyy"
	  var tanggal = ('0' + tglAkhir.getDate()).slice(-2);
	  var bulan = ('0' + (tglAkhir.getMonth() + 1)).slice(-2); // Tambah 1 karena Januari dimulai dari 0
	  var tahun = tglAkhir.getFullYear();
	  var tanggalString = tanggal + '-' + bulan + '-' + tahun;
	 }
	 else
	 	var tanggalString = '';

  $("#i_tglAkhirLs").val(tanggalString);
});

function save(act,checkIzin)
{
	if(checkIzin == 'Y'){
		// var npwp = $("#i_npwp").val();
		// var nib = $("#i_nib").val();
		// var idtku = $("#i_nitku").val();

		// var noIzin = $("#noET").val();
		// var tglIzin = $("#tglET").val();
		// var tglAkhirIzin = $("#tglAkhirET").val();
		// var probis = 'E';

		// const callbackCheck = function (respCheck) {
		// 	if(respCheck.code == '99')
	  //   	showAlert(respCheck);
	  //   else
	  //   	save_act(act)
  	// }

		// var formData = new FormData();
	  // formData.append("nib", nib);
	  // formData.append("npwp", npwp);
	  // formData.append("idtku", idtku);
	  // formData.append("probis", probis);
	  // formData.append("noIzin", noIzin);
	  // formData.append("tglIzin", tglIzin);

	  // var url = baseurl + "services/checkizin/act";
	  // postAjax(url,formData,callbackCheck);

	  //alert('check izin di non aktifkan sampai live');
	  save_act(act);
	}
	else{
		save_act(act);
	}


}

function save_act(act){

		const callback = function (resp) {
			$(".btn-save").empty();

			var idLs = $('#idData').val();
			var idJenisLS = $('#i_idJenisLS').val();

			if(resp.code == '00' && resp.data.act == 'ISSUED')
			{
				var urlRedirect = baseurl+'ekspor/coal/ls/terbit';
				alertRedirect(resp,5000,urlRedirect);
			}
			else if(resp.code == '00' && resp.data.act == 'SEND')
			{
				var urlRedirect = baseurl+'ekspor/coal/ls/proses';
				alertRedirect(resp,5000,urlRedirect);
			}
			else{
				if(resp.code == '00'){
					$('#idData').val(resp.data.id);
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

			initselectdua('#k_ntpn',baseurl+'select/ntpn',$('#idData').val(),0);
		}

		var param = $("#form-main").serializeArray()
		var postdata = JSON.stringify(param);
		var ttd = $("#i_idTtd").select2('data');
		var cabang = $("#i_idCabang").select2('data');

		var propEks = $("#i_kdProp").select2('data');
		var kotaEks = $("#i_kdKota").select2('data');
		var jenisIUP = $("#i_idJnsIUP").select2('data');
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

		if(ttd)
			formData.append("namaTtd", ttd[0].text);

		if(cabang)
			formData.append("namaCabang", cabang[0].text);

		if(jenisIUP)
			formData.append("jenisIUP", jenisIUP[0].text);

		if(propEks){
			formData.append("kdPropInatrade", propEks[0].kodeInatrade);
			formData.append("namaProp", propEks[0].text);
		}
		if(kotaEks){
			formData.append("kdKotaInatrade", kotaEks[0].kodeInatrade);
			if(kotaEks[0].namaKota)
				formData.append("namaKota", kotaEks[0].namaKota);
			else
				formData.append("namaKota", $("#i_kdKota").find(':selected').data('post'));
		}

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

		formData.append('fileLS',$('#i_fileLS')[0].files[0]);

		var url = baseurl + "ekspor/coal/ls/save";

		// postAjax(url,postData,callbacks,dataType='json',processData=false)
		postAjax(url,formData,callback,'json',false);

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

				$(".lbl-noET").html(resp.data.noET);
				$(".lbl-tglET").html(resp.data.tglET + ' s.d ' + resp.data.tglAkhirET);

 				$("#i_tglET").val(resp.data.tglET);
				$("#i_tglAkhirET").val(resp.data.tglAkhirET); 

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

function showUploadDokNsw(id)
{
	var idLs = $('#idData').val();
	if(!idLs){
		var error = {code:"99", msg:"Silahkan simpan Draft LS terlebih dahulu", text:"Gagal"};
		showAlert(error);
	}
	else{

		const callback = function (resp) {
			if(resp.code == '00'){
				var modalDokNsw = new bootstrap.Modal(document.getElementById('modalDokNsw'))
				modalDokNsw.show();


				$("#idDokNSW").val(resp.data.docs.id);
				$("#lbl-jenisdok-nsw").html(resp.data.docs.jenisDokumen);
				if(resp.data.docs.negaraPenerbit){
					var newOption = new Option(resp.data.docs.uraiNegaraPenerbit, resp.data.docs.negaraPenerbit, true, true);
					$('#t_negaraPenerbitNsw').append(newOption).trigger('change');
				}
				$("#lbl-nodok-nsw").html(resp.data.docs.nomorDokumen);
				$("#lbl-tgldok-nsw").html(resp.data.docs.tglDok);
				$("#t_tglAkhirDokumenNsw").val(resp.data.docs.tglAkhirDok);
				$("#lbl-file-nsw").html('<a class="btn btn-primary" href="'+resp.data.docs.urlDokumen+'" role="button" target="_blank">Lihat</a>');

				if(resp.data.docs.negaraPenerbit){
					var newOption = new Option(resp.data.docs.uraiNegaraPenerbit, resp.data.docs.negaraPenerbit, true, true);
					$('#t_idJenisDokNsw').append(newOption).trigger('change');
				}
			}
			else{
				showAlert(resp);
			}
		}

		var formData = new FormData();;
		formData.append('id',id);
		var url = baseurl + "ekspor/coal/ls/edit_dok_nsw";
		postAjax(url,formData,callback);
	}
}

function save_dok_nsw()
{
	const callback = function (resp) {
		showAlert(resp);
		if(resp.code == '00'){
			getDokumen($('#idData').val())
			$(".btn-close-DokNsw").trigger('click');
			$('#modalDokNsw').modal('hide');
		}
	}

		var param = $("#form-dokumen-insw").serializeArray()
		var postdata = JSON.stringify(param);
		var formData = new FormData();
		formData.append("postdata", postdata);
		var url = baseurl + "ekspor/coal/ls/save_dok_nsw";
		postAjax(url,formData,callback);
}

function pilihDokAct()
{
	const callback = function (resp) {
		showAlert(resp);
		if(resp.code == '00'){
			// getDokumen(resp.data.iddata);
			getDokumen($('#idData').val()); 
			$(".lbl-noET").html(resp.data.noET);
			$(".lbl-tglET").html(resp.data.tglET + ' s.d ' + resp.data.tglAkhirET);
			
			$("#i_tglET").val(resp.data.tglET);
			$("#i_tglAkhirET").val(resp.data.tglAkhirET); 
			 
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

	var url 	= baseurl + "ekspor/coal/ls/save_dok_pilih";
	postAjax(url,formData,callback);
}

function addNTPN()
{
	const callback = function (resp) {
		if(resp.code == '00')
		{
			$(".btn-save-ntpn").removeClass('disabled d-none');
			$(".btn-update-ntpn").removeClass('disabled d-none').addClass('disabled d-none');
			$("#form-ntpn").trigger('reset');
			$("#n_idJnsIUP, #n_kdSatuan, #n_idProp, #n_currency").val('').trigger('change');
			$('#n_volume').prop('readonly', false);

			resetFormNtpn();
			getNTPN(idLs);
		}
		
		showAlert(resp);
	}

	var idLs = $('#idData').val();
	// var idLs = 1;
	if(!idLs){
		var error = {code:"99", msg:"Silahkan simpan Draft LS terlebih dahulu", text:"Tidak dapat tambah ntpn"};
		showAlert(error);
	}
	else{
		var param = $("#form-ntpn").serializeArray()
		var postdata = JSON.stringify(param);

		var formData = new FormData();


		var iup = $("#n_idJnsIUP").select2('data');
		if(iup.length > 0){
			formData.append("jenisIUP", iup[0].text);
		}

		var propinsi = $("#n_idProp").select2('data');
		if(propinsi.length > 0){
			formData.append("kdPropInatrade", propinsi[0].kodeInatrade);
			formData.append("namaProp", propinsi[0].text);
		}

		var satuan = $("#n_kdSatuan").select2('data');
		if(satuan.length > 0){
			formData.append("uraiSatuan", satuan[0].uraiSatuan);
		}

		formData.append("postdata", postdata);
		formData.append("idLs", idLs);

		var url = baseurl + "ekspor/coal/ls/add_ntpn";
		postAjax(url,formData,callback);
	}
}

function del_ntpn(id)
{
	const callback = function (resp) {
		showAlert(resp);
		getNTPN($("#idData").val());
	}

	swal_confirm('Konfirmasi Hapus','Hapus ntpn ?',function (confirm) {
	    if (confirm) {

			var formData = new FormData();
			formData.append("id", id);
			formData.append("idLs", $("#idData").val());

			var url = baseurl + "ekspor/coal/ls/del_ntpn";
			postAjax(url,formData,callback);
	    }
  });
}

function edit_ntpn(id,idNtpnNsw)
{
	const callback = function (resp) {

		// if(idNtpnNsw)
		// {
		// 	$(".input-n-npwp").prop('disabled',true);
		// 	$(".input-n-npwp").removeClass('d-none').addClass('d-none');
		// }
		// else{
		// 	$(".select-n-npwp").prop('disabled',false);
		// 	$(".select-n-npwp").removeClass('d-none');
		// }

		fillNTPN(resp);
		$(".btn-save-ntpn").removeClass('disabled d-none').addClass('disabled d-none');
		$(".btn-update-ntpn").removeClass('disabled d-none');

		$('#ntpn_collapse').collapse('show');
		$('html, body').animate({
	        scrollTop: $("#form-ntpn").offset().top - 100
	    }, 200);
	}

	swal_confirm('Konfirmasi','Edit ntpn ?',function (confirm) {
	    if (confirm) {

			var formData = new FormData();
			formData.append("id", id);
			formData.append("idLs", $("#idData").val());

			var url = baseurl + "ekspor/coal/ls/edit_ntpn";
			postAjax(url,formData,callback);
	    }
    });
}

function fillNTPN(data)
{

	$("#idNtpn").val(data.id);
	$("#n_noNtpn").val(data.noNtpn);
	$("#n_tglNtpn").val(data.tglNtpn);
	$("#n_nama").val(data.nama);
	$("#n_nib").val(data.nib);

	$("#n_npwp").val(data.npwp);
	$("#n_npwp").trigger('change');
	
	if(data.idNtpnNsw)
	{
		$(".input-n-npwp").attr('id','n_npwp_x');
		$(".input-n-npwp").attr('name','n_npwp_x');
		$(".select-n-npwp").attr('id','n_npwp');
		$(".select-n-npwp").attr('name','n_npwp');

		$(".select-n-npwp").prop('disabled',false);
		$(".input-n-npwp").prop('disabled',true);
		$(".input-n-npwp").removeClass('d-none').addClass('d-none');
		$(".select-n-npwp").removeClass('d-none');
		
		// $('#n_volume').prop('readonly', true);
		$("#n_npwp").prop('readonly',true);
	}
	else{
		$(".select-n-npwp").attr('id','n_npwp_x');
		$(".select-n-npwp").attr('name','n_npwp_x');
		$(".input-n-npwp").attr('id','n_npwp');
		$(".input-n-npwp").attr('name','n_npwp')

		$(".select-n-npwp").prop('disabled',true);
		$(".input-n-npwp").prop('disabled',false);
		$(".select-n-npwp").removeClass('d-none').addClass('d-none');
		$(".input-n-npwp").removeClass('d-none');
	}


	if(data.idNtpnNsw){
		$(".select-n-npwp").html(data.selectNPWP);
		$(".select-n-npwp").val(data.npwp);
	}
	else
		$(".input-n-npwp").val(data.npwp);

	// if(data.idNtpnNsw)
	// 	$(".select-n-npwp").prop('readonly',true);

	$("#n_nitku").val(data.nitku);
	$("#n_volume").val(data.volume).trigger('keyup');
	$("#n_royalti").val(data.royalti).trigger('keyup');

	if(data.idJnsIUP){
	  var newOption = new Option(data.jenisIUP, data.idJnsIUP, true, true);
		$('#n_idJnsIUP').append(newOption).trigger('change');
	}

	if(data.kdSatuan){
  var newOption = new Option(data.kdSatuan+' - '+data.uraiSatuan, data.kdSatuan, true, true);
	$('#n_kdSatuan').append(newOption).trigger('change');
	}

	if(data.idProp && data.idProp != 0){
		var newOption = new Option(data.namaProp, data.idProp, true, true);
		$('#n_idProp').append(newOption).trigger('change');
	}

	if(data.currency){
		var newOption = new Option(data.currency+' - '+data.uraiCurrency, data.currency, true, true);
		$('#n_currency').append(newOption).trigger('change');
	}
}

function getKalori(idLs)
{
	// new DataTable('#table-kalori', {
	// 	destroy: true,
	// 	searching: false,
	// 	ordering: false,
	// 	processing: true,
	// });

	const callback = function (resp) {
		$("#table-kalori tbody").html(resp.content);
	}

	var formData = new FormData();
	formData.append("idLs", idLs);

	var url = baseurl + "ekspor/coal/ls/get_kalori";
	postAjax(url,formData,callback);
}


function getNTPN(idLs)
{
	const callback = function (resp) {
		$("#table-ntpn tbody").html(resp.content);
	}

	var formData = new FormData();
	formData.append("idLs", idLs);

	var url = baseurl + "ekspor/coal/ls/get_ntpn";
	postAjax(url,formData,callback);
}

function addKomoditas()
{
	const callback = function (resp) {
		if(resp.code == '00'){
			$("#idPosTarif").val('');
			$(".btn-save-hs").removeClass('disabled d-none');
			$(".btn-update-hs").removeClass('disabled d-none').addClass('disabled d-none');
			$("#form-komoditas").trigger('reset');
			$("#k_postarif, #k_ntpn, #k_kdSatuanBarang, #k_kdNegaraAsal, #k_currencyHargaBarang").val('').trigger('change');
			initselectdua('#kal_seriBarang',baseurl+'select/seribarang',$('#idData').val(),0);
			
			getKomoditas(idLs);
		}
		showAlert(resp);
	}

	var idLs = $('#idData').val();
	if(!idLs){
		var error = {code:"99", msg:"Silahkan simpan Draft LS terlebih dahulu", text:"Tidak dapat tambah ntpn"};
		showAlert(error);
	}
	else{
		var param = $("#form-komoditas").serializeArray()
		var postdata = JSON.stringify(param);

		var formData = new FormData();

		formData.append("postdata", postdata);
		formData.append("idLs", idLs);

		var url = baseurl + "ekspor/coal/ls/add_komoditas";
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

	var url = baseurl + "ekspor/coal/ls/get_komoditas";
	postAjax(url,formData,callback);
}

function getDokumen(idLs)
{
	const callback = function (resp) {
		$("#tab-referensi tbody").html(resp.content);
	}

	var formData = new FormData();
	formData.append("idLs", idLs);

	var url = baseurl + "ekspor/coal/ls/get_list_dok";
	postAjax(url,formData,callback);
}

function del_dok_ref(idRef)
{
	const callback = function (resp) {
		showAlert(resp);
		getDokumen($('#idData').val())

		$(".lbl-noET").html(resp.data.noET);
		$(".lbl-tglET").html(resp.data.tglET);
		$(".lbl-tglAkhirET").html(resp.data.tglAkhirET);
	}

	swal_confirm('Konfirmasi hapus','Hapus dari pilihan referensi ?',function (confirm) {
	    if (confirm) {

			var formData = new FormData();
			formData.append("idRef", idRef);

			var url = baseurl + "ekspor/coal/ls/delete_dok_ref";
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

			var url = baseurl + "ekspor/coal/ls/edit_hs";
			postAjax(url,formData,callback);
	    }
    });
}

function fillKomoditas(data)
{
	if(data.postarif){
		var newOption = new Option(data.hscode, data.postarif, true, true);
		$('#k_postarif').html(newOption).trigger('change');
	}

	if(data.kdSatuanBarang){
		var newOption = new Option(data.kdSatuanBarang+' - '+data.uraiSatuanBarang, data.kdSatuanBarang, true, true);
		$('#k_kdSatuanBarang').html(newOption).trigger('change');
	}

	if(data.kdNegaraAsal){
		var newOption = new Option(data.negaraAsal, data.kdNegaraAsal, true, true);
		$('#k_kdNegaraAsal').html(newOption).trigger('change');
	}

	if(data.currencyHargaBarang){
		var newOption = new Option(data.currencyHargaBarang+' - '+data.uraiCurrency, data.currencyHargaBarang, true, true);
		$('#k_currencyHargaBarang').html(newOption).trigger('change');
	}

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

  	var url = baseurl + "ekspor/coal/ls/add_package";
	postAjax(url,formData,callback);

}

function getPackage(idLs)
{
	const callback = function (resp) {
		$("#table-package tbody").html(resp.content);
	}

	var formData = new FormData();
	formData.append("idLs", idLs);

	var url = baseurl + "ekspor/coal/ls/get_package";
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

		var url = baseurl + "ekspor/coal/ls/del_package";
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

				var url = baseurl + "ekspor/coal/ls/edit_package";
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
  formData.append('cnt_noSegel',$('#cnt_noSegel').val());

  var url = baseurl + "ekspor/coal/ls/add_container";
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

	var url = baseurl + "ekspor/coal/ls/get_container";
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

		var url = baseurl + "ekspor/coal/ls/del_container";
		postAjax(url,formData,callback);
	  }
	});
}

function edit_container(id)
{
	const callback = function (resp) {
		$("#idContainer").val(resp.id);
		$("#cnt_nomor").val(resp.nomor);
		$("#cnt_noSegel").val(resp.noSegel);
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

				var url = baseurl + "ekspor/coal/ls/edit_container";
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

				var url = baseurl + "ekspor/coal/ls/delete_hs";
				postAjax(url,formData,callback);
	    }
    });
}


function addKalori()
{
	const callback = function (resp) {
		showAlert(resp);

		if(resp.code == '00'){
			$(".btn-save-kalori").removeClass('disabled d-none');
			$(".btn-update-kalori").removeClass('disabled d-none').addClass('disabled d-none');
			$("#form-kalori").trigger('reset');
			$("#kal_seriBarang").val('').trigger('change');

			getKalori(idLs);
		}
	}

	var idLs = $('#idData').val();
	// var idLs = 1;
	if(!idLs){
		var error = {code:"99", msg:"Silahkan simpan Draft LS terlebih dahulu", text:"Tidak dapat menambahkan kalori"};
		showAlert(error);
	}
	else{
		var param = $("#form-kalori").serializeArray()
		var postdata = JSON.stringify(param);

		var formData = new FormData();
		formData.append("postdata", postdata);
		formData.append("idLs", idLs);

		var url = baseurl + "ekspor/coal/ls/add_kalori";
		postAjax(url,formData,callback);
	}
}

function edit_kalori(id)
{
	const callback = function (resp) { 
    	var option = new Option(resp.hs.seri+' - '+resp.hs.fhs, resp.hs.id, true, true);

		$("#idKalori").val(id);
		$('#kal_seriBarang').html(option).trigger('change');
		$("#calArb").val(resp.calArb).trigger('keyup');;
		$("#calAdb").val(resp.calAdb).trigger('keyup');;
		$("#tmArb").val(resp.tmArb).trigger('keyup');;
		$("#tAsh").val(resp.tAsh).trigger('keyup');;
		$("#tSulfur").val(resp.tSulfur).trigger('keyup');;
		$("#klasifikasiBatubara").val(resp.klasifikasiBatubara);
		$("#kal_keterangan").val(resp.keterangan);
 
		$(".btn-save-kalori").removeClass('disabled d-none').addClass('disabled d-none');
		$(".btn-update-kalori").removeClass('disabled d-none');

		$('#klasifikasi_collapse').collapse('show');
		$('html, body').animate({
	        scrollTop: $("#form-kalori").offset().top - 100
	    }, 200);
	}

	$(".btn-save-kalori").removeClass('disabled d-none');
	$(".btn-update-kalori").removeClass('disabled d-none').addClass('disabled d-none');
	$("#form-kalori").trigger('reset');
	$("#kal_seriBarang").val('').trigger('change');

	swal_confirm('Konfirmasi','Edit kalori ?',function (confirm) {
	    if (confirm) {

			var formData = new FormData();
			formData.append("id", id);
			formData.append("idLs", $("#idData").val());

			var url = baseurl + "ekspor/coal/ls/edit_kalori";
			postAjax(url,formData,callback);
	    }
    });
}

function del_kalori(id)
{
	const callback = function (resp) {
		showAlert(resp);
		getKalori($("#idData").val());
	}

	swal_confirm('Konfirmasi Hapus','Hapus kalori ?',function (confirm) {
	    if (confirm) {
				var formData = new FormData();
				formData.append("id", id);
				formData.append("idLs", $("#idData").val());

				var url = baseurl + "ekspor/coal/ls/delete_kalori";
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
 
function create_perubahan()
{
	swal_confirm('Konfrimasi Perubahan LS','Buat perubahan terhadap LS ini ? Data perubahan akan otomatis dibuat pada menu LS Konsep',function (confirm) {
	    if (confirm) {
	    	const callback = function (resp) {
					if(resp.code == '00'){
							$(".btn-cabut").addClass('disabled d-none');
							$(".btn-perubahan").addClass('disabled d-none');
							var urlRedirect = baseurl+'ekspor/coal/ls/konsep';
							alertRedirect(resp,5000,urlRedirect);
					}
					else{
						showAlert(resp);
					}
				}

				var formData = new FormData();
				formData.append('idLs',$("#idData").val());
				var url = baseurl + "ekspor/coal/ls/perubahan";
				postAjax(url,formData,callback);
	    }
  });
}

// 2023-11-17 
function resetFormNtpn()
{
// $('#reset-form-ntpn').click(function () {
	// console.log("p oo");
	$('#form-ntpn')[0].reset(); 
	$("#n_npwp").prop('readonly',false);
	$("#n_npwp").attr('readonly', false);
	$('#idNtpn').val('');
	$("#n_idJnsIUP").empty().trigger('change'); 
	$("#n_npwp").empty().trigger('change');
	$("#n_kdSatuan").empty().trigger('change'); 
	$("#n_idProp").empty().trigger('change'); 
	$("#n_currency").empty().trigger('change'); 

	$(".select-n-npwp").prop('disabled',true);
	$(".select-n-npwp").removeClass('d-none').addClass('d-none');

	$(".input-n-npwp").prop('disabled',false);
	$(".input-n-npwp").removeClass('d-none');

	$(".select-n-npwp").attr('id','n_npwp_x');
	$(".select-n-npwp").attr('name','n_npwp_x');
	$(".input-n-npwp").attr('id','n_npwp');
	$(".input-n-npwp").attr('name','n_npwp')

	$('#n_volume').prop('readonly', false);
	
	$(".btn-save-ntpn").removeClass('disabled d-none');
	$(".btn-update-ntpn").removeClass('disabled d-none').addClass('disabled d-none');
// });  
}

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
 
function resetFormKalori()
{
// $('#reset-form-kalori').click(function () { 
	$('#form-kalori')[0].reset(); 

	$('#idKalori').val('');
	$("#kal_seriBarang").empty().trigger('change'); 
	
	$(".btn-save-kalori").removeClass('disabled d-none');
	$(".btn-update-kalori").removeClass('disabled d-none').addClass('disabled d-none');
// }); 
}

//2024-01-30 
function view(id)
{
	$.redirect(baseurl+'ekspor/coal/ls/view', {id: id, csrf_appls: csrfName}, "POST", "_blank");
}

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

function print(id)
{
	var idLs = $('#idData').val();

	if(idLs != ''){
		$.redirect(baseurl+'print/coal/lse', {id: idLs, csrf_appls: csrfName}, "POST", "_blank");
	}
}