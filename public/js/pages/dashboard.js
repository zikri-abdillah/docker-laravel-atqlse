$(document).ready(function() {    
	get_pengajuan_terakhir();  
	get_penerbitan_terakhir(); 
	get_user_waiting();
});
 
function get_pengajuan_terakhir() {  
	var searchParam = JSON.stringify($('#frm-tracking-coal').serializeArray());
	
	if ($.fn.DataTable.isDataTable('#tabel-pengajuan')) {
		$('#tabel-pengajuan').DataTable().destroy();
	}

	new DataTable('#tabel-pengajuan', {
		ajax: {
	        url: baseurl+'beranda/pengajuan-terakhir',
			type: 'POST', 
			"data": { searchParam: searchParam, csrf_appls: csrfName }
		},
		"columnDefs": [
		    { "width": "5%", "targets": 0 },
		    { "width": "25%", "targets": 1 },
		    { "width": "20%", "targets": 2 },
		    { "width": "25%", "targets": 3 },
		    { "width": "20%", "targets": 4 },
		],
		"columns": [
		    { className: "text-center align-top" },
		    { className: "text-nowrap align-top" },
		    { className: "text-center align-top" },
		    { className: "text-nowrap align-top" },
		    { className: "text-center align-top" },
		],
	    searching: false,
	    ordering: false,
	    processing: true,
	    serverSide: true,    
		scrollX: true, 
		autoWidth: true,
        pagination:false,
	});
}

function get_penerbitan_terakhir() {  
	var searchParam = JSON.stringify($('#frm-tracking-coal').serializeArray());
	
	if ($.fn.DataTable.isDataTable('#tabel-penerbitan')) {
		$('#tabel-penerbitan').DataTable().destroy();
	}

	new DataTable('#tabel-penerbitan', {
		ajax: {
	        url: baseurl+'beranda/penerbitan-terakhir',
			type: 'POST', 
			"data": { searchParam: searchParam, csrf_appls: csrfName }
		},
		"columnDefs": [
		    { "width": "5%", "targets": 0 },
		    { "width": "25%", "targets": 1 },
		    { "width": "20%", "targets": 2 },
		    { "width": "25%", "targets": 3 },
		    { "width": "20%", "targets": 4 },
		],
		"columns": [
		    { className: "text-center align-top" },
		    { className: "text-nowrap align-top" },
		    { className: "text-center align-top" },
		    { className: "text-nowrap align-top" },
		    { className: "text-center align-top" },
		],
	    searching: false,
	    ordering: false,
	    processing: true,
	    serverSide: true,    
		scrollX: true, 
		autoWidth: true,
        pagination:false,
	});
}

function get_user_waiting() {
	const callback = function (resp) {   
		$("#list_user_waiting").html(resp.content);
	}

	var formData = new FormData();  
	var url = baseurl+'beranda/user-waiting';
	postAjax(url, formData, callback);
}

function detail(id) {
	$.redirect(baseurl + 'management/user/detail', { id: id, csrf_appls: csrfName }, "POST", "_blank");
}
  
function view(id)
{
	$.redirect(baseurl+'beranda/view-lse', {id: id, csrf_appls: csrfName}, "POST", "_blank");
}