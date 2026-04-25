$(document).ready(function () {
	get_list_npwp();

	$('#modalAddNpwp').on('hidden.bs.modal', function () {
        $('#form-npwp')[0].reset();
		$("#btn-simpan-npwp").html('<i class="fa fa-save me-2" aria-hidden="true"> Simpan');
		$("idNpwp").val('');
    });
})
   
function get_list_npwp() {
	var searchParam = JSON.stringify($('#frm-tracking-npwp').serializeArray());
	
	if ($.fn.DataTable.isDataTable('#table-npwp')) {
		$('#table-npwp').DataTable().destroy();
	}

	new DataTable('#table-npwp', {
		ajax: {
			url: baseurl + 'management/npwp/list',
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
			{ "width": "5%", "targets": 0 }
		],
		"columns": [
			{ className: "text-center" },
			{ className: "align-top" },
			{ className: "align-top" },
			{ className: "text-center" },
			{ className: "text-center" } 
		],
		searching: true,
		ordering: false,
		processing: true,
		serverSide: true
	});
}

$('#btn-reset-npwp').click(function () {
	$('#frm-tracking-npwp')[0].reset();
	get_list_npwp();
});

$('#btn-tracking-npwp').click(function () {
	get_list_npwp();
}); 

function open_modal(id) {
	$('#form-npwp')[0].reset();
	$("#btn-simpan-npwp").html('<i class="fa fa-save me-2" aria-hidden="true"> Simpan');
	var modalPilihDok = new bootstrap.Modal('#modalAddNpwp')
	modalPilihDok.show();

	const callback = function (resp) {
		$("#modalTitle").html('Update NPWP');
		$("#btn-simpan-npwp").html('<i class="fa fa-save me-2" aria-hidden="true"> Update');

		$("#idNpwp").val(resp.id);
		$("#i_npwp15").val(resp.npwp15);
		$("#i_npwp16").val(resp.npwp16);
		$("#i_nama").val(resp.nama);
	}

	if (id !== undefined) {
		var formData = new FormData();
		formData.append('idNpwp', id);
		postAjax(baseurl + 'management/npwp/edit', formData, callback);
	}
}

$('#btn-close-modal').click(function () {
	$('#form-npwp')[0].reset();
	$("#btn-simpan-npwp").html('<i class="fa fa-save me-2" aria-hidden="true"> Simpan');
});

$('#btn-simpan-npwp').unbind('click').click(function (event) {
	event.preventDefault();

	const callback = function (resp) {
		showAlert(resp);
 
		if (resp.code == '00') {
			// location.reload(); 
			$('#modalAddNpwp').modal('hide');
			$('#form-npwp')[0].reset();
			get_list_npwp();
		}
	}

	var idNpwp = $("#idNpwp").val();
	var param = $("#form-npwp").serializeArray()
	var postdata = JSON.stringify(param);
	var formData = new FormData();
	formData.append("postdata", postdata);
	formData.append("idNpwp", idNpwp);

	swal_confirm('Konfirmasi', 'Apakah data npwp sudah sesuai?', function (confirm) {
		if (confirm) {
			var url = baseurl + "management/npwp/save";
			postAjax(url, formData, callback);
		}
	});
});

function del_npwp(id) {
	const callback = function (resp) {
		showAlert(resp);
		if (resp.code == '00') {
			// $("#idData").val(resp.data.id);
			// $.redirect(baseurl + 'management/cabang/', {}, "POST", "_self");
			get_list_npwp();
		}
	}

	swal_confirm('Konfirmasi', 'Hapus data npwp ini  ?', function (confirm) {
		if (confirm) {

			var formData = new FormData();
			formData.append("id", id);

			var url = baseurl + 'management/npwp/delete';
			postAjax(url, formData, callback);
		}
	});
}