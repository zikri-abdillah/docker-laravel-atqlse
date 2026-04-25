$(document).ready(function() {
	get_list_pengajuan();
});

$('#btn-reset-pengajuan').click(function () { 
	$('#frm-tracking-pengajuan')[0].reset();
	get_list_pengajuan(); 
});

$('#btn-tracking-pengajuan').click(function () {  
	get_list_pengajuan(); 
}); 
 
function get_list_pengajuan() {  
	var url      = window.location.href;
	  
	if(url.indexOf('view') == -1){ 
		var searchParam = JSON.stringify($('#frm-tracking-pengajuan').serializeArray());
		
		if ($.fn.DataTable.isDataTable('#table-pengajuan')) {
			$('#table-pengajuan').DataTable().destroy();
		}

		new DataTable('#table-pengajuan', {
			ajax: {
				url: baseurl+'client/lse/online/list',
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
}

function view(id)
{
	$.redirect(baseurl+'client/lse/online/view', {id: id, csrf_appls: csrfName}, "POST", "_blank");
}

function edit(id)
{
	$.redirect(baseurl+'client/lse/online/process', {id: id, csrf_appls: csrfName}, "POST", "_self");
}

