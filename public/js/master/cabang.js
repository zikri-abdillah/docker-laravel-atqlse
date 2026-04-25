$(document).ready(function () {
	get_list_cabang(); 
})
   
function get_list_cabang() {  
	var searchParam = JSON.stringify($('#frm-tracking-cabang').serializeArray());
	
	if ($.fn.DataTable.isDataTable('#table-cabang')) {
		$('#table-cabang').DataTable().destroy();
	}

	new DataTable('#table-cabang', {
		ajax: {
			url: baseurl + 'management/cabang/list',
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
			{ className: "text-center" },
			{ className: "text-center" } 
		],
		searching: false,
		ordering: false,
		processing: true,
		serverSide: true
	});
}

$('#btn-reset-cabang').click(function () { 
	$('#frm-tracking-cabang')[0].reset();
	get_list_cabang(); 
});

$('#btn-tracking-cabang').click(function () {  
	get_list_cabang(); 
}); 

function open_modal(id) {
	var modalPilihDok = new bootstrap.Modal('#modalAddCabang')
	modalPilihDok.show();

	const callback = function (resp) {
		$("#modalTitle").html('Update Cabang');
		$("#btn-simpan-cabang").html('<i class="fa fa-save me-2" aria-hidden="true"> Update');

		$("#idCabang").val(resp.id);
		$("#i_cabang").val(resp.cabang);
		$("#i_kodeCabang").val(resp.kodeCabang);
	}

	if (id !== undefined) {
		var formData = new FormData();
		formData.append('idCabang', id);
		postAjax(baseurl + 'management/cabang/edit', formData, callback);
	}
}

$('#btn-close-modal').click(function () { 
	$('#form-cabang')[0].reset(); 
	$("#btn-simpan-cabang").html('<i class="fa fa-save me-2" aria-hidden="true"> Simpan');
});

$('#btn-simpan-cabang').unbind('click').click(function (event) {
	event.preventDefault();

	const callback = function (resp) {
		showAlert(resp);
 
		if (resp.code == '00') {
			// location.reload(); 
			$('#modalAddCabang').modal('hide');
			$('#form-cabang')[0].reset();
			get_list_cabang(); 
		}
	}

	var idCabang = $("#idCabang").val();
	var param = $("#form-cabang").serializeArray()
	var postdata = JSON.stringify(param);
	var formData = new FormData();
	formData.append("postdata", postdata);
	formData.append("idCabang", idCabang);

	swal_confirm('Konfirmasi', 'Apakah data cabang sudah sesuai?', function (confirm) {
		if (confirm) {
			var url = baseurl + "management/cabang/save";
			postAjax(url, formData, callback);
		}
	});
});

function del_cabang(id) {
	const callback = function (resp) {
		showAlert(resp);
		if (resp.code == '00') {
			// $("#idData").val(resp.data.id);
			// $.redirect(baseurl + 'management/cabang/', {}, "POST", "_self");
			get_list_cabang(); 
		}
	}

	swal_confirm('Konfirmasi', 'Hapus data cabang ini  ?', function (confirm) {
		if (confirm) {

			var formData = new FormData();
			formData.append("id", id);

			var url = baseurl + 'management/cabang/delete';
			postAjax(url, formData, callback);
		}
	});
}