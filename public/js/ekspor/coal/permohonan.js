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
				url: baseurl+'ekspor/coal/ls/pengajuan/list',
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
	$.redirect(baseurl+'ekspor/coal/ls/pengajuan/view', {id: id, csrf_appls: csrfName}, "POST", "_blank");
}

function edit(id)
{
	$.redirect(baseurl+'ekspor/coal/ls/pengajuan/process', {id: id, csrf_appls: csrfName}, "POST", "_self");
}

$(document).on("click", "#btn-create-ls", function (e) { // <-- see second argument of .on
  e.preventDefault();
  swal_confirm('Konfrimasi','Ini adalah pengajuan Online Simbara. Lanjutkan buat draft LS ?',function (confirm) {
	    if (confirm) {
			$("#btn-create-ls").attr("disabled", true);

	    	const callback = function (resp) {
				showAlert(resp);
				if(resp.code == '00'){
					$("#btn-create-ls").removeAttr("disabled"); 
					$("#btn-create-ls").hide();
					
					var urlRedirect = baseurl+'ekspor/coal/ls/konsep';
					alertRedirect(resp,5000,urlRedirect);
				}
			}

			var formData = new FormData();
			formData.append('idPermohonan',$("#idPermohonan").val());
			postAjax(baseurl+'ekspor/coal/ls/pengajuan/create-ls',formData,callback);
	    }
  	});
});
 
// 10-3-2024  
function view_log_lnsw(id) {
	const callback = function (resp) {  
		var modalPilihDok = new bootstrap.Modal('#modalLog')
		modalPilihDok.show();
	  
		new DataTable('#table-log-lse', {
			destroy: true,
			searching: false,
			ordering: false,
			processing: true,
			"columns": [
				{ className: "text-center text-nowrap align-middle" },
				{ className: "text-nowrap align-middle" },
				{ className: "text-nowrap align-middle" },
				{ className: "align-middle"},
				{ className: "text-center align-middle" },
				{ className: "text-center align-middle" },
				{ className: "text-center align-middle" }
			],
		});

		$("#table-log-lse tbody").html(resp.html); 
		$("#text-aju").text(":    " + resp.nomorAju); 
		$("#text-permohonan").text(":    " + resp.nomorPermohonan);   
	}

	if (id !== undefined) {
		var formData = new FormData();
		formData.append('idLs', id);
		postAjax(baseurl + 'log/view-log-lnsw', formData, callback);
	}
}

function pengembalian(idPermohonan){
	Swal.fire({
	  title: "Konfirmasi",
    html: "Aksi ini akan mengembalikan pengajuan LSE ke INSW, bila ada LS dalam proses dengan nomor aju ini statusnya akan otomatis dihapus. Lanjutkan ?",
	  input: "text",
	  inputAttributes: {
	    autocapitalize: "off"
	  },
	  inputPlaceholder: "Keterangan pengembalian / penolakan",
	  confirmButtonText: "Ya",
	  showLoaderOnConfirm: true,
	  icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Ya",
    cancelButtonText: "Batal",
	  preConfirm: async (keterangan) => {

	  	if (!keterangan) {
	      Swal.showValidationMessage('<i class="fa fa-info-circle"></i> Keterangan harus di isi')
	    }
	    else{
	    	const callback = function (resp) {
					if(resp.code == '00'){
						$('#table-pengajuan').DataTable().ajax.reload(null,false);
					}
					showAlert(resp);
				}

		  	var formData = new FormData();
				formData.append('idPermohonan',idPermohonan);
				formData.append('keterangan',keterangan);
				postAjax(baseurl+'api/simbara/send-pengembalian',formData,callback);
	    }

	  },
	  allowOutsideClick: () => !Swal.isLoading()
	}).then((result) => {
	  if (result.isConfirmed) {

	  }
	  else{
      Swal.fire(
        'Dibatalkan!',
        'Aksi dibatalkan',
        'success'
      )
    }
	});
}