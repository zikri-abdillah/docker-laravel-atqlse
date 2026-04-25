$(document).ready(function() {  
	getCaptcha();
	
	var pathname = window.location.pathname; 

	if(pathname.indexOf('registrasi') != -1){
		initselectdua('.select-propinsi', baseurl + 'select/propinsi', '', 0); 
		initselectdua('.select-kota', baseurl + 'select/kota');
		initselectdua('.select-jenis-iup', baseurl + 'select/jenisiup', '', 0);  
	}
});
 
$("#btn-login").click(function(event) {
	$(".login-msg").html('');

	let csrfName = $('meta[name="APPLS-CSRF-TOKEN"]').attr("content");
	let uname 	 = $('#uname').val();
	let upass 	 = $('#upass').val();
	let ucaptcha = $('#ucaptcha').val();

	$.post( baseurl+"login/act", { uname: uname, upass: upass, ucaptcha: ucaptcha, csrf_appls: csrfName })
	.done(function( resp ) {
		if(resp.code == '00')
		{
			window.location.replace(resp.data.to);
		} else {
			showAlert(resp); 

			if(resp.msg == "Captcha tidak sesuai"){
				getCaptcha();
				$("#ucaptcha").val(''); 
			} else { 
				$(".login-msg").html(resp.msg); 
			}
		}
	})
	.done(function() {
		console.log( "second success" );
	})
	.fail(function() {
		console.log( "error" );
	})
	.always(function() {
		console.log( "finished" );
	});
});

function getCaptcha() {  
	$('#captcha-image').html('');
	const callback = function (resp) {
		$('#captcha-image').html(resp.msg);  
	}

	var formData = new FormData();  
	var url = baseurl + "login/getCaptcha";
	postAjax(url,formData,callback);
}

$(".toggle-password").click(function() { 
	// $(this).toggleClass("fa fa-eye");
 
	var input = $($(this).attr("toggle"));

	if (input.attr("type") == "password") {
		input.attr("type", "text");
	} else {
		input.attr("type", "password");
	}
});

// 2024-02-22 
$('#btn-form-register').click(function () {  
	$.redirect(baseurl + 'registrasi', {}, "GET", "_self");
});

$('#btn-cancel').click(function () {  
	$.redirect(baseurl, {}, "GET", "_self");
});

$('#btn-reset-register').click(function () {
	$('#form-registrasi')[0].reset();
});

$('#btn-save-register').unbind('click').click(function (event) { 
	event.preventDefault();

	const callback = function (resp) {
		showAlert(resp); 
		$("#btn-save-register").removeAttr("disabled"); 

		if (resp.code == '00') {  
			alertRedirect(resp, 14000,baseurl); 
			$("#btn-save-register").hide();
			$("#btn-reset-register").hide();
			$("#btn-cancel").text("Back to Login Page"); 
		}

	}
 
	var jenisIUP 	= $("#i_idJenisIup").select2('data'); 
	var param 		= $("#form-registrasi").serializeArray()
	var postdata 	= JSON.stringify(param);
	var formData 	= new FormData();
	formData.append("postdata", postdata); 
	formData.append("jenisIUP", jenisIUP[0].text);
	formData.append('fileNPWP',$('#i_fileNPWP')[0].files[0]); 
	formData.append('fileNIB',$('#i_fileNIB')[0].files[0]); 
	formData.append('fileKTP',$('#i_fileKTP')[0].files[0]); 
 
	swal_confirm('Konfirmasi', 'Apakah data user sudah sesuai?', function (confirm) {
		if (confirm) { 
			// $("#btn-save-register").attr("disabled", true);

			var url = baseurl + "registrasi/save";
			postAjax(url, formData, callback);
		}
	});
}); 