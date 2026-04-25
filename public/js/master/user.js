$(document).ready(function () {
	get_list_user(); 
	
	initselectdua('.select-role', baseurl + 'select/role', 'internal', 0);
	initselectdua('.select-type', baseurl + 'select/type', '', 0);
	initselectdua('.select-cabang', baseurl + 'select/cabang', '', 0);
	
	initselectdua('.select-propinsi', baseurl + 'select/propinsi', '', 0); 
	initselectdua('.select-kota', baseurl + 'select/kota');
	initselectdua('.select-jenis-iup', baseurl + 'select/jenisiup', '', 0);  
	
	var idUser = $('#idUser').val(); 

	if (idUser) { 
		$("#btn-simpan-user").html('<i class="fa fa-save me-2" aria-hidden="true"> Update'); 
	} 
})
   
function get_list_user() {  
	var searchParam = JSON.stringify($('#frm-tracking-user').serializeArray());
	
	if ($.fn.DataTable.isDataTable('#table-user')) {
		$('#table-user').DataTable().destroy();
	}

	new DataTable('#table-user', {
		ajax: {
			url: baseurl + 'management/user/list',
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
			{ "width": "10%", "targets": 4 }, 
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

$('#btn-reset-pegawai').click(function () { 
	$('#form-user')[0].reset(); 
});

$('#btn-reset-user').click(function () { 
	$('#frm-tracking-user')[0].reset();
	get_list_user(); 
});

$('#btn-tracking-user').click(function () {  
	get_list_user(); 
});
 
$('#btn-back-user').unbind('click').click(function (event) {
	event.preventDefault();
	$.redirect(baseurl + 'management/user/', {}, "POST", "_self"); 
});

$('#btn-simpan-user').unbind('click').click(function (event) {
	event.preventDefault();

	const callback = function (resp) {
		showAlert(resp);

		$("#btn-simpan-user").removeAttr("disabled");  
 
		if (resp.code == '00') { 
			$("#btn-simpan-user").hide();
			$("#btn-reset-pegawai").hide();

			var urlRedirect = baseurl+'management/user';
			alertRedirect(resp, 14000, urlRedirect); 
		}
	}
 
	var param 		= $("#form-user").serializeArray()
	var postdata 	= JSON.stringify(param);
	var formData 	= new FormData();
	formData.append("postdata", postdata); 
	formData.append("idUser", $("#idUser").val());
	formData.append("idProfile", $("#idProfile").val());

	swal_confirm('Konfirmasi', 'Apakah data user sudah sesuai?', function (confirm) {
		if (confirm) { 
			$("#btn-simpan-user").attr("disabled", true);
 
			if($("#type").val() == '1'){  
				var jenisIUP 	= $("#i_idJenisIup").select2('data'); 
				formData.append("jenisIUP", jenisIUP[0].text); 
 
				var url = baseurl + "management/user/save-pu";
			} else { 
				var url = baseurl + "management/user/save";
			}

			postAjax(url, formData, callback);
		}
	});
});

function edit(id) {
	$.redirect(baseurl + 'management/user/edit', { id: id, csrf_appls: csrfName }, "POST", "_self"); 
}

function detail(id) {
	$.redirect(baseurl + 'management/user/detail', { id: id, csrf_appls: csrfName }, "POST", "_self");
}

function del_user(id) {
	const callback = function (resp) {
		showAlert(resp);
		if (resp.code == '00') { 
			get_list_user()
		}
	}

	swal_confirm('Konfirmasi', 'Hapus data user ini  ?', function (confirm) {
		if (confirm) {

			var formData = new FormData();
			formData.append("id", id);

			var url = baseurl + 'management/user/delete';
			postAjax(url, formData, callback);
		}
	});
}

// 06-11-2023
$('#btn-reset-user').click(function () {   
	$('#frm-tracking-user')[0].reset();
	$("#s_role").empty().trigger('change');
	$("#s_type").empty().trigger('change');
});

// 2024-02-26 
function change_status(status) {
	let teks = "";
	if(status == 1){
		teks = "Apakah anda inigin menonaktifkan  user ini ?";
	} else if(status == 2){
		teks = "Apakah anda inigin mengaktifkan user ini ?";
	} else if(status == 3){
		teks = "Apakah anda inigin mengaktifkan user ini dan mengirimkan email pemberitahuanya?";
	}
	
	const callback = function (resp) {
		showAlert(resp);
		$("#btn-user-status").removeAttr("disabled"); 

		if (resp.code == '00') {
			if(status == 1){
				$("#btn-user-status").removeClass('btn-success').addClass('btn-danger');
				$("#btn-user-status").text('non-Acive');
			} else if(status == 2){
				$("#btn-user-status").removeClass('btn-danger').addClass('btn-success');
				$("#btn-user-status").text('Acive');
			} else if(status == 3){
				$("#btn-user-status").removeClass('btn-warning').addClass('btn-success');
				$("#btn-user-status").text('Acive');
			}
		}
	}

	swal_confirm('Konfirmasi', teks, function (confirm) {
		if (confirm) { 
			$("#btn-user-status").attr("disabled", true);

			var formData = new FormData();
			formData.append("idUser", $("#idUser").val());
			formData.append("status", status);

			var url = baseurl + 'management/user/change_status';
			postAjax(url, formData, callback);
		}
	}); 
}