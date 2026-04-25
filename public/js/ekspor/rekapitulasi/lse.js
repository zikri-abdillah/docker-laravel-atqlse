$(document).ready(function() {  
	initselectdua('.select-jenis-ls',baseurl+'select/jenisls','',0);
	initselectdua('.select-cabang',baseurl+'select/cabang','',0);

	get_list_coal();
});

function get_list_coal() {  
	var searchParam = JSON.stringify($('#frm-tracking-coal').serializeArray());
	
	if ($.fn.DataTable.isDataTable('#table-coal')) {
		$('#table-coal').DataTable().destroy();
	}

	new DataTable('#table-coal', {
		ajax: {
	        url: baseurl+'ekspor/rekapitulasi/lse/list',
			type: 'POST', 
			"data": { searchParam: searchParam, csrf_appls: csrfName }
		},
		"columnDefs": [
		    { "width": "25%", "targets": 3 },
		    { "width": "20%", "targets": 4 }
		],
		"columns": [
		    { className: "text-center text-nowrap align-top" },
		    { className: "text-nowrap align-top" },
		    { className: "text-nowrap align-top" },
		    { className: "align-top"},
		    { className: "align-top" },
		    { className: "text-center align-top" },
		    { className: "text-center align-top" }
		],
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
  
function view(id, jenisLS)
{ 
	var url = baseurl+'ekspor/coal/ls/view'; 
	$.redirect(url, {id: id, csrf_appls: csrfName}, "POST", "_blank");
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
 
// 4-09-2024 
$('#btn-export-coal').click(function () {
	var url = baseurl+'ekspor/rekapitulasi/lse/export';
	var searchParam = JSON.stringify($('#frm-tracking-coal').serializeArray());
	$.redirect(url, {searchParam: searchParam, csrf_appls: csrfName}, "POST", "_blank");
});