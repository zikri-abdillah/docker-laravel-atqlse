'use strict';

$(function (e) {

	$('.mask-npwp').inputmask({
        mask: ['99.999.999.9-999.999'], // Format masker untuk NPWP 15 / 16 digit
        placeholder: '_',
        clearIncomplete: true // Hapus input yang tidak lengkap saat fokus keluar
    });

	$('.mask-npwp-16').inputmask({
        mask: ['9999.9999.9999.9999'], // Format masker untuk NPWP 15 / 16 digit
        placeholder: '_',
        clearIncomplete: true // Hapus input yang tidak lengkap saat fokus keluar
    });
 
	$(".mask-npwp-x").on("keyup change", function() {   
		var npwp = this.value.replaceAll('-', '').replaceAll('.', '').replaceAll('_', '');
		if(npwp.length > 15){    
			$(".mask-npwp-x").inputmask("9999.9999.9999.9999", {
				placeholder: "" 
			});
		} else if(npwp.length <= 15){   
			$(".mask-npwp-x").inputmask("99.999.999.9-999.9999", {
				placeholder: "" 
			});
		} 
	})

	$('.bs-datepicker').bootstrapdatepicker({
		autoclose: true,
		format: "dd-mm-yyyy",
		viewMode: "date",
		multidate: false,
		multidateSeparator: "-",
	})

	if ( $.isFunction($.fn.select2) ) {
		$(document).on('select2:open', () => {
			document.querySelector('.select2-search__field').focus();
		});
	}
});

function initselectdua(element, url, filter='',minlength=3, parent='')
{
	if ( $.isFunction($.fn.select2) ) {
		var ddparent = '';
		if(parent != '')
			ddparent = $("#"+parent);
		$(element).select2({
			dropdownParent: ddparent,
			minimumInputLength: minlength,
			width: '100%',
			ajax: {
				type: "POST",
	            url: url,
	            delay: 500,
	            dataType: 'json',
	            data: function (params) {
	               return {
	                  q: params.term,
	                  page: params.page,
	                  filter: filter,
	                  csrf_appls: csrfName,
	               };
	            },
	            processResults: function (data, params) {
	                params.page = params.page || 1;
	                return {
	                    results: data.data
	                };
	            },cache: false
	        }
		})
	}
}

