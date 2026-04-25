function act()
{
	const callback = function (resp) { 
		if (resp.code == '00') {
			var no = 1;
			var tabelData = "";
			var respData = resp.data.data;

			// console.log(resp.data.data);
			respData.forEach(function (data) { 
				if(data.tgl_ntpn !== undefined){
					tabelData += "<tr>";
					tabelData += "<td>"+ no +"</td>";
					tabelData += "<td>"+ data.ntpn +"</td>";
					tabelData += "<td>"+ data.tgl_ntpn +"</td>";
					tabelData += "<td>"+ data.npwp +"</td>";
					tabelData += "<td>"+ data.nama_perusahaan +"</td>";
					tabelData += "<td>"+ data.tonase_ntpn +"</td>";
					tabelData += "<td>"+ data.terpakai +"</td>";
					tabelData += "<td>"+ data.saldo +"</td>";
					tabelData += "</tr>";
				} else {
					tabelData += "<tr>";
					tabelData += "<td>"+ no +"</td>";
					tabelData += "<td>"+ data.ntpn +"</td>";
					tabelData += '<td colspan="6" align="center"> Data Tidak Valid</td>'; 
					tabelData += "</tr>";
				}

				no++;
			});
 
			$("#table-ntpn tbody tr").remove(); 
			$("#table-ntpn").find('tbody').append(tabelData); 
		} 

		showAlert(resp);
	}
    
    let arrNtpn = $("#ntpn").val();
      
	if(arrNtpn === ' ' || arrNtpn === null){ 
		showAlert("Silahkan input NTPN terlebih dahulu");
	} else { 
		var formData = new FormData();
		formData.append("ntpn", arrNtpn);
		var url = baseurl + "services/checkntpn/act";
		postAjax(url, formData, callback);
	} 
}