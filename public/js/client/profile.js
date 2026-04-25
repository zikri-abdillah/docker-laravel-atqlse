$(document).ready(function () { 
	initselectdua('.select-role', baseurl + 'select/role', 'internal', 0);
	initselectdua('.select-type', baseurl + 'select/type', '', 0);
	initselectdua('.select-cabang', baseurl + 'select/cabang', '', 0);
	
	initselectdua('.select-propinsi', baseurl + 'select/propinsi', '', 0); 
	initselectdua('.select-kota', baseurl + 'select/kota');
	initselectdua('.select-jenis-iup', baseurl + 'select/jenisiup', '', 0);  
})

$('#btn-edit-profile').unbind('click').click(function (event) {
	$.redirect(baseurl + 'profile/edit', {}, "GET", "_self");  
});

$('#btn-back-profile').unbind('click').click(function (event) {
	$.redirect(baseurl + 'internal/beranda', {}, "GET", "_self");  
});

$('#btn-reset-profile').unbind('click').click(function (event) {
	$('#form-profile')[0].reset();
});

$('#btn-save-profile').unbind('click').click(function (event) {
	event.preventDefault();

	const callback = function (resp) {
		showAlert(resp);

		$("#btn-save-profile").removeAttr("disabled");  
 
		if (resp.code == '00') { 
			$("#btn-save-profile").hide();
			$("#btn-reset-profile").hide();

			var urlRedirect = baseurl+'profile';
			alertRedirect(resp, 14000, urlRedirect); 
		}
	}
  
	var param 		= $("#form-profile").serializeArray()
	var postdata 	= JSON.stringify(param);
	var formData 	= new FormData();
	formData.append("postdata", postdata); 
	formData.append("idUser", $("#idUser").val());
	formData.append("idProfile", $("#idProfile").val()); 

	swal_confirm('Konfirmasi', 'Apakah data profile anda sudah sesuai?', function (confirm) {
		if (confirm) { 
			// $("#btn-save-profile").attr("disabled", true);
   
			if($("#type").val() == '1'){  
				var jenisIUP 	= $("#i_idJenisIup").select2('data'); 
				formData.append("jenisIUP", jenisIUP[0].text); 
 
				var url = baseurl + "profile/savepu";
			} else { 
				var url = baseurl + "profile/save";
			}
			 
			postAjax(url, formData, callback);
		}
	});
});
