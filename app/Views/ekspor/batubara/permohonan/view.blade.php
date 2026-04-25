<div class="card">   
    <div class="card-body">
    <h5 class="card-title fw-semibold mb-3">{{ $page_title }}</h5>
        <ul class="nav tabs-menu-form">
            <li>
                <a href="#tabUmum" data-bs-toggle="tab" class="active" id="buttonUmum">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-info" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M11 14h1v4h1" /><path d="M12 11h.01" /></svg>
                    Informasi Umum
                </a>
            </li> 
            <li>
                <a href="#tabReferensi" data-bs-toggle="tab" id="buttonReferensi">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-invoice" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 7l1 0" /><path d="M9 13l6 0" /><path d="M13 17l2 0" /></svg>
                    Dokumen Referensi
                </a>
            </li>  
            <li>
                <a href="#tabKomoditas" data-bs-toggle="tab" id="buttonKomoditas">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-diamond" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M6 5h12l3 5l-8.5 9.5a.7 .7 0 0 1 -1 0l-8.5 -9.5l3 -5"></path>
                        <path d="M10 12l-2 -2.2l.6 -1"></path>
                    </svg>
                    Komoditas 
                </a>
            </li>  
        </ul> 
 
        <div class="tab-content">
            <div class="tab-pane active" id="tabUmum">  
               <div class="row"> 
                  <div aria-multiselectable="true" class="accordion-input mb-2" id="tab_main" role="tablist">
                     <div class="card mb-0 mt-2">
                        <div class="card-header border-bottom-0" id="head_main" role="tab">
                           <a class="accor-style2 collapsed" aria-controls="collapse2" aria-expanded="false" data-bs-toggle="collapse" href="#main_collapse"><i class="fe fe-chevrons-down me-2"></i>DATA LS</a>
                        </div>
                        <div aria-labelledby="head_main" class="collapse" data-bs-parent="#tab_main" id="main_collapse" role="tabpanel">
                           <div class="card-body">  
                              <div class="row">
                                 <div class="col"> 
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Nomor Pengajuan</label>
                                       <div class="col-md-8"> 
                                          :&nbsp;&nbsp;{{ $pengajuan->nomorAju }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Tanggal Pengajuan</label>
                                       <div class="col-md-8"> 
                                          :&nbsp;&nbsp;{{ reverseDate($pengajuan->tanggalAju) }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Nomor Permohonan</label>
                                       <div class="col-md-8"> 
                                          :&nbsp;&nbsp;{{ $pengajuan->nomorPermohonan }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Tanggal Permohonan</label>
                                       <div class="col-md-8"> 
                                          :&nbsp;&nbsp;{{ reverseDate($pengajuan->tglPermohonan) }}
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col"> 
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Waktu Verifikasi</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->nomorAju.' / '.reverseDate($pengajuan->tanggalAju) }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Jenis Pengajuan</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->uraiJenisPengajuan }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Perihal</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->perihal }}
                                       </div>
                                    </div>
                                 </div>
                              </div> 
                           </div>
                        </div>
                     </div>
                  </div>

                  <div aria-multiselectable="true" class="accordion-input mb-2" id="tab_eksportir" role="tablist">
                     <div class="card mb-0 mt-2">
                        <div class="card-header border-bottom-0" id="head_eksportir" role="tab">
                           <a class="accor-style2 collapsed" aria-controls="collapse2" aria-expanded="false" data-bs-toggle="collapse" href="#eksportir_collapse"><i class="fe fe-chevrons-down me-2"></i>EKSPORTIR</a>
                        </div>
                        <div aria-labelledby="head_eksportir" class="collapse" data-bs-parent="#tab_eksportir" id="eksportir_collapse" role="tabpanel">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col">
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Nama Eksportir</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->namaPerusahaan }}
                                       </div>
                                    </div> 
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">NPWP</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ formatNPWP($pengajuan->nomorIdentitas) }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Jenis IUP</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->uraiJenisIup }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Nomor IUP</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->nomorIup }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Nomor ET Batubara</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->nomorEt }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Alamat</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->alamatPerusahaan }}
                                       </div>
                                    </div>
                                 </div>

                                 <div class="col"> 
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Telp</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->teleponPerusahaan }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Fax</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->faxPerusahaan }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Email</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->emailPerusahaan }}
                                       </div>
                                    </div> 
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Nama CP</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->namaCp }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Jabatan CP</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->jabatanCp }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Telp CP</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->teleponCp }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Email CP</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->emailCp }}
                                       </div>
                                    </div>
                                 </div>
                              </div> 
                           </div>
                        </div>
                     </div>
                  </div>

                  <div aria-multiselectable="true" class="accordion-input mb-2" id="tab_importir" role="tablist">
                     <div class="card mb-0 mt-2">
                        <div class="card-header border-bottom-0" id="head_importir" role="tab">
                           <a class="accor-style2 collapsed" aria-controls="collapse2" aria-expanded="false" data-bs-toggle="collapse" href="#importir_collapse"><i class="fe fe-chevrons-down me-2"></i>IMPORTIR</a>
                        </div>
                        <div aria-labelledby="head_importir" class="collapse" data-bs-parent="#tab_importir" id="importir_collapse" role="tabpanel">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col">
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Nama Importir</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->namaImportir }}
                                       </div>
                                    </div> 
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Alamat Importir</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->alamatImportir }}
                                       </div>
                                    </div> 
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Negara</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->uraiNegaraImportir }}
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col"> 
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Nomor Telp</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->teleponImportir }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Nomor Fax</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->faxImportir }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Email</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->emailImportir }}
                                       </div>
                                    </div>
                                 </div>
                              </div> 
                           </div> 
                        </div>
                     </div>
                  </div>
  
                  <div aria-multiselectable="true" class="accordion-input mb-2" id="tab_pengapalan" role="tablist">
                     <div class="card mb-0 mt-2">
                        <div class="card-header border-bottom-0" id="head_pengapalan" role="tab">
                           <a class="accor-style2 collapsed" aria-controls="collapse2" aria-expanded="false" data-bs-toggle="collapse" href="#pengapalan_collapse"><i class="fe fe-chevrons-down me-2"></i>PENGAPALAN</a>
                        </div>
                        <div aria-labelledby="head_pengapalan" class="collapse" data-bs-parent="#tab_pengapalan" id="pengapalan_collapse" role="tabpanel">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col">
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Cara Kirim</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->namaCaraPengirim }}
                                       </div>
                                    </div> 
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Nama Alat Pengiriman</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->namaAlatPengirim }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Ukuran Tongkang</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->ukuranTongkang }}
                                       </div>
                                    </div> 
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Lokasi Muat Vessel</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->lokasiMuatVessel }}
                                       </div>
                                    </div> 
                                 </div>
                                 <div class="col"> 
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Incoterm</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->uraiPenjualan }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Tanggal Muat Awal</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ reverseDate($pengajuan->tanggalPemuatanAwal) }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Tanggal Muat Akhir</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ reverseDate($pengajuan->tanggalPemuatanAkhir) }}
                                       </div>
                                    </div> 
                                 </div>
                              </div> 
                           </div> 
                        </div>
                     </div>
                  </div>

                  <div aria-multiselectable="true" class="accordion-input mb-2" id="tab_port" role="tablist">
                     <div class="card mb-0 mt-2">
                        <div class="card-header border-bottom-0" id="head_port" role="tab">
                           <a class="accor-style2 collapsed" aria-controls="collapse2" aria-expanded="false" data-bs-toggle="collapse" href="#port_collapse"><i class="fe fe-chevrons-down me-2"></i>PENGAPALAN</a>
                        </div>
                        <div aria-labelledby="head_port" class="collapse" data-bs-parent="#tab_port" id="port_collapse" role="tabpanel">
                           <div class="card-body">
                                 <div class="row">
                                    <div class="col-md-12">
                                       <div class="table-responsive">
                                             <table class="table table-bordered table-sm">
                                                <thead>
                                                   <tr>
                                                         <th>Kegiatan</th>
                                                         <th>Pelabuhan</th>
                                                         <th>Negara</th>
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   @foreach ($pelabuhans as $pelabuhan)
                                                      <tr>
                                                         <td>{{ $pelabuhan->uraianKegiatan }}</td>
                                                         <td>{{ $pelabuhan->kodePelabuhan.' - '.$pelabuhan->namaPelabuhan }}</td>
                                                         <td>{{ $pelabuhan->namaNegara }}</td>
                                                      </tr>
                                                   @endforeach
                                                </tbody>
                                             </table>
                                       </div>
                                    </div>
                                 </div>
                              </div> 
                        </div>
                     </div>
                  </div>

                  <div aria-multiselectable="true" class="accordion-input mb-2" id="tab_asuransi" role="tablist">
                     <div class="card mb-0 mt-2">
                        <div class="card-header border-bottom-0" id="head_asuransi" role="tab">
                           <a class="accor-style2 collapsed" aria-controls="collapse2" aria-expanded="false" data-bs-toggle="collapse" href="#asuransi_collapse"><i class="fe fe-chevrons-down me-2"></i>ASURANSI</a>
                        </div>
                        <div aria-labelledby="head_asuransi" class="collapse" data-bs-parent="#tab_asuransi" id="asuransi_collapse" role="tabpanel">
                           <div class="card-body">
                              <div class="row">
                                 <div class="col">
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Perusahaan Asuransi (Kapal)</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->nmPerusahaanAsuransiKapal }}
                                       </div>
                                    </div> 
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Polis Asuransi (Kapal)</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->noPolisSertifikatAsuransiKapal }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Sertifikat Asuransi (Kapal)</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->noSertifikatAsuransiKapal }}
                                       </div>
                                    </div>  
                                 </div>
                                 <div class="col"> 
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Perusahaan Asuransi (Kargo)</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->nmPerusahaanAsuransiCargo }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Polis Asuransi (Kargo)</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->noPolisSertifikatAsuransiCargo }}
                                       </div>
                                    </div>
                                    <div class="row mb-3">
                                       <label class="col-md-4 form-label">Sertifikat Asuransi (Kargo)</label>
                                       <div class="col-md-8">
                                          :&nbsp;&nbsp;{{ $pengajuan->noSertifikatAsuransiCargo }}
                                       </div>
                                    </div> 
                                 </div>
                              </div> 
                           </div>
                        </div>
                     </div>
                  </div> 
               </div> 
            </div>
             
            <div class="tab-pane" id="tabReferensi">
               <div class="card">
                  <div class="card-body">
                     <div class="row"> 
                        <div class="table-responsive">
                           <table class="table table-bordered" id="tab-referensi">
                              <thead>
                                    <tr>
                                       <th class="text-center">Seri</th>
                                       <th>Jenis Dokumen</th>
                                       <th>Nomor Dokumen</th>
                                       <th>Tgl Dokumen</th>
                                       <th>Batch</th>
                                       <th>Tanggal Terima</th>
                                       <th>File</th>
                                    </tr>
                              </thead>
                              <tbody>
                                 @foreach ($references as $reference)
                                    <tr>
                                       <td>{{ $reference->seriDokumen }}</td>
                                       <td>{{ $reference->namaDokumen }}</td>
                                       <td>{{ $reference->nomorDokumen }}</td>
                                       <td>{{ reverseDate($reference->tanggalDokumen) }}</td>
                                       <td>{{ $reference->batch }}</td>
                                       <td>{{ reverseDateTime($reference->createdAt) }}</td>
                                       <td><a class="btn btn-sm btn-w-xs btn-icon btn-info" href="{{ $reference->urlDokumen }}" role="button" rel="no-referrer" target="_blank">Link</a></td>
                                    </tr>
                                 @endforeach
                              </tbody>
                           </table>
                        </div> 
                     </div> 
                  </div> 
               </div>  
            </div>
             
            <div class="tab-pane" id="tabKomoditas">  
               <div class="table-responsive mt-3">
                  <table class="table table-bordered" id="tab-referensi">
                     <thead>
                        <tr>
                           <th class="text-center">Seri</th>
                           <th>Ketegori Eksportir</th>
                           <th>HS / Urian</th>
                           <th>Tonase</th>
                           <th>Harga Satuan / FOB</th>
                           <th>Asal Barang</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach ($komoditi as $hs)
                           @php
                                 $kerjasamas = model('t_inswPermohonanBrgKerjasama')->where('idBarang',$hs->id)->findAll();
                           @endphp
                           <tr>
                              <td class="align-top text-center">{{ $hs->seriBarang }}</td>
                              <td class="align-top">{{ $hs->namaKategoriEksportir }}</td> 
                              <td class="align-top">{!! formatHS($hs->kodeHs).'<br>'.nl2br($hs->uraianBarang) !!}</td>
                              <td class="align-top">{{ formatAngka($hs->jumlahTonase).' '.$hs->satuan }}</td>
                              <td class="align-top">{!! 'Harga Satuan : '.formatAngka($hs->hargaSatuan).'<br>FOB : '.formatAngka($hs->fob) !!}</td>
                              <td class="align-top">{{ $hs->asalBarang }}</td>
                           </tr>
                           <tr>
                              <td colspan="6">
                                 <table class="table table-bordered" id="tab-referensi">
                                    <tr>
                                       <th>Ketegori Barang</th>
                                       <th>NTPN</th>
                                       <th>NPWP</th>
                                       <th>Tonase Pembelian</th>
                                       <th>Tonase Ekspor</th>
                                    <tr>
                                    @foreach ($kerjasamas as $kerjasama)
                                    <tr>
                                       <td>{{ $kerjasama->namaKategoriBarang }}</td>
                                       <td>{!! $kerjasama->nomorNtpn.'<br>'.$kerjasama->tanggalNtpn !!}</td>
                                       <td>{!! 'Penjual : '.$kerjasama->npwpPenjual.'<br>Penambang : '.$kerjasama->npwpPenambang.'<br>Trader : '.$kerjasama->npwpTrader !!}</td>
                                       <td>{{ formatAngka($kerjasama->tonasePembelian) }}</td>
                                       <td>{{ formatAngka($kerjasama->tonaseEkspor) }}</td>
                                    </tr>
                                    @endforeach

                                 </table>
                              </td>
                           </tr>
                        @endforeach
                     </tbody>
                  </table>
               </div>   
            </div> 
        </div>

        <div class="col-md-12 mt-2 text-center"> 
            <button type="button" class="btn btn-dark btn-sm" onclick="GoBack();return false;">
               <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-back-up-double" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                  <path d="M13 14l-4 -4l4 -4"></path>
                  <path d="M8 14l-4 -4l4 -4"></path>
                  <path d="M9 10h7a4 4 0 1 1 0 8h-1"></path>
               </svg> Kembali
            </button>
            
            @if ( session()->get('sess_role') == 6 && $pengajuan->statusInsw == '010')
               <button type="button" class="btn btn-primary btn-sm" id="btn-create-ls">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil-plus" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                     <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
                     <path d="M13.5 6.5l4 4"></path>
                     <path d="M16 19h6"></path>
                     <path d="M19 16v6"></path>
                  </svg> Buat Draft LS
               </button>  
            @endif   

            <input type="hidden" id="idPermohonan" name="idPermohonan" value="{{ encrypt_id($pengajuan->id) }}">
         </div> 
    </div>
</div> 