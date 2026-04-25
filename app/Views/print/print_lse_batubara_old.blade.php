
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>LSE Batubara</title>
	<style type="text/css">

		<?php  
			$mAtas = "215px";
			$mBawah = "0";
			$mKanan = "0";
			$mKiri = "0";
			$qrSize = "50%"; 
			
			if(!empty($opsiCetak)){
				$mAtas = $opsiCetak->mAtas ? $opsiCetak->mAtas : "215px";
				$mBawah = $opsiCetak->mBawah ? $opsiCetak->mBawah : "0";
				$mKanan = $opsiCetak->mKanan ? $opsiCetak->mKanan : "0";
				$mKiri = $opsiCetak->mKiri ? $opsiCetak->mKiri : "0";
				$qrSize = $opsiCetak->qrSize ? $opsiCetak->qrSize : "50%";  
			}
		?> 

		@page {
			margin-top: <?php echo $mAtas; ?>; 
			@if (count($asalbarangs) > 3)
				margin-bottom: <?php echo $mBawah; ?>; 
			@endif
		}

		body{
			font-family: 'Times';
			font-size: 8px;
		}

		.fs-iso{
			font-size: 8px;
			font-style: italic;
			color: grey;
		}

		.fs-hdr{
			font-size: 12px;
		}

		.text-nowrap{
			white-space: nowrap;
		}
		.fw-bold{
			font-weight:bold;
		}
		.text-center{
			text-align:center;
		}
		.text-right{
			text-align:right;
		}

		@if (count($asalbarangs) > 3)
			tr {
		        page-break-inside: auto; /* Biarkan Dompdf menentukan page breaks otomatis */
		    }

		    .page-break-before {
		        page-break-before: always; /* Memaksa page break sebelum baris ini */
		    }
		@endif

		.tr-collapse td,
		.tr-collapse th {
			border-spacing: 0;
			border-top: 1px solid black;
			border-right: 1px solid black;
			vertical-align: middle;
/*			border-bottom: 1px solid black;*/
		}

		.newrow {
			border-top: 1px solid black;
		}

		.tr-collapse td:last-of-type {
		    border-right: none;
		}

		.no-leftborder{
			border-left: 0;
		}

		@media print {
		  .hidePrint {
		    display: none;
		  }
		}

	</style>
</head>
<body>

	<!-- <table style="width:100%;padding-top: 65px;">
		<tr>
			<td class="fs-hdr fw-bold text-center">LAPORAN SURVEYOR EKSPOR ( LSE )<br> <i>SURVEYOR’S REPORT</i></td>
		</tr>
	</table> -->
	{{-- <div style="padding-top: 200px;"></div> --}}
	<table style="width:100%;border: 1px solid black;border-spacing: 0; margin-bottom: 100px;">
		<tr>
			<td colspan="9" class="fs-hdr text-center" style="border-bottom: 1px solid black;"><u><b>LAPORAN SURVEYOR EKSPOR ( LSE )</b></u><br> <i>SURVEYOR'S EXPORT REPORT</i></td>
		</tr>
		<tr>
			<td colspan="9" class="fw-bold text-center"><u>PERATURAN MENTERI PERDAGANGAN RI NO. 11 Tahun 2024, 30 MEI 2024</u></td>
		</tr>
		<tr>
			<td colspan="9" class="text-center"><i>REGULATION OF TRADE MINISTRY NO. 11 Tahun 2024 DATED MEI 30<sup>TH</sup> , 2024</i></td>
		</tr>

		{{-- ROW A --}}
		<!-- <tr><td colspan="9">&nbsp;</td></tr> -->
		<tr class="tr-collapse">
			<td class="fw-bold" style="width:20px;padding-left: 5px;border-right: 0;">A.</td>
			<td colspan="8" class="fw-bold">KANTOR PENERBIT / <i>ISSUING OFFICE</i> : {{ $datals->namaCabang }}</td>
			<!-- <td colspan="6">:<span style="margin-left: 5px;">{{ $datals->namaCabang }}</span></td> -->
		</tr>
		<tr class="tr-collapse">
			<td colspan="4" style="padding-left:5px">NO. LSE <span style="margin-left:25px;margin-right:10px;">: {{ $datals->noLs }}</span></td>
			<td style="border-right: 0px;padding-left:5px;width: 120px;"><span><u>TGL.DIKELUARKAN</u><br> <i> DATE OF ISSUED</i></span></td>
			<td colspan="2">: <span style="padding-left:10px">{{ formatDate($datals->tglLs) }}</span></td>
			<td style="border-right: 0px;padding-left:5px;width: 100px;"><u>TGL.HABIS PAKAI</u><br> <i> DATE OF EXPIRES</i></td>
			<td colspan="1">: <span style="padding-left:10px">{{ formatDate($datals->tglAkhirLs) }}</span></td>
		</tr>

		{{-- ROW B --}}
		<tr class="tr-collapse fw-bold">
			<td style="width:20px;padding-left: 5px;border-right: 0;">B.</td>
			<td colspan="8">PERNYATAAN EKSPORTIR / <i>EXPORTER’S STATEMENT</i></td>
		</tr>
		<tr class="tr-collapse">
			<td colspan="9" style="padding:0;margin:0;border-spacing:0;border:0;width: 100%;">
				<table style="padding:0;margin:0;border-spacing:0;border-top:1px solid black;width: 100%;">
					<tr>
						<td style="border-spacing:0;width: 50%;border-right: 1px solid black;border-top: 0; padding-left: 5px;padding-top: 5px;">
							<table style="border-spacing:0;border:0;width: 100%;">
								<tr> <td style="border-top: 0;"><u>EKSPORTIR (NPWP, NAMA, ALAMAT)</u><br><i>EXPORTER (NPWP, NAME, ADDRESS)</i></td> </tr>
								<tr> <td style="border-top: 0;">{{ formatNpwp($datals->npwp16 ?: $datals->npwp) }}</td> </tr>
								<tr> <td style="border-top: 0;"><b>{{ $datals->bentukPersh.'. '.$datals->namaPersh }}</b></td> </tr>
								<tr> <td style="border-top: 0;">{{ $datals->alamatPersh }}</td> </tr>
							</table>
						</td>
						<td style="border: 0;padding:0;margin:0; padding-top: 5px;vertical-align: top;">
							<table style="border:0;border-spacing:0;padding:0;margin:0;width: 100%;">
								<tr>
									<td style="padding-left: 5px;border: 0;vertical-align: top;width: 125px;">NO. WO (PVEB)</td>
									<td style="border: 0;vertical-align: top;width: 5px;">:</td>
									<td style="border: 0;vertical-align: top;width: 150px;">{{ $datals->noPveb }}</td>
									<td style="border: 0;vertical-align: top;">TGL.<br><i>DATE</i></td>
									<td style="border: 0;vertical-align: top;width: 5px;">:</td>
									<td style="border: 0;vertical-align: top;min-width: 75px;">{{ formatDate($datals->tglPveb) }}</td>
								</tr>
								<tr>
									<td style="padding-left: 5px;border:0;border-top: 1px solid black;vertical-align: top;"><u>TEMPAT PEMERIKSAAN</u><br><i>SURVEY LOCATION</i></td>
									<td style="border: 0;border-top: 1px solid black;vertical-align: top;width: 5px;">:</td>
									<td style="border: 0;border-top: 1px solid black;vertical-align: top;">{{ ( empty($datals->lokasiPeriksaPrint) ) ? $datals->lokasiPeriksa:$datals->lokasiPeriksaPrint }}</td>
									<td style="border: 0;border-top: 1px solid black;vertical-align: top;">TGL.<br><i>DATE</i></td>
									<td style="border: 0;border-top: 1px solid black;vertical-align: top;width: 5px;">:</td>
									<td style="border: 0;border-top: 1px solid black;vertical-align: top;min-width: 75px;">{{ formatDate($datals->tglPeriksa) }}</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td style="border-spacing:0;width: 50%;border-right: 1px solid black;border-top: 1px solid black; padding-left: 5px;padding-top: 5px; vertical-align: top;">
							<table style="border-spacing:0;border:0;width: 100%;">
								<tr>
									<td colspan="4" style="border-top: 0;"><u>ET-BATUBARA & PRODUK BATUBARA</u><br><i>REGISTERED EXPORTER</i></td>
								</tr>
								<tr>
									<td style="border:0;width: 15px;">NO.<</td>
									<td style="border-top:0;border-right:0;padding-left: 5px;width: 100px;">: {{ $datals->noET }}</td>
									<td style="border:0;padding-left: 5px;width: 30px;">TGL.<br>DATE</td>
									<td style="border:0;padding-left: 5px;">: {{ formatDate($datals->tglET) }}</td>
								</tr>
							</table>
						</td>
						<td style="border: 0;padding:0;margin:0; padding-top: 5px;vertical-align: top;border-top: 1px solid black;">
							<table style="border:0;border-spacing:0;padding:0;margin:0;width: 100%;">
								<tr>
									<td style="padding-left: 5px;border: 0;vertical-align: top;width: 125px;">NO. PACKING LIST</td>
									<td style="border: 0;vertical-align: top;width: 5px;">:</td>
									<td style="border: 0;vertical-align: top;width: 150px;">{{ $datals->noPackingList }}</td>
									<td style="border: 0;vertical-align: top;">TGL.<br><i>DATE</i></td>
									<td style="border: 0;vertical-align: top;width: 5px;">:</td>
									<td style="border: 0;vertical-align: top;min-width: 75px;">{{ formatDate($datals->tglPackingList) }}</td>
								</tr>
								<tr>
									<td style="padding-left: 5px;border:0;border-top: 1px solid black;vertical-align: top;">NO. INVOICE</td>
									<td style="border: 0;border-top: 1px solid black;vertical-align: top;width: 5px;">:</td>
									<td style="border: 0;border-top: 1px solid black;vertical-align: top;">{{ $datals->noInvoice }}</td>
									<td style="border: 0;border-top: 1px solid black;vertical-align: top;">TGL.<br><i>DATE</i></td>
									<td style="border: 0;border-top: 1px solid black;vertical-align: top;width: 5px;">:</td>
									<td style="border: 0;border-top: 1px solid black;vertical-align: top;min-width: 75px;">{{ formatDate($datals->tglInvoice) }}</td>
								</tr>
								<tr>
									<td colspan="6" style="padding-left: 5px;border:0;border-top: 1px solid black;vertical-align: top;">
										<span>NILAI EKSPOR ({{ $datals->kodeIncoterm }}) / <i>EXPORT VALUE (USD)</i></span>
										<span style="margin-left:15px">:</span>
										<span style="margin-left:15px">{{ formatAngka($datals->nilaiInvoice) }}</span>
									</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td style="border-spacing:0;width: 50%;border-right: 1px solid black;border-top: 1px solid black; padding-left: 5px;padding-top: 5px; vertical-align: top;">
							<table style="border-spacing:0;border:0;width: 100%;">
								<tr>
									<td style="border-top: 0;"><u>IMPORTIR (NAMA DAN ALAMAT)</u><br><i>IMPORTER (NAME AND ADDRESS)</i></td>
								</tr>
								<tr>
									<td  style="border:0;"><b>{{ $datals->namaImportir }}</b></td>
								</tr>
								<tr>
									<td  style="border:0;">{{ $datals->alamatImportir }}</td>
								</tr>
							</table>
						</td>
						<td style="border: 0;padding:0;margin:0; padding-top: 5px;vertical-align: top;border-top: 1px solid black;">
							<table style="border:0;border-spacing:0;padding:0;margin:0;width: 100%;">
								<tr>
									<td rowspan="2" style="padding-left: 5px;border: 0;vertical-align: top;width: 220px;"><u>IUPOP/PKP2B/IUPKOP/IPR</u><br><i>MINING LICENSE</i></td>
									<td style="border:0;width: 20px;padding-left:20px;vertical-align:top;">NO.</td>
									@if (!in_array($datals->idJnsIUP, [4,8]))
										<td style="border:0;padding-left:5px;vertical-align:top;">: {{ $datals->noIUP }}</td>
									@else
										<td style="border:0;padding-left:5px;vertical-align:top;">: -</td>
									@endif
								</tr>
								<tr>
									<td style="border-right:0;border-top:0;padding-left:20px;vertical-align:top;"><u>TGL</u><br><i>DATE</i></td>
									@if (!in_array($datals->idJnsIUP, [4,8]))
										<td style="border-top:0;padding-left:5px;vertical-align:middle;">: {{ formatDate($datals->tglIUP) }}</td>
									@else
										<td style="border-top:0;padding-left:5px;vertical-align:middle;">: -</td>
									@endif

								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td style="border-spacing:0;width: 35%;border-right: 1px solid black;border-top: 1px solid black; padding-left: 5px;padding-top: 5px; vertical-align: top;">
							<table style="border-spacing:0;border:0;width: 100%;">
								<tr>
									<td style="border:0;width: 100px;"><u>PELABUHAN MUAT</u><br><i>LOADING PORT</i></td>
									<td style="border:0;width: 5px;">:</td>
									<td style="border:0;">{{ ( empty($datals->portMuatPrint) ) ? $datals->portMuat : $datals->portMuatPrint }}</i></td>
								</tr>
							</table>
						</td>
						<td style="border: 0;padding:0;margin:0; padding-top: 5px;vertical-align: top;border-top: 1px solid black;">
							<table style="border:0;border-spacing:0;padding:0;margin:0;width: 100%;">
								<tr>
									<td rowspan="2" style="padding-left: 5px;border: 0;vertical-align: top;width: 220px;"><u>IUPOPK PENGANGKUTAN DAN PENJUALAN</u><br><i>TRANSPORTING AND SELLING LICENCE</i></td>
									<td style="border:0;width: 20px;padding-left:20px;vertical-align:top;">NO.</td>
									@if (in_array($datals->idJnsIUP, [4]))
										<td style="border:0;padding-left:5px;vertical-align:top;">: {{ $datals->noIUP }}</td>
									@else
										<td style="border:0;padding-left:5px;vertical-align:top;">: -</td>
									@endif

								</tr>
								<tr>
									<td style="border-right:0;border-top:0;padding-left:20px;vertical-align:top;"><u>TGL</u><br><i>DATE</i></td>
									@if (in_array($datals->idJnsIUP, [4]))
										<td style="border-top:0;padding-left:5px;vertical-align:middle;">: {{ formatDate($datals->tglIUP) }}</td>
									@else
										<td style="border-top:0;padding-left:5px;vertical-align:middle;">: -</td>
									@endif

								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td style="border-spacing:0;width: 35%;border-right: 1px solid black;border-top: 1px solid black; padding-left: 5px;padding-top: 5px; vertical-align: top;">
							<table style="border-spacing:0;border:0;width: 100%;">
								<tr>
									<td style="border:0;width: 100px;"><u>TANGGAL MUAT</u><br><i>DATE OF LOADING</i></td>
									<td style="border:0;width: 5px;">:</td>
									<td style="border:0;">{{ formatDate($datals->tglMuat) }}</i></td>
								</tr>
							</table>
						</td>
						<td style="border: 0;padding:0;margin:0; padding-top: 5px;vertical-align: top;border-top: 1px solid black;">
							<table style="border:0;border-spacing:0;padding:0;margin:0;width: 100%;">
								<tr>
									<td rowspan="2" style="padding-left: 5px;border: 0;vertical-align: top;width: 220px;"><u>IUPOPK PENGOLAHAN & PEMURNIAN / IUI</u><br><i>SMELTING LICENCE</i></td>
									<td style="border:0;width: 20px;padding-left:20px;vertical-align:top;">NO.</td>
									@if (in_array($datals->idJnsIUP, [8]))
										<td style="border:0;padding-left:5px;vertical-align:top;">: {{ $datals->noIUP }}</td>
									@else
										<td style="border:0;padding-left:5px;vertical-align:top;">: -</td>
									@endif
								</tr>
								<tr>
									<td style="border-right:0;border-top:0;padding-left:20px;vertical-align:top;"><u>TGL</u><br><i>DATE</i></td>
									@if (in_array($datals->idJnsIUP, [8]))
										<td style="border-top:0;padding-left:5px;vertical-align:middle;">: {{ formatDate($datals->tglIUP) }}</td>
									@else
										<td style="border-top:0;padding-left:5px;vertical-align:middle;">: -</td>
									@endif
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td style="border-spacing:0;width: 35%;border-right: 1px solid black;border-top: 1px solid black; padding-left: 5px;padding-top: 5px; vertical-align: top;">
							<table style="border-spacing:0;border:0;width: 100%;">
								<tr>
									<td style="border:0;width: 100px;"><u>TANGGAL SELESAI MUAT</u><br><i>DATE OF FINISH LOADING</i></td>
									<td style="border:0;width: 5px;">:</td>
									<td style="border:0;">{{ formatDate($datals->tglMuatAkhir) }}</i></td>
								</tr>
							</table>
						</td>
						<td style="border: 0;padding:0;margin:0; padding-top: 5px;vertical-align: top;border-top: 1px solid black;">
							<table style="border:0;border-spacing:0;padding:0;margin:0;width: 100%;">
								<tr>
									<td rowspan="2" style="padding-left: 5px;border: 0;vertical-align: top;width: 220px;"><u>BUKTI PEMBAYARAN ROYALTI</u><br><i>ROYALTY PAYMENT</i></td>
									<td style="border:0;width: 20px;padding-left:20px;vertical-align:top;">NO.</td>
									<td style="border: 0;width: 3px;vertical-align: top;">:</td>
									<td style="border:0;padding-left:5px;vertical-align:top;">{{ implode(", ", $noNtpn) }}</td>

								</tr>
								<tr>
									<td style="border-right:0;border-top:0;padding-left:20px;vertical-align:top;"><u>TGL</u><br><i>DATE</i></td>
									<td style="border: 0;width: 3px;vertical-align: top;">:</td>
									<td style="border-top:0;padding-left:5px;vertical-align:middle;">{{ implode(", ", $tglNtpn) }}</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td style="border-spacing:0;width: 35%;border-right: 1px solid black;border-top: 1px solid black; padding-left: 5px;padding-top: 5px; vertical-align: top;">
							<table style="border-spacing:0;border:0;width: 100%;">
								<tr>
									<td style="border:0;width: 100px;"><u>NEGARA DAN NAMA PELABUHAN TUJUAN</u><br><i>COUNTRY AND PORT DESTINATION</i></td>
									<td style="border:0;width: 5px;">:</td>
									<td style="border:0;">{{ ( empty($datals->portTujuanPrint) ) ? $datals->portTujuan.', '.$datals->negaraTujuan : $datals->portTujuanPrint }}</i></td>
								</tr>
							</table>
						</td>
						<td style="border: 0;padding:0;margin:0; vertical-align: top;border-top: 1px solid black;">
							<table style="border:0;border-spacing:0;padding:0;margin:0;width: 100%;">
								{{-- <td style="width: 60%;"> --}}
								<td style="width: 60%;border: 0;padding:0;margin:0; padding-top: 5px;vertical-align: top;border-right: 1px solid black;">
									<table style="border:0;border-spacing:0;padding:0;margin:0;width: 100%;">
										<tr>
											<td style="padding-left: 5px;border: 0;vertical-align: top;"><u>NAMA KAPAL</u></td>
											<td style="border: 0;width: 3px;vertical-align: top;">:</td>
											<td style="border:0;padding-left:5px;vertical-align:top;">{{ $datals->namaTransport }}</td>
										</tr>
										<tr>
											<td rowspan="2" style="padding-left: 5px;border: 0;vertical-align: top;"><u>KAPASITAS KAPAL</u><br><i>VESSEL / BARGE CAP</i></td>
											<td style="border: 0;width: 3px;vertical-align: top;">:</td>
											<td style="border:0;padding-left:5px;vertical-align:top;">{{ $datals->kapasitasKapal }}</td>
										</tr>
									</table>
								</td>
								<td style="width: 40%;border: 0;padding:0;margin:0; padding-top: 5px;vertical-align: top;">
									<table style="border:0;border-spacing:0;padding:0;margin:0;width: 100%;">
										<tr>
											<td rowspan="2" style="padding-left: 5px;border: 0;vertical-align: top;width: 50px;"><u>JENIS L/C</u><br><i>(TYPES OF L/C)</i></td>
											<td style="border: 0;width: 3px;vertical-align: top;">:</td>
											<td style="border:0;padding-left:5px;vertical-align:top;">{{ $datals->jenisLc }}</td>
										</tr>
									</table>
								</td>
							</table>
						</td>
					</tr>

					<tr>
						<td style="border-spacing:0;width: 35%;border-right: 1px solid black;border-top: 1px solid black; padding-left: 5px;padding-top: 5px; vertical-align: top;">
							<table style="border-spacing:0;border:0;width: 100%; vertical-align: top;">
								<tr>
									<td style="border:0;width: 100px;vertical-align: top"><u>NAMA PERS. ASURANSI</u><br><i>INSURANCE</i></td>
									<td style="border:0;width: 5px;vertical-align: top">:</td>
									<td style="border:0;vertical-align: top">{{ ( empty($datals->namaAsuransiKargo) ) ? $datals->namaAsuransiKapal:$datals->namaAsuransiKargo }}</i></td>
								</tr>
								<tr>
									<td style="border:0;width: 100px;vertical-align: top"><u>NOMOR POLIS</u><br><i>(NUMBER OF POLICY)</i></td>
									<td style="border:0;width: 5px;vertical-align: top">:</td>
									<td style="border:0;vertical-align: top">{{ $datals->noPolis }}</i></td>
								</tr>
							</table>
						</td>
						<td style="border: 0;padding:0;margin:0; vertical-align: top;border-top: 1px solid black;">
							<table style="border:0;border-spacing:0;padding:0;margin:0;width: 100%;">
								<td style="width: 60%;border: 0;padding:0;margin:0; padding-top: 5px;vertical-align: top;border-right: 1px solid black;">
									<table style="border:0;border-spacing:0;padding:0;margin:0;width: 100%;">
										<tr>
											<td style="padding-left: 5px;border: 0;vertical-align: top;width: 70px;"><u>JENIS KAPAL</u><br><i>TYPE</i></td>
											<td style="border: 0;width: 3px;vertical-align: top;">:</td>
											<td style="border:0;padding-left:5px;vertical-align:top;">{{ $datals->cargoType }}</td>
										</tr>
										<tr>
											<td style="padding-left: 5px;border: 0;vertical-align: top;"><u>BENDERA</u><br><i>FLAG</i></td>
											<td style="border: 0;width: 3px;vertical-align: top;">:</td>
											<td style="border:0;padding-left:5px;vertical-align:top;">{{ $datals->benderaKapal }}</td>
										</tr>
									</table>
								</td>
								<td style="width: 40%;border: 0;padding:0;margin:0; vertical-align: top;">
									<table style="border:0;border-spacing:0;padding:0;margin:0;width: 100%;">
										<tr>
											<td style="padding-left: 5px;border: 0;vertical-align: top;width: 70px;"><u>NOMOR L/C</u><br><i>(NUMBER OF L/C)</i></td>
											<td style="border: 0;width: 3px;vertical-align: top;">:</td>
											<td style="border:0;padding-left:5px;vertical-align:top;">{{ $datals->noLc }}</td>
										</tr>
										<tr>
											<td style="padding-left: 5px;border: 0;vertical-align: top;"><u>TANGGAL L/C</u><br><i>(DATE OF L/C)</i></td>
											<td style="border: 0;width: 3px;vertical-align: top;">:</td>
											<td style="border:0;padding-left:5px;vertical-align:top;">{{ reverseDate($datals->tglLc) }}</td>
										</tr>
									</table>
								</td>
							</table>
						</td>
					</tr>

				</table>
			</td>
		</tr>

		{{-- ROW C --}}
		<tr class="tr-collapse">
			<td class="fw-bold" style="width:20px;padding-left: 5px;border-right: 0;border-bottom: 1px solid black;">C.</td>
			<td colspan="9" class="fw-bold" style="border-bottom: 1px solid black;"><u>HASIL SURVEY</u><br><i>SURVEY RESULT</i></td>
		</tr>
		<tr class="tr-collapse">
			<td colspan="9" style="padding:0;margin:0;border-spacing:0;border:0;width: 100%;">
				<table style="padding:0;margin:0;border-spacing:0;border-top:1px solid black;width: 100%;">
					<tr>
						{{-- Kolom kiri --}}
						<td style="width:50%;border: 0; border-top: 0; padding: 0;border-right: 1px solid black;">
							<table style="border-spacing:0;border:0;width: 100%;">
								<tr>
									<td style="border-right: 0;border-top: 0; padding-left: 5px;padding-top: 5px;">ALAT ANGKUT (MODE OF TRANSPORT) <span style="margin-left:5px;margin-right:5px;">:</span> {{ $datals->modaTransport }}</td>
								</tr>
							</table>
						</td>
						{{-- Kolom kanan --}}
						<td style="width:50%;border: 0;border-top: 0; padding: 0;">
							<table style="border-spacing:0;border:0;width: 100%;">
								<tr>
									<td style="border-right: 0;border-top: 0;border-right: 0; padding-left: 5px;padding-top: 5px;">TIPE PEMUATAN (CARGO TYPE) <span style="margin-left:5px;margin-right:5px;">:</span> {{ $datals->tipeMuat }}</td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td colspan="2" style="width:100%;border: 0; border-top: 0; border-right: 0;padding: 0;">
							<table style="border-spacing:0;border:0;width: 100%;">
								<tr>
									<td style="text-align: center; width: 35px; white-space: nowrap; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-top: 5px;text-align: center;">NO</td>
									<td style="text-align: center; width: 75px; white-space:nowrap; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 5px;">HS</td>
									<td style="text-align: center; width: 234.5px; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 5px;"><u>URAIAN BARANG</u><br><i>DESCRIPTION</i></td>
									<td style="text-align: center; width: 70px; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 5px;"><u>JUMLAH</u><br><i>QUANTITY</i></td>
									<td style="text-align: center; width: 70px; white-space:nowrap; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 5px;">(USD) PRICE</td>
									<td style="text-align: center; border-right: 0;border-top: 1px solid black; border-right: 0;padding-left: 5px;padding-top: 5px;"><u>NO.IUP/PKP2B/IPR ASAL BARANG</u><br><i>MINING LICENSE OF GOODS ORIGINS</i></td>
								</tr>
								@php
									$noPetikemas ='';
									$totalBarang = 0;
									$totalUsd = 0;
									$db = \Config\Database::connect();
									$containers = $db->table('tx_lse_container')->where('idLs',$datals->id)->get()->getResult();
									foreach ($containers as $key => $container) {
										$noPetikemas .= 'CONTAINER '.strtoupper($container->nomor). ' SEAL '.strtoupper($container->noSegel).', ';
									}
								@endphp
								@foreach ($barangs as $barang)
								$hargaUSD = 0;
								@php
									$totalBarang = $totalBarang + $barang->jumlahBarang;
									$hargaUSD = $barang->hargaBarangUsd * $barang->jumlahBarang;
									$totalUsd = $totalUsd + $barang->hargaBarangUsd;
								@endphp
									<tr>
										<td style="text-align: center; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 5px;">{{ $loop->iteration }}</td>
										<td style="text-align: center; white-space:nowrap; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 5px;">{{ formatHS($barang->postarif) }}</td>
										<td style="text-align: left; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 5px;">{{ nl2br($barang->uraianBarang) }}</td>
										<td style="text-align: center; border-right: 0;white-space: nowrap; border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 5px;">{{ formatAngka($barang->jumlahBarang).' '.$barang->kdSatuanBarang }}</td>
										<td style="text-align: center; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 5px;">{{ formatAngka($barang->hargaBarangUsd) }}</td>
										<td style="text-align: center; border-right: 0;border-top: 1px solid black; border-right: 0;padding-left: 5px;padding-top: 5px;">{!! $barang->noIup !!}</td>
									</tr>
								@endforeach
								<tr>
									<td colspan="3" style="text-align: center; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 2px;"><b>TOTAL</b></td>
									<td style="text-align: center; border-right: 0;white-space: nowrap; border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 2px;"><b>{{ formatAngka($totalBarang) }}</b></td>
									<td style="text-align: center; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 2px;"><b>{{ formatAngka($totalUsd) }}</b></td>
									<td style="text-align: center; border-right: 0;border-top: 1px solid black; border-right: 0;padding-left: 5px;padding-top: 2px;"></td>
								</tr>
							</table>
						</td>
					</tr>

					<tr>
						<td colspan="2" style="width:100%;border: 0; border-top: 0; border-right: 0;padding: 0;">
							<table style="border-spacing:0;border:0;width: 100%;">
								<tr>
									<td style="text-align: center; width: 35px; white-space: nowrap; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-top: 5px;text-align: center;">NO</td>
									<td style="text-align: center; width: 220px; white-space:nowrap; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 5px;">NAMA PERUSAHAAN ASAL BARANG</td>
									<td style="text-align: center; width: 120px; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 5px;"><u>NPWP</u><br><i>NPWP</i></td>
									<td style="text-align: center; width: 200px; border-right: 0;border-top: 1px solid black;  border-right: 1px solid black;padding-left: 5px;padding-top: 5px;"><u>PROPINSI ASAL BARANG</u><br><i>PROVINCE</i></td>
									<td style="text-align: center;border-right: 0;border-top: 1px solid black; border-right: 0;padding-left: 5px;padding-top: 5px;"><u>ROYALTI DIMUKA</u><br><i>ROYALTI DP</i></td>
								</tr>
								@php $totalRoyaltiIDR = $totalRoyaltiUSD = 0; $textRoyalti = '';@endphp
								@foreach ($asalbarangs as $royalti)
									@php
										if($royalti->currency == 'IDR')
											$totalRoyaltiIDR = $totalRoyaltiIDR + $royalti->royalti;
										else
											$totalRoyaltiUSD = $totalRoyaltiUSD + $royalti->royalti;
									@endphp
									<tr>
										<td style="text-align: center; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 5px;">{{ $loop->iteration }}</td>
										<td style="text-align: left; white-space:nowrap; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 5px;">{{ $royalti->nama }}</td>
										<td style="text-align: center; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 5px;">{{ formatNpwp($royalti->npwp) }}</td>
										<td style="text-align: center; border-right: 0;white-space: nowrap; border-top: 1px solid black;  border-right: 1px solid black;padding-left: 5px;padding-top: 5px;">{{ $royalti->namaProp }}</td>
										<td style="text-align: right; border-right: 0;border-top: 1px solid black; border-right: 0;padding-left: 5px;padding-top: 5px;">{{ formatAngka($royalti->royalti).' '.$royalti->currency }}</td>
									</tr>
								@endforeach
								@php
									if($totalRoyaltiIDR > 0)
										$textRoyalti .= formatAngka($totalRoyaltiIDR).' IDR';
									if($totalRoyaltiUSD > 0){
										if(!empty($textRoyalti))
											$textRoyalti .= '<br>';
										$textRoyalti .= formatAngka($totalRoyaltiUSD).' USD';
									}
								@endphp
								<tr>
									<td colspan="4" style="text-align: center; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 2px; border-bottom: 1px solid black;"><b>TOTAL</b></td>

									<td style="text-align: right; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;padding-top: 2px;border-bottom: 1px solid black;"><b>{!! $textRoyalti !!}</b></td>
									{{-- <td style="text-align: center; border-right: 0;border-top: 1px solid black; border-right: 0;padding-left: 5px;padding-top: 2px;"><b>{{ formatAngka($totalUsd) }}</b></td> --}}
								</tr>
							</table>
						</td>
					</tr>

					<tr class="page-break-before">
						<td colspan="2" style="width:100%;border: 0; border-top: 0; border-right: 0;padding: 0;">
							<table style="border-spacing:0;border:0;width: 100%;">
								<tr>
									<td style="vertical-align: top; text-align: center; width: 35px; white-space: nowrap; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;text-align: center;">NO</td>
									<td style="vertical-align: top; text-align: center; width: 10%; white-space:nowrap; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;"><i>Cal. (KKal/Kg-arb)</i></td>
									<td style="vertical-align: top; text-align: center; width: 12%; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;"><i>Cal. (KKal/Kg-adb)</i></td>
									<td style="vertical-align: top; text-align: center; width: 10%; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;"><i>TM (%-arb)</i></td>
									<td style="vertical-align: top; text-align: center; width: 10%; white-space:nowrap; order-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;"><i>T.ash (%-adb)</i></td>
									<td style="vertical-align: top; text-align: center; width: 10%; white-space:nowrap; order-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;"><i>T. Sulfur (%-adb)</i></td>
									<td style="vertical-align: top; text-align: center; width: 20%; white-space:nowrap; order-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;"><i>Klasifikasi batubara (arb)</i></td>
									<td style="vertical-align: top; text-align: center; border-right: 0;border-top: 1px solid black; border-right: 0;padding-left: 5px;"><i>Ket</i></td>
								</tr>
								@foreach ($kaloris as $kalori)
									<tr>
										<td style="vertical-align: top; text-align: center; width: 35px; white-space: nowrap; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;text-align: center;">{{ $loop->iteration }}</td>
										<td style="vertical-align: top; text-align: center; width: 10%; white-space:nowrap; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;">{{ $kalori->calArb }}</td>
										<td style="vertical-align: top; text-align: center; width: 12%; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;">{{ $kalori->calAdb }}</td>
										<td style="vertical-align: top; text-align: center; width: 10%; border-right: 0;border-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;">{{ $kalori->tmArb }}</td>
										<td style="vertical-align: top; text-align: center; width: 10%; white-space:nowrap; order-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;">{{ $kalori->tAsh }}</td>
										<td style="vertical-align: top; text-align: center; width: 10%; white-space:nowrap; order-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;">{{ $kalori->tSulfur }}</td>
										<td style="vertical-align: top; text-align: center; width: 20%; white-space:nowrap; order-top: 1px solid black; border-right: 1px solid black;padding-left: 5px;">{{ $kalori->klasifikasiBatubara }}</td>
										<td style="vertical-align: top; text-align: center; border-right: 0;border-top: 1px solid black; border-right: 0;padding-left: 5px;">{{ $kalori->keterangan }}</td>
									</tr>
								@endforeach
							</table>
						</td>
					</tr>


					<tr>
						<td colspan="2" style="width:100%;border: 0; border-top: 1px solid black; border-right: 0;padding: 0;padding-left: 5px;">NO. PETI KEMAS DAN SEGEL <i>(CONTAINER NUMBER AND SEAL)</i> :</td>
					</tr>
					<tr>
						<td colspan="2" style="width:100%;border: 0; padding: 0;padding-left: 5px;">{{ substr($noPetikemas, 0,strlen($noPetikemas)-2) }}</td>
					</tr>
					<tr>
						<td colspan="2" style="width:100%;border: 0; border-top: 0; border-right: 0;padding: 0;">
							<table style="border-spacing:0;border:0;width: 100%;">
								<tr>
									<td style="text-align: left; border-right: 0;border-top: 1px solid black;padding-left: 5px;padding-top: 5px;">CATATAN / NOTE <span style="margin-left:10px;margin-right:10px;">:</span> {{ $datals->catatanPeriksa }} </td>
									{{-- <td style="text-align: left; width: 2%; white-space: nowrap; border-right: 0;border-top: 1px solid black;padding-left: 5px;padding-top: 5px;">:</td>
									<td style="text-align: left; border-right: 0;border-top: 1px solid black; border-right: 0;padding-left: 5px;padding-top: 5px;">{{ $datals->catatanPeriksa }}</td> --}}
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width:100%;border: 0; border-top: 0; border-right: 0;padding: 0;">
							<table style="border-spacing:0;border:0;width: 100%;">
								<tr>
									<td style="width:50%">
										<table style="border:0">
											<tr>
												<td style="border:0"><u>KESIMPULAN PEMERIKSAAN</u><br><i>SURVEY CONCLUSION</i></td>
											</tr>
											<tr><td style="border:0">&nbsp;</td></tr>
											<tr>
												<td style="border:0"><u><b>BARANG YANG DIPERIKSA SESUAI PERATURAN <br>MENTERI PERDAGANGAN NOMOR 11 TAHUN 2024, 30 MEI 2024</b></u><br><i>GOODS VERIFICATED FOUND IN COMPLIANCE WITH THE REGULATION OF TRADE MINISTRY NO. 11 Tahun 2024, MEI 30<sup>TH</sup>, 2024</i></td>
											</tr>
										</table>
									</td>
									<td style="width:50%;vertical-align: top;">
										<table style="width: 100%; border:0">
											<tr>
												<td style="border:0;text-align: center;"><b>PT. Asiatrust Technovima Qualiti</b></td>
											</tr>
											@if(!empty($qrcode))
												<tr>
													<td style="border:0;text-align:right;"><img src="{{ $qrcode}}" alt="Qr Code" width="90" style="vertical-align:bottom"></td>
												</tr>
											@else
												<tr><td style="border:0">&nbsp;</td></tr>
												<tr><td style="border:0">&nbsp;</td></tr>
												<tr><td style="border:0">&nbsp;</td></tr>
											@endif

											<tr><td style="border:0;text-align: center;">{{ $datals->namaTtd }}</td></tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="width:100%;border: 0; border-top: 1px solid black; border-right: 0;padding: 0;font-size: 8px; ">Laporan ini diterbitkan untuk memenuhi ketentuan ekspor Produk Pertambangan hasil Pengolahan dan Pemurnian. Isi laporan ini merupakan hasil pemeriksaan terhadap Produk Pertambangan yang akan diekspor. Laporan Surveyor ini tidak membebaskan eksportir dari kewajiban dan tanggung jawab hukum yang tercantum dalam kontrak jual beli</td>
					</tr>
					<tr>
						<td colspan="2" style="width:100%;border: 0; border-top: 1px solid black; border-right: 0;padding: 0;font-size: 8px; ">This report is made to fulfill the export requirements for export of Mining Product Processing Refinery Result. This report contains the result of survey on Mining Product for export. This report does not release the exporter from his/her obligations and responsibilities stated in the sales-purchase contract.</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>