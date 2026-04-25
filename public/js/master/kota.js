$(document).ready(function () {
	initselectdua('.select-negara', baseurl + 'select/negara', '', 0);
	initselectdua('#i_negara',baseurl+'select/negara', '', 0, 'modalAddKota');

	get_list_kota(); 
})
   
function get_list_kota() {  
	var searchParam = JSON.stringify($('#frm-tracking-kota').serializeArray());
	
	if ($.fn.DataTable.isDataTable('#table-kota')) {
		$('#table-kota').DataTable().destroy();
	}
 
	new DataTable('#table-kota', {
		ajax: {
			url: baseurl + 'management/kota/list',
			type: 'POST', 
			"data": { searchParam: searchParam, csrf_appls: csrfName }
		},
		"columnDefs": [
			{
				"targets": [0],
				"orderable": true,
				//set not orderable
			},
			{ "targets": [1], "className": 'no-wrap' },
			{ "width": "5%", "targets": 0 },
			{ "width": "10%", "targets": 1 },
			{ "width": "15%", "targets": 2 },
			{ "width": "15%", "targets": 3 },
			{ "width": "15%", "targets": 4 },
			{ "width": "5%", "targets": 5 },
			{ "width": "10%", "targets": 6 }
		],
		"columns": [
			{ className: "text-center" },
			{ className: "text-center" },
			{ className: "text-center" },
			{ className: "text-center" },
			{ className: "text-center" }, 
			{ className: "text-center" }, 
			{ className: "text-center" } 
		],
		searching: false,
		ordering: false,
		processing: true,
		serverSide: true
	});
}

$('#btn-reset-kota').click(function () { 
	$('#frm-tracking-kota')[0].reset();
	get_list_kota(); 
});

$('#btn-tracking-kota').click(function () {  
	get_list_kota(); 
}); 

function open_modal(id) {
	var modalPilihDok = new bootstrap.Modal('#modalAddKota')
	modalPilihDok.show();

	const callback = function (resp) {
		$("#modalTitle").html('Update Kota');
		$("#btn-simpan-kota").html('<i class="fa fa-save me-2" aria-hidden="true"> Update'); 
		$("#idKota").val(resp.idKota);
		$("#i_unlocode").val(resp.kodeUNLOCODE);
		$("#i_inatrade").val(resp.kodeInatrade);
		$("#i_lengkap").val(resp.namaLengkap);
		$("#i_nama").val(resp.namaKota); 
  
		if ($('#i_negara').find("option[value='" + resp.kodeNegara + "']").length) {
			$('#i_negara').val(resp.kodeNegara).trigger('change');
		} else {
			var newOption = new Option(resp.nama, resp.kodeNegara, true, true);
			$('#i_negara').append(newOption).trigger('change');
		}
 
		if ($('#i_status').find("option[value='" + resp.isActive + "']").length) {
			$('#i_status').val(resp.isActive).trigger('change');
		} else {
			var newOption = new Option(resp.namaNegara, resp.isActive, true, true);
			$('#i_status').append(newOption).trigger('change');
		}
	}

	if (id !== undefined) {
		var formData = new FormData();
		formData.append('idKota', id);
		postAjax(baseurl + 'management/kota/edit', formData, callback);
	}
}

$('#btn-close-modal').click(function () { 
	$('#form-kota')[0].reset(); 
	$("#btn-simpan-kota").html('<i class="fa fa-save me-2" aria-hidden="true"> Simpan');
});

$('#btn-simpan-kota').unbind('click').click(function (event) {
	event.preventDefault();

	const callback = function (resp) {
		showAlert(resp);
		if (resp.code == '00') {
			$('#modalAddKota').modal('hide');
			$('#form-kota')[0].reset();
			get_list_kota(); 
		}
	}

	var negara = $("#i_negara").select2('data'); 
	var idKota = $("#idKota").val();

	var param = $("#form-kota").serializeArray()
	var postdata = JSON.stringify(param); 
	var formData = new FormData();
	formData.append("postdata", postdata);
	formData.append("idKota", idKota);
	formData.append("namaNegara", negara[0].text);

	swal_confirm('Konfirmasi', 'Apakah data kota sudah sesuai?', function (confirm) {
		if (confirm) {
			var url = baseurl + "management/kota/save";
			postAjax(url, formData, callback);
		}
	});
});

function del_kota(id) {
	const callback = function (resp) {
		showAlert(resp);
		if (resp.code == '00') {
			get_list_kota(); 
		}
	}

	swal_confirm('Konfirmasi', 'Hapus data kota ini  ?', function (confirm) {
		if (confirm) {

			var formData = new FormData();
			formData.append("id", id);

			var url = baseurl + 'management/kota/delete';
			postAjax(url, formData, callback);
		}
	});
}

// 06-11-2023
$('#btn-reset-kota').click(function () {   
	$('#frm-tracking-kota')[0].reset();
	$("#s_negara").empty().trigger('change');
});