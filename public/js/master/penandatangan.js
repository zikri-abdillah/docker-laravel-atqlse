$(document).ready(function () {
	initselectdua('.select-cabang', baseurl + 'select/cabang', '', 0);
	initselectdua('#i_cabang',baseurl+'select/cabang', '', 0, 'modalAddPenandatangan');

	get_list_penandatangan(); 
})
   
function get_list_penandatangan() {  
	var searchParam = JSON.stringify($('#frm-tracking-penandatangan').serializeArray());
	
	if ($.fn.DataTable.isDataTable('#table-penandatangan')) {
		$('#table-penandatangan').DataTable().destroy();
	}

	new DataTable('#table-penandatangan', {
		ajax: {
			url: baseurl + 'management/penandatangan/list',
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
			{ "width": "10%", "targets": 4 }
		],
		"columns": [
			{ className: "text-center" },
			{ className: "align-top" },
			{ className: "align-top" },
			{ className: "align-top" },
			{ className: "align-top" },
			{ className: "text-center" },
			{ className: "text-center" } 
		],
		searching: false,
		ordering: false,
		processing: true,
		serverSide: true
	});
}

$('#btn-reset-penandatangan').click(function () { 
	$('#frm-tracking-penandatangan')[0].reset();
	get_list_penandatangan(); 
});

$('#btn-tracking-penandatangan').click(function () {  
	get_list_penandatangan(); 
}); 

function open_modal(id) {
	var modalPilihDok = new bootstrap.Modal('#modalAddPenandatangan')
	modalPilihDok.show();

	const callback = function (resp) {
		$("#modalTitle").html('Update Penandatangan');
		$("#btn-simpan-penandatangan").html('<i class="fa fa-save me-2" aria-hidden="true"> Update');

		$("#idPenandatangan").val(resp.id);
		$("#i_nama").val(resp.nama);
		$("#i_identitas").val(resp.noIdentitas);
		$("#i_jabatan").val(resp.jabatan);
  
		if ($('#i_cabang').find("option[value='" + resp.idCabang + "']").length) {
			$('#i_cabang').val(resp.idCabang).trigger('change');
		} else {
			var newOption = new Option(resp.namaCabang, resp.idCabang, true, true);
			$('#i_cabang').append(newOption).trigger('change');
		}
	}

	if (id !== undefined) {
		var formData = new FormData();
		formData.append('idPenandatangan', id);
		postAjax(baseurl + 'management/penandatangan/edit', formData, callback);
	}
}

$('#btn-close-modal').click(function () { 
	$('#form-penandatangan')[0].reset(); 
	$("#btn-simpan-penandatangan").html('<i class="fa fa-save me-2" aria-hidden="true"> Simpan');
});

$('#btn-simpan-penandatangan').unbind('click').click(function (event) {
	event.preventDefault();

	const callback = function (resp) {
		showAlert(resp);
		if (resp.code == '00') {
			$('#modalAddPenandatangan').modal('hide');
			$('#form-penandatangan')[0].reset();
			get_list_penandatangan(); 
		}
	}

	var cabang = $("#i_cabang").select2('data'); 
	var idPenandatangan = $("#idPenandatangan").val();

	var param = $("#form-penandatangan").serializeArray()
	var postdata = JSON.stringify(param); 
	var formData = new FormData();
	formData.append("postdata", postdata);
	formData.append("idPenandatangan", idPenandatangan);
	formData.append("namaCabang", cabang[0].text);

	swal_confirm('Konfirmasi', 'Apakah data penandatangan sudah sesuai?', function (confirm) {
		if (confirm) {
			var url = baseurl + "management/penandatangan/save";
			postAjax(url, formData, callback);
		}
	});
});

function del_penandatangan(id) {
	const callback = function (resp) {
		showAlert(resp);
		if (resp.code == '00') {
			get_list_penandatangan(); 
		}
	}

	swal_confirm('Konfirmasi', 'Hapus data penandatangan ini  ?', function (confirm) {
		if (confirm) {

			var formData = new FormData();
			formData.append("id", id);

			var url = baseurl + 'management/penandatangan/delete';
			postAjax(url, formData, callback);
		}
	});
}

// 06-11-2023
$('#btn-reset-penandatangan').click(function () {   
	$('#frm-tracking-penandatangan')[0].reset();
	$("#s_cabang").empty().trigger('change');
});