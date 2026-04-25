$(document).ready(function() {
	//alert('load');
});

function act()
{
	const callback = function (resp) {  
		$("#table-header-izin tbody tr").remove();
		$("#table-kepatuhan-izin tbody tr").remove(); 
		$("#table-komoditas-izin tbody tr").remove(); 
		
		if (resp.code == '00') {
			var no = 1;
			var tabelHeader = "";
			var tabelPetuh = "";
			var tabelKomoditas = "";
			var respHeader = resp.data['header'];
			var respKepatuhan = resp.data['kepatuhan'];
			var respKomoditas = resp.data['komoditas'];

			if(respHeader['no_izin'] !== undefined){
				tabelHeader += "<tr>"; 
				tabelHeader += "<td>"+ respHeader['nib'] +"</td>";
				tabelHeader += "<td>"+ respHeader['npwp'] +"</td>";
				tabelHeader += "<td>"+ respHeader['nitku'] +"</td>";
				tabelHeader += "<td>"+ respHeader['nama_perusahaan'] +"</td>";
				tabelHeader += "<td>"+ respHeader['no_izin'] +"</td>";
				tabelHeader += "<td>"+ respHeader['tgl_izin'] +"</td>";
				tabelHeader += "<td>"+ respHeader['tgl_akhir'] +"</td>";
				tabelHeader += "</tr>"; 
			} else {
				tabelHeader += "<tr>"; 
				tabelHeader += '<td colspan="7" align="center"> Data Tidak Valid</td>'; 
				tabelHeader += "</tr>";
			}
 
			$("#table-header-izin").find('tbody').append(tabelHeader); 
			
			if(respKepatuhan['kode'] !== undefined){
				tabelPetuh += "<tr>"; 
				tabelPetuh += "<td>"+ respKepatuhan['kode'] +"</td>";
				tabelPetuh += "<td>"+ respKepatuhan['keterangan'] +"</td>"; 
				tabelPetuh += "</tr>"; 
			} else {
				tabelPetuh += "<tr>"; 
				tabelPetuh += '<td colspan="2" align="center"> Data Tidak Valid</td>'; 
				tabelPetuh += "</tr>";
			}

			$("#table-kepatuhan-izin").find('tbody').append(tabelPetuh); 
 
			respKomoditas.forEach(function (data) { 
				if(data.seri !== undefined){
					tabelKomoditas += "<tr>";
					tabelKomoditas += "<td>"+ data.seri +"</td>";
					tabelKomoditas += "<td><b>HS / Pos Tarif</b> : "+ data.pos_tarif +"<br/>"; 
					tabelKomoditas += "<b>Uraian</b> : "+ data.ur_barang +"</td>"; 
					// tabelKomoditas += "<b>Spesifikasi</b> : "+ data.spesifikasi +"</td>"; 

					tabelKomoditas += "<td>"+ data.spesifikasi +"</td>"; 

					tabelKomoditas += "<td><b>Kuota</b> : "+ data.jml_volume +"<br/>"; 
					tabelKomoditas += "<b>Kuota Terpakai LS</b> : "+ data.terpakai_ls +"<br/>"; 
					tabelKomoditas += "<b>Kuota Tepakai Booking</b> : "+ data.terpakai_booking +"<br/>"; 
					tabelKomoditas += "<b>Kuota Sisa</b>: "+ data.sisa_volume +"<br/>"; 
					tabelKomoditas += "<b>Kuota Tersedia</b> : "+ data.avail_volume +"<br/><br/>"; 
					tabelKomoditas += "<b>Satuan</b> : "+ data.jns_satuan +"</td>"; 

					// tabelKomoditas += "<td>"+ data.jns_satuan +"</td>"; 

					tabelKomoditas += "<td><b>Pelabuhan Asal</b> : "+ data.plb_asal +"<br/>"; 
					tabelKomoditas += "<b>Pelabuhan Muat</b> : "+ data.plb_muat +"<br/>";
					tabelKomoditas += "<b>Pelabuhan Tujuan</b> : "+ data.plb_tujuan +"<br/>"; 
					tabelKomoditas += "<b>Pelabuhan Bongkar</b> : "+ data.plb_bongkar +"</td>"; 

					
					tabelKomoditas += "<td><b>Negara Asal</b> : "+ data.neg_asal +"<br/>"; 
					tabelKomoditas += "<b>Negara Muat</b> : "+ data.neg_muat +"<br/>"; 
					tabelKomoditas += "<b>Negara Asal</b> : "+ data.neg_tujuan +"</td>";  

					tabelKomoditas += "</tr>";
				} else {
					tabelKomoditas += "<tr>"; 
					tabelKomoditas += '<td colspan="6" align="center"> Data Tidak Valid</td>'; 
					tabelKomoditas += "</tr>";
				}

				no++;
			});

			$("#table-komoditas-izin").find('tbody').append(tabelKomoditas); 
		} 

		showAlert(resp);
	}

	var formData = new FormData();;
	formData.append("nib", $("#nib").val());
	formData.append("npwp", $("#npwp").val());
	formData.append("idtku", $("#idtku").val());
	formData.append("probis", $("#probis").val());
	formData.append("noIzin", $("#noIzin").val());
	formData.append("tglIzin", $("#tglIzin").val());
 
	var url = baseurl + "services/checkizin/act";
	postAjax(url,formData,callback);

}