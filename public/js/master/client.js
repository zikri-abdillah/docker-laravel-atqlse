$(document).ready(function () {
	get_list_client();

	// initselectdua(element, url, filter='',minlength=3, parent='')  
	initselectdua('.select-propinsi', baseurl + 'select/propinsi', '', 0); 
	initselectdua('.select-kota', baseurl + 'select/kota');
	initselectdua('.select-jenis-iup', baseurl + 'select/jenisiup', '', 0); 
	initselectdua('#t_negaraPenerbit', baseurl + 'select/negara', '', 0);
	initselectdua('#t_idJenisDok', baseurl + 'select/jenisdok', '', 0);

	var idPerusahaan = $('#idData').val();
	
	$('#buttonReferensi').attr('class', 'disabled');

	if (idPerusahaan) {
		$('#buttonReferensi').attr('class', 'enabled');
		$('#i_npwp').attr("readonly", true); 
		$("#btn-simpan-profile").html('<i class="mdi mdi-content-save-all-outline" aria-hidden="true"></i> Update');

		getDokumen(idPerusahaan);
	} else {
		var pathname = window.location.pathname;
		var url = window.location.href;
		var origin = window.location.origin; 
	}
	
	$('#viewFile').hide();
});

function get_list_client() {
	var searchParam = JSON.stringify($('#frm-tracking-client').serializeArray());

	if ($.fn.DataTable.isDataTable('#table-client')) {
		$('#table-client').DataTable().destroy();
	}

	new DataTable('#table-client', {
		ajax: {
			url: baseurl + 'management/client/list',
			type: 'POST',
			"data": { searchParam: searchParam, csrf_appls: csrfName }
		},
		"columnDefs": [
			{
				"targets": [0], //first column / numbering column
				"orderable": true,
				//set not orderable
			},
			{ "targets": [1], "className": 'no-wrap' },
			{ "width": "20%", "targets": 4 }
		],
		"columns": [
			{ className: "text-center" },
			{ className: "align-top" },
			{ className: "align-top" },
			{ className: "align-top" },
			{ className: "align-top" },
			{ className: "text-center" }
		],
		searching: false,
		ordering: false,
		processing: true,
		serverSide: true
	});
}

$('#buttonReferensi').click(function () { 
	var idPerusahaan = $('#idData').val(); 
	if (!idPerusahaan) { 
		var error = { code: "99", msg: "Sebelum meng-upload dokumen referensi, silahkan simpan data client terlebih dahulu", text: "Perhatian" };
		showAlert(error);
	}
	
});

$('#btn-reset-profile').click(function () {
	$('#form-main')[0].reset();
});

$('#btn-reset-client').click(function () {
	$('#frm-tracking-client')[0].reset();
	get_list_client();
});

$('#btn-tracking-client').click(function () {
	get_list_client();
});
  
$(".accor-style2").click(function (e) {
	$(this).find('i').toggleClass('fe-chevrons-down fe-chevrons-up');
});

$('#btn-simpan-profile').unbind('click').click(function (event) {
	event.preventDefault();

	const callback = function (resp) {
		showAlert(resp);
		
		if (resp.code == '00') {
			$('#buttonReferensi').attr('class', 'enabled'); 
			$("#buttonProfile").removeClass("active");
			$("#buttonReferensi").addClass("active");

			$("#idData").val(resp.data.id); 
		}
	}

	var param = $("#form-main").serializeArray()
	var postdata = JSON.stringify(param);
	var jenisIUP = $("#i_idJenisIup").select2('data'); 
 
	var formData = new FormData();
	formData.append("postdata", postdata);
	formData.append("jenisIUP", jenisIUP[0].text);
	formData.append("idData", $("#idData").val());
 
	swal_confirm('Konfirmasi', 'Apakah data perusahaan sudah sesuai?', function (confirm) {
		if (confirm) {
			var url = baseurl + "management/client/save";
			postAjax(url, formData, callback);
		}
	});
});

function back_client() { 
	$.redirect(baseurl + 'management/client/', {}, "POST", "_self");
}

function edit(id) {
	$.redirect(baseurl + 'management/client/edit', { id: id, csrf_appls: csrfName }, "POST", "_blank"); 
}

function detail(id) {
	$.redirect(baseurl + 'management/client/detail', { id: id, csrf_appls: csrfName }, "POST", "_blank"); 
}

function del_perusahaan(id) {
	const callback = function (resp) {
		showAlert(resp);
		if (resp.code == '00') { 
			get_list_client();
		}
	}

	swal_confirm('Konfirmasi', 'Hapus data perusahaan  ?', function (confirm) {
		if (confirm) {

			var formData = new FormData();
			formData.append("id", id);

			var url = baseurl + 'management/client/delete';
			postAjax(url, formData, callback);
		}
	});
}

$('#btn-save-dokumen').unbind('click').click(function (event) {
	var idDok 		 = $('#idDok').val();
	var idClient 	 = $('#idData').val();
	var npwp 		 = $('#i_npwp').val();
 
	console.log($('#t_idJenisDok').val());
	  
	if (!idClient) {
		var error = { code: "99", msg: "Silahkan simpan data client terlebih dahulu", text: "Gagal" };
		showAlert(error);
	} else {
		if (!idDok) {
			var url 		= baseurl + "dokpersh/uploadDok";
			var fileUpload 	= $('#t_fileDok')[0].files[0];
		} else {
			var url 		= baseurl + "dokpersh/updeteDok";
			var fileUpload 	= $('#t_fileDokUpdate')[0].files[0];
		}
		  
		var param 	 = $("#form-dokumen").serializeArray();
		var postdata = JSON.stringify(param);
		var formData = new FormData();

		formData.append('postdata', postdata);
		formData.append('csrf_appls', csrfName);
		formData.append('idClient', idClient);
		formData.append('npwp', npwp);
		formData.append('fileDok', fileUpload);
		 
		$.ajax({
			method		: "POST",
			url			: url,
			data		: formData,
			cache		: false,
			contentType	: false,
			processData	: false,
			success		: function (resp) {
				showAlert(resp);

				if(resp.code == '00'){
					$('#form-dokumen')[0].reset();
					$('#viewFile').hide();
					$('#uploadFile').show();
					 
					$('#idDok').val(''); 	
					$("#t_idJenisDok").empty().trigger('change');
					$("#t_negaraPenerbit").empty().trigger('change');
					
					getDokumen(idClient);  
				}
			},
			error		: function (xhr, status, error) {
				console.log(xhr.responseText);
			}
		});
	}
});

function getDokumen(idPerusahaan) {
	const callback = function (resp) {  
		new DataTable('#table-dok-ref', {
			destroy: true, 
			searching: false,
			ordering: false,
			processing: true,
		});
		
		$("#table-dok-ref tbody").html(resp.content);
	}

	var formData = new FormData();
	formData.append("idPerusahaan", idPerusahaan);

	var url = baseurl + "management/client/listDok";
	postAjax(url, formData, callback);
}

function view_dok_persh(idRef) {
	$.redirect(baseurl + 'dokpersh/view', { idRef: idRef, csrf_appls: csrfName }, "POST", "_blank");
}

function del_dok_persh(idDok) {
	var idPerusahaan = $('#idData').val();
	const callback = function (resp) {
		showAlert(resp, 600);
		getDokumen(idPerusahaan);
	}

	swal_confirm('Konfirmasi hapus', 'Hapus dari pilihan referensi ?', function (confirm) {
		if (confirm) {

			var formData = new FormData();
			formData.append("idDok", idDok);
			formData.append("withdata", true);
			formData.append('csrf_appls', csrfName);
			
			// var url = baseurl + "management/client/deleteDok";
			var url = baseurl + 'dokpersh/delete';
			postAjax(url, formData, callback);
		}
	});
}
 
function edit_dok_persh(id)
{
	const callback = function (resp) { 
		// let file 			= baseurl + resp.pathFile;
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
 
		$("#tab_referensi").trigger('click'); 
		$("#idDok").val(resp.id); 
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
 
$('#reset-form-upload').click(function () {
	$('#form-dokumen')[0].reset();
	$('#viewFile').hide();
	$('#uploadFile').show();
	 
	$('#idDok').val(''); 	
	$("#t_idJenisDok").empty().trigger('change');
	$("#t_negaraPenerbit").empty().trigger('change');
});
 
// 06-11-2023
$('#btn-reset-client').click(function () {   
	$('#frm-tracking-client')[0].reset();
	$("#s_idJenisIup").empty().trigger('change');
});
 
// 2024-03-11 
function change_status(status) {
	let teks = "";
	if(status == 1){
		teks = "Apakah anda inigin menonaktifkan  perusahaan ini ?";
	} else if(status == 2){
		teks = "Apakah anda inigin mengaktifkan perusahaan ini ?";
	} else if(status == 3){
		teks = "Apakah anda inigin mengaktifkan perusahaan ini dan mengirimkan email pemberitahuanya?";
	}
	
	const callback = function (resp) {
		showAlert(resp);
		$("#btn-perusahaan-status").removeAttr("disabled"); 

		if (resp.code == '00') {
			if(status == 1){
				$("#btn-perusahaan-status").removeClass('btn-success').addClass('btn-danger');
				$("#btn-perusahaan-status").text('non-Acive');
			} else if(status == 2){
				$("#btn-perusahaan-status").removeClass('btn-danger').addClass('btn-success');
				$("#btn-perusahaan-status").text('Acive');
			} else if(status == 3){
				$("#btn-perusahaan-status").removeClass('btn-warning').addClass('btn-success');
				$("#btn-perusahaan-status").text('Acive');
			}
		}
	}

	swal_confirm('Konfirmasi', teks, function (confirm) {
		if (confirm) { 
			$("#btn-perusahaan-status").attr("disabled", true);

			var formData = new FormData();
			formData.append("idPerusahaan", $("#idPerusahaan").val());
			formData.append("status", status);

			var url = baseurl + 'management/client/change-status';
			postAjax(url, formData, callback);
		}
	}); 
}