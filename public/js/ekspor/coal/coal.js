$(document).ready(function() {  
	initselectdua('.select-cabang',baseurl+'select/cabang','',0);

	get_list_coal();
});

$( window ).on( "resize", function() {
	//$($.fn.dataTable.tables(true)).DataTable().columns.adjust();
} );

$(document).on("click", ".app-sidebar__toggle", function (e) {
	$('#table-coal').DataTable().columns.adjust().draw();
});


function get_list_coal() {  
	var searchParam = JSON.stringify($('#frm-tracking-coal').serializeArray());
	
	if ($.fn.DataTable.isDataTable('#table-coal')) {
		$('#table-coal').DataTable().destroy();
	}

	new DataTable('#table-coal', {
		ajax: {
	        url: baseurl+'ekspor/coal/ls/list/'+$('#dataFilter').val(),
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
		autoWidth: true,
	});
}

$('#btn-reset-coal').click(function () { 
	$('#frm-tracking-coal')[0].reset();
	get_list_coal(); 
});

$('#btn-tracking-coal').click(function () {  
	get_list_coal(); 
}); 

function del(id)
{
	const callback = function (resp) {
		showAlert(resp);
		//$("#table-data").DataTable().ajax.reload();
		get_list_coal();
	}

	swal_confirm('Konfirmasi hapus','Hapus Data LS ?',function (confirm) {
	    if (confirm) {

			var formData = new FormData();
			formData.append("idLs", id);

			var url = baseurl + "ekspor/coal/ls/delete";
			postAjax(url,formData,callback);
	    }
    });
}

function view(id)
{
	$.redirect(baseurl+'ekspor/coal/ls/view', {id: id, csrf_appls: csrfName}, "POST", "_blank");
}

function edit(id)
{
	$.redirect(baseurl+'ekspor/coal/ls/edit', {id: id, csrf_appls: csrfName}, "POST", "_self");
}

function cabut(id)
{ 
	const callback = function (resp) {
		showAlert(resp);
		get_list_coal();
	}
    Swal.fire({
	  title: 'Batalkan Penerbitan LS ?',
      html: 'LS yang dibatalkan tidak dapat di gunakan kembali. Pastikan status LS berlaku pada tracking LNSW.',
      icon: 'warning',
	  input: 'text',
	  inputPlaceholder: 'Keterangan pembatalan',
	  inputAttributes: {
	    autocapitalize: 'off'
	  },
	  showCancelButton: true,
	  confirmButtonText: 'YA',
	  showLoaderOnConfirm: true,
	  preConfirm: (note) => {
	  	if(note)
		  	return note;
		else
			Swal.showValidationMessage('Harap mengisi keterangan pembatalan')
	  },
	  allowOutsideClick: () => !Swal.isLoading()
	}).then((result) => {
	  if (result.isConfirmed) {
	  		var formData = new FormData();
			formData.append("idLs", id);
			formData.append("note", result.value);

			var url = baseurl + "ekspor/coal/ls/batal";
			postAjax(url,formData,callback);
	  }
	})
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

$(document).on("click", "#btn-xml-inatrade", function (e) { // <-- see second argument of .on
  e.preventDefault();
  var idls = $(this).data('iddata');

  swal_confirm('Konfrimasi Kirim Inatrade','Kirim data elektronik LS ke Inatrade ?.',function (confirm) {
    if (confirm) {
    	send_xml_ls(idls);
    }
  });
});

function send_xml_ls(idls)
{
	const callback = function (resp) {
		showAlert(resp);
		//$("#table-data").DataTable().ajax.reload();
		get_list_coal();
	}
	var formData = new FormData();
	formData.append('idLs', idls);
	postAjax(baseurl + 'services/xml/coal/send_inatrade', formData, callback);
}


$(document).on("click", "#btn-json-inatrade", function (e) { // <-- see second argument of .on
  e.preventDefault();
  var idls = $(this).data('iddata');

  swal_confirm('Konfrimasi Kirim Inatrade','Kirim data elektronik LS ke Inatrade ?.',function (confirm) {
    if (confirm) {
    	send_json_ls(idls);
    }
  });
});

function send_json_ls(idls)
{
	$('body').loading('start');
	const callback = function (resp) {
		$('body').loading('stop');
		get_list_coal();
		showAlert(resp);
	}
	var formData = new FormData();
	formData.append('idLs', idls);
	postAjax(baseurl + 'services/coal/test_send_doc_simbara', formData, callback);
}

// 2024-07015
function rolback(id)
{
	const callback = function (resp) { 
		if(resp.code == '00'){
			var urlRedirect = baseurl+'ekspor/coal/ls/konsep';
			alertRedirect(resp,5000,urlRedirect);
		} else {
			showAlert(resp); 
		} 
	}

	Swal.fire({
		title: "Rollback",
		html: '<textarea class="form-control" id="alasan_tolak" name="alasan_tolak" placeholder="Alasan Rollback" rows="3"></textarea>',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Ya',
		cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {  
				var formData = new FormData(); 
				formData.append("iddata", id);
				formData.append("alasan", $("#alasan_tolak").val());
			
				var url = baseurl + "ekspor/coal/ls/rollback";
				postAjax(url,formData,callback); 
			}
			else{
				Swal.fire(
				'Dibatalkan!',
				'Aksi dibatalkan',
				'success'
				)
			}
		})
}

function print(id)
{
	$.redirect(baseurl+'print/coal/lse', {id: id, csrf_appls: csrfName}, "POST", "_blank");
}

// 4-09-2024 
$('#btn-export-coal').click(function () {
	var url = baseurl+'ekspor/rekapitulasi/lse/export';
	var searchParam = JSON.stringify($('#frm-tracking-coal').serializeArray());
	$.redirect(url, {searchParam: searchParam, csrf_appls: csrfName}, "POST", "_blank");
});