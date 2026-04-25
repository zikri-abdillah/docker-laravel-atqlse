$(document).ready(function() {
	get_list();
});


function get_list() {
	var searchParam = JSON.stringify($('#frm-search').serializeArray());

	if ($.fn.DataTable.isDataTable('#table-data')) {
		$('#table-data').DataTable().destroy();
	}

	new DataTable('#table-data', {
		ajax: {
	        url: baseurl+'ekspor/rekapitulasi/laporan-bulanan/list',
			type: 'POST',
			"data": { searchParam: searchParam, csrf_appls: csrfName }
		},

		"columns": [
		    { className: "text-center text-nowrap align-top" },
		    { className: "text-nowrap align-top" },
		    { className: "text-nowrap align-top" },
		    { className: "align-top"},
		    { className: "text-nowrap align-top" },
		    { className: "text-nowrap align-top" },
		    { className: "text-nowrap text-center align-top" }
		],
	    searching: false,
	    ordering: false,
	    processing: true,
	    serverSide: true,
		scrollX: true,
	});
}

function showModalAdd()
{
	$("#form-laporan").trigger('reset');
	$("#i_fileLaporan").val('');
	$(".div-upload-file").removeClass('d-none');
	$(".div-view-file").removeClass('d-none').addClass('d-none');
	$("#i_bulan").val('').trigger('change');
	$("#i_tahun").val('').trigger('change');
	var modal = new bootstrap.Modal(document.getElementById('modalAddLaporan'))
	modal.show();
}

function view_draft_laporan()
{
	const callback = function (resp) {
		$("#div-draft-summary").html(resp.data);
	}

	var formData = new FormData();
	formData.append('bulan', $("#i_bulan").val());
	formData.append('tahun', $("#i_tahun").val());
	postAjax(baseurl + 'ekspor/rekapitulasi/laporan-bulanan/draft-summary', formData, callback);
}

function save_draft()
{
	const callback = function (resp) {
		if(resp.code == '00'){
			save()
		}
		else if(resp.code == '01'){
			swal_confirm('Konfirmasi','Laporan untuk periode bulan dan tahun yang dippilih sudah pernah dikirim. Buat laporan perubahan ? Nomor dan tanggal laporan akan disamakan dengan laporan yang sudah pernah terkirim',function (confirm) {
		    if (confirm) {
					save('PERUBAHAN');
		    }
	    });
		}
		else if(resp.code == '99'){
			showAlert(resp);
		}
	}

	var formData = new FormData();
	formData.append('idLaporan', $("#idLaporan").val());
	formData.append('noLaporan', $("#i_noLaporan").val());
	formData.append('tglLaporan', $("#i_tglLaporan").val());
	formData.append('bulan', $("#i_bulan").val());
	formData.append('tahun', $("#i_tahun").val());
	postAjax(baseurl + 'ekspor/rekapitulasi/laporan-bulanan/save-check', formData, callback);
}

function save(act='BARU')
{
	const callback = function (resp) {
		if(resp.code == '00'){
			$('#table-data').DataTable().ajax.reload();
			$(".btn-close").trigger('click');
		}
		showAlert(resp);
	}

	var formData = new FormData();
	formData.append('idLaporan', $("#idLaporan").val());
	formData.append('noLaporan', $("#i_noLaporan").val());
	formData.append('tglLaporan', $("#i_tglLaporan").val());
	formData.append('bulan', $("#i_bulan").val());
	formData.append('tahun', $("#i_tahun").val());
	formData.append('act', act);
	formData.append('fileLaporan',$('#i_fileLaporan')[0].files[0]);
	postAjax(baseurl + 'ekspor/rekapitulasi/laporan-bulanan/save-draft', formData, callback);
}

function delete_laporan(idLaporan)
{
	const callback = function (resp) {
		$('#table-data').DataTable().ajax.reload();
		showAlert(resp);
	}

	swal_confirm('Konfirmasi Hapus','Hapus laporan ?',function (confirm) {
	    if (confirm) {
			var formData = new FormData();
			formData.append('idLaporan', idLaporan);
			postAjax(baseurl + 'ekspor/rekapitulasi/laporan-bulanan/delete', formData, callback);
	    }
    });
}

function list_ls(id)
{

	$("#idLaporanModal").val(id);
	var modalElement = document.getElementById('modalDetailLs');
	if (!modalElement.classList.contains('show')) {
		if ($.fn.DataTable.isDataTable('#table-detail')) {
			$('#table-detail').DataTable().destroy();
		}
		$('#table-detail tbody').html('');
		$("#text-nomor").html('')
		$("#text-tanggal").html('')
		$("#text-periode").html('')
    var modal = new bootstrap.Modal(document.getElementById('modalDetailLs'))
		modal.show();
	}

	if ($.fn.DataTable.isDataTable('#table-detail')) {
		$('#table-detail').DataTable().destroy();
	}

	var searchParam = JSON.stringify($('#frm-search-detail').serializeArray());
	var table = new DataTable('#table-detail', {
		ajax: {
	        url: baseurl+'ekspor/rekapitulasi/laporan-bulanan/detail',
			type: 'POST',
			"data": { idLaporan: id, searchParam:searchParam, csrf_appls: csrfName }
		},

		"columns": [
		    { className: "text-center text-nowrap align-top" },
		    { className: "align-top"},
		    { className: "align-top" },
		    { className: "text-nowrap align-top" },
		    { className: "text-nowrap align-top" },
		    { className: "text-nowrap text-center align-top" }
		],
	    searching: false,
	    ordering: false,
	    processing: true,
	    serverSide: true,
		scrollX: true,
		pageLength: 25,
		lengthMenu: [25, 50, 75, 100, 125, 150, 200, 250, 350, 500]
	});

	table.on('draw', function (e, settings) {

		$("#text-nomor").html(settings.json.laporan.noLaporan)
		$("#text-tanggal").html(reverseDate(settings.json.laporan.tglLaporan))
		$("#text-periode").html(getBulanIndonesia(settings.json.laporan.bulan)+' - '+settings.json.laporan.tahun)
		if(settings.json.isFile){
			$('.btn-file-laporan').prop('disabled', false);
			//$(".btn-file-laporan").addClass('enable');
		}
		else{
			$('.btn-file-laporan').prop('disabled', true);
			//$(".btn-file-laporan").addClass('disabled');
		}
	});
}

$(document).on("click", "#btn-search-detail", function (e) { // <-- see second argument of .on
  list_ls($("#idLaporanModal").val());
});


function edit(idLaporan)
{
	const callback = function (resp) {
		if(resp.code = '00')
		{
			showModalAdd();

			$("#idLaporan").val(resp.data.id);
			$("#i_noLaporan").val(resp.data.noLaporan);
			$("#i_tglLaporan").val(reverseDate(resp.data.tglLaporan));
			var bulan = parseInt(resp.data.bulan) - 1;
			$("#i_bulan").val(bulan).trigger('change');
			$("#i_tahun").val(resp.data.tahun).trigger('change');
			if(resp.isFile)
			{
				$(".div-upload-file").removeClass('d-none').addClass('d-none');
				$(".div-view-file").removeClass('d-none');
			}
			else{
				$(".div-upload-file").removeClass('d-none');
				$(".div-view-file").removeClass('d-none').addClass('d-none');
			}

		}
	}

	var formData = new FormData();
	formData.append('idLaporan', idLaporan);
	postAjax(baseurl + 'ekspor/rekapitulasi/laporan-bulanan/edit', formData, callback);
}

$(document).on("click", ".btn-hapus-file", function (e) { // <-- see second argument of .on
  const callback = function (resp) {
  	if(resp.code=='00')
  	{
  		$(".div-upload-file").removeClass('d-none');
			$(".div-view-file").removeClass('d-none').addClass('d-none');
  	}
		showAlert(resp);
	}

	swal_confirm('Konfirmasi Hapus','Hapus file ? Setelah dihapus data tidak dapat dikembalikan',function (confirm) {
	    if (confirm) {
			var formData = new FormData();
			formData.append('idLaporan', $("#idLaporan").val());
			postAjax(baseurl + 'ekspor/rekapitulasi/laporan-bulanan/delete-file', formData, callback);
	    }
  });
});

$(document).on("click", ".btn-lihat-file", function (e) {
	$.redirect(baseurl+'ekspor/rekapitulasi/laporan-bulanan/attach', {idLaporan: $("#idLaporan").val(), csrf_appls: csrfName}, "POST", "_blank");
});

$(document).on("click", ".btn-file-laporan", function (e) {
	$.redirect(baseurl+'ekspor/rekapitulasi/laporan-bulanan/attach', {idLaporan: $("#idLaporanModal").val(), csrf_appls: csrfName}, "POST", "_blank");
});



$(document).on("click", ".btn-kirim-inatrade", function (e) { // <-- see second argument of .on
  e.preventDefault();
  var idLaporan = $(this).data('iddata');

  swal_confirm('Konfrimasi Kirim Inatrade','Kirim data laporan LS ke Inatrade ?.',function (confirm) {
    if (confirm) {
    	send_laporan(idLaporan);
    }
  });
});

function send_laporan(idls)
{
	$('body').loading('start');
	const callback = function (resp) {
		$('body').loading('stop');
		get_list();
		showAlert(resp);
	}
	var formData = new FormData();
	formData.append('idLaporan', idls);
	postAjax(baseurl + 'services/laporan/send', formData, callback);
}