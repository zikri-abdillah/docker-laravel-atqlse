$(document).ready(function() {
	//alert('load');
});

function find()
{
	const callback = function (resp) { 
		if (resp.code == '00') {
			var no = 1;
			var tabelData = "";
			var respData = resp.data.data;
  
			if(respData !== undefined){
				if(respData['jenis_barang'] === 1){ 
					var jenis_barang = 'Batubara';
				}

				if(respData['hasil_survey'] === 1){ 
					var hasil_survey = 'Yes';
				} else {
					var hasil_survey = 'No';
				}

				tabelData += "<tr>";
				tabelData += "<td>"+ no +"</td>";
				tabelData += "<td>"+ respData['no_sertifikat'] +"</td>"; 
				tabelData += "<td>"+ respData['nama_moda_transplb'] +"</td>";
				tabelData += "<td>"+ respData['nama_tertanggung'] +"</td>";
				tabelData += "<td><b>Jenis Barang</b> : "+ jenis_barang +"<br/>";
				tabelData += "<b>Volume Rencana</b> : "+ respData['volume_rencana'] +" (<b>"+ respData['satuan_rencana'] +"</b>) </td>";
				tabelData += "<td><b>Tanggal Berangkat</b> : "+ respData['tgl_berangkat'] +"<br/>";
				tabelData += "<b>Tanggal Insert</b> : "+ respData['tgl_insert'] +"<br/>";
				tabelData += "<b>Tanggal Survey</b> : "+ respData['tgl_survey'] +"</td>";
				tabelData += "<td>"+ respData['nilai_premi'] +"</td>";
				tabelData += "<td>"+ hasil_survey +"</td>";
				tabelData += "</tr>";
			} else {
				tabelData += "<tr>";
				tabelData += "<td>"+ no +"</td>"; 
				tabelData += '<td colspan="8" align="center"> Data Tidak Valid</td>'; 
				tabelData += "</tr>";
			}  
		} 

		$("#table-asuransi tbody tr").remove(); 
		$("#table-asuransi").find('tbody').append(tabelData); 

		showAlert(resp);
	}
    
	var formData = new FormData();
	formData.append("nosertifikat", $("#nosertifikat").val());

	var url = baseurl + "services/asuransi/actfind";
	postAjax(url,formData,callback);

}

function survey()
{
	const callback = function (resp) {   
		var respData = resp.data.data;
		var code 	 = resp.data.kode; 
		var ket 	 = respData['keterangan'];
		ket 		 = ket.replace("MInvalid Schema Validation :icrosoft", "");
		ket 		 = ket.replace("Invalid Schema Validation : no_sertifikat :", " Asuransi nomor ");
		
		if(code == 200){
			var title = 'Success!'; 
		} else if(code == 400){
			var title = 'Warning!'; 
		} else {
			var title = 'Failed!'; 
		}

		if (resp.code == '00') {
			// var modalPilihDok 	= new bootstrap.Modal('#modalRespon');  
			// $("#ketRespon").html(respData['keterangan']);  			 
			// modalPilihDok.show(); 
			
			var error = {code:"00", msg: ket, text:title};
			showAlert(error); 
		} else { 
			var error = {code:"99", msg: ket, text:title};
			showAlert(error);
		}
	}
    
	var formData = new FormData();
	formData.append("nosertifikat", $("#nosertifikat").val());
    formData.append("tglsurvey", $("#tglsurvey").val());
    formData.append("hasilsurvey", $("#hasilsurvey").val());
    formData.append("keterangan", $("#keterangan").val());

	var url = baseurl + "services/asuransi/actsurvey";
	postAjax(url,formData,callback);

}