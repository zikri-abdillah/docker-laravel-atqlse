$(document).ready(function() {  
	initselectdua('.select-jenis-ls',baseurl+'select/jenisls','',0);
	initselectdua('.select-cabang',baseurl+'select/cabang','',0);

	get_list_coal();
});

function get_list_coal() {  
	var searchParam = JSON.stringify($('#frm-tracking-coal').serializeArray());
	
	if ($.fn.DataTable.isDataTable('#table-data')) {
		$('#table-data').DataTable().destroy();
	}

	if($('#dataFilter').val() == 'terbit'){
		var cols = [
		    { className: "text-center text-nowrap align-top" },
		    { className: "text-nowrap align-top" },
		    { className: "text-nowrap align-top" },
		    { className: "align-top"},
		    { className: "align-top" },
		    { className: "text-center align-top" },
		    { className: "text-center align-top" }
		];
	}
	else
	{
		var cols = [
		    { className: "text-center text-nowrap align-top" },
		    { className: "text-nowrap align-top" },
		    { className: "align-top"},
		    { className: "align-top" },
		    { className: "text-center align-top" },
		    { className: "text-center align-top" }
		];
	}

	var table = new DataTable('#table-data', {
		ajax: {
	        url: baseurl+'client/lse/list/'+$('#dataFilter').val(),
			type: 'POST',
			"data": { searchParam: searchParam, csrf_appls: csrfName }
		},
		"columnDefs": [
		    { "width": "25%", "targets": 2 },
		    { "width": "25%", "targets": 3 }
		],
		columns: cols,
	    searching: false,
	    ordering: false,
	    processing: true,
	    serverSide: true,    
		scrollX: true, 
	});
}

$('#btn-reset-coal').click(function () { 
	$('#frm-tracking-coal')[0].reset();
	get_list_coal(); 
});

$('#btn-tracking-coal').click(function () {  
	get_list_coal(); 
}); 
 

function view(id)
{
	$.redirect(baseurl+'client/lse/view', {id: id, csrf_appls: csrfName}, "POST", "_blank");
}

function edit(id)
{
	$.redirect(baseurl+'client/lse/edit', {id: id, csrf_appls: csrfName}, "POST", "_self");
}
  
// 06-11-2023
$('#btn-reset-coal').click(function () {   
	$('#frm-tracking-coal')[0].reset();
	$("#s_idCabang").empty().trigger('change');
});

function view_log(id) {
	const callback = function (resp) {  
		var modalPilihDok = new bootstrap.Modal('#modalLog')
		modalPilihDok.show();
	  
		new DataTable('#table-log-lse', {
			destroy: true,
			searching: false,
			ordering: false,
			processing: true,
		});

		$("#table-log-lse tbody").html(resp.html);

		$("#text-jenis-ls").text(":    " + resp.jenisLS); 
		$("#text-no-ls").text(":    " + resp.lsNo); 
		$("#text-no-draft").text(":    " + resp.draftNo); 
		
	}

	if (id !== undefined) {
		var formData = new FormData();
		formData.append('idLs', id);
		postAjax(baseurl + 'log/view_log', formData, callback);
	}
}

$(document).on("click", ".btn-view-file", function (e) {
	$.redirect(baseurl+'client/lse/view_file', {id: $("#idData").val(), csrf_appls: csrfName}, "POST", "_blank");
});

function del(id)
{

	const callback = function (resp) {
		showAlert(resp);
		$("#table-data").DataTable().ajax.reload();
	}

	swal_confirm('Konfirmasi hapus','Hapus Data LS ?',function (confirm) {
	    if (confirm) {

			var formData = new FormData();
			formData.append("idLs", id);

			var url = baseurl + "client/lse/delete";
			postAjax(url,formData,callback);
	    }
    });
}


function create_perubahan()
{
	swal_confirm('Konfrimasi Perubahan LS','Buat perubahan terhadap LS ini ? Data perubahan akan otomatis dibuat pada menu konsep',function (confirm) {
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