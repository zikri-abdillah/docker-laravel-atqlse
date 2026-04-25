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
                <a href="#tabContainer" data-bs-toggle="tab" id="buttonContainer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-package" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /><path d="M16 5.25l-8 4.5" /></svg>
                    Package & Container
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
                                             <label class="col-md-4 form-label">Jenis Penerbitan</label>
                                             <div class="form-group col-md-8">
                                                   {{ $datals->jenisTerbit }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Nomor  SI</label>
                                             <div class="col-md-8">
                                                   {{ $datals->noSi }}                                            
                                                </div> 
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Tanggal  SI</label> 
                                             <div class="col-md-8">
                                                   {{ $datals->tglSi }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Nomor WO/PVEB</label>
                                             <div class="col-md-8">
                                                   {{ $datals->noPveb }}
                                             </div> 
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Tanggal WO/PVEB</label> 
                                             <div class="col-md-8">
                                                   {{ $datals->tglPveb }}
                                             </div>
                                          </div>
                                       </div>
                                       
                                       <div class="col">
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Cabang Penerbit</label>
                                             <div class="col-md-8">
                                                   {{ $datals->namaCabang }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Nomor LS</label>
                                             <div class="col">
                                                   {{ $datals->noLs }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Tanggal LS</label>
                                             <div class="col-md-8">
                                                   {{ reverseDate($datals->tglLs).' s.d '.reverseDate($datals->tglAkhirLs) }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Penandatangan LS</label>
                                             <div class="form-group col-md-8 mb-0">
                                                   {{ $datals->namaTtd }}
                                             </div>
                                          </div> 
                                          <div class="row mb-0">
                                             <label class="col-md-4 form-label">File LS</label>
                                             <div class="form-group col-md-8 mb-0"> 
                                                @if(!empty($datals->fileLS) && file_exists(WRITEPATH.'uploads/'.$datals->fileLS))
                                                   <span id="span-view-ls">
                                                      <button type="button" class="btn btn-sm btn-warning btn-view-file"><i class="fa fa-eye me-2" aria-hidden="true"></i>Lihat File</button>
                                                   </span> 
                                                @endif
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
                              <div class="card-body mt-4">
                                 <div class="row">
                                       <div class="col">
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Nama Eksportir</label>
                                             <div class="col-md-8">
                                                   {{ $datals->namaPersh }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">NPWP</label>
                                             <div class="col-md-8">
                                                   {{ formatNPWP($datals->npwp) }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">NIB</label>
                                             <div class="col-md-8">
                                                   {{ $datals->nib }}
                                             </div>
                                          </div> 
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">NITKU</label>
                                             <div class="col-md-8">
                                                   {{ $datals->nitku }}
                                             </div>
                                          </div>
                                       </div>
                                       <div class="col">
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Jenis IUP</label>
                                             <div class="form-group col-md-8">
                                                   {{ $datals->jenisIUP }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Nomor IUP</label>
                                             <div class="col-md-8">
                                                   {{ $datals->noIUP }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Tanggal IUP</label>
                                             <div class="col-md-8">
                                                   {{ reverseDate($datals->tglIUP) }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Alamat Eksportir</label>
                                             <div class="col-md-8">
                                                   {{ $datals->alamatPersh }}, {{ $datals->namaKota }} - {{ $datals->namaProp }}, {{ $datals->kodepos }}
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
                              <div class="card-body mt-4">
                                 <div class="row">
                                       <div class="col">
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Nama Importir</label>
                                             <div class="col-md-8">
                                                   {{ $datals->namaImportir }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Alamat Importir</label>
                                             <div class="col-md-8">
                                                   {{ $datals->alamatImportir }}
                                             </div>
                                          </div>
                                       </div>
                                       <div class="col">
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Negara</label>
                                             <div class="form-group col-md-8">
                                                   {{ $datals->negaraImportir }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Kota</label>
                                             <div class="form-group col-md-8">
                                                   {{ $datals->kotaImportir }}
                                                </div>
                                          </div>
                                       </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>

                     <div aria-multiselectable="true" class="accordion-input mb-2" id="tab_barang" role="tablist">
                        <div class="card mb-0 mt-2">
                           <div class="card-header border-bottom-0" id="head_importir" role="tab">
                              <a class="accor-style2 collapsed" aria-controls="collapse2" aria-expanded="false" data-bs-toggle="collapse" href="#barang_collapse"><i class="fe fe-chevrons-down me-2"></i>BARANG</a>
                           </div>
                           <div aria-labelledby="head_barang" class="collapse" data-bs-parent="#tab_barang" id="barang_collapse" role="tabpanel">
                              <div class="card-body mt-4">
                                 <div class="row">
                                       <div class="col">
                                          <div class="row  mb-3">
                                             <label class="col-md-4 form-label mt-0">Jumlah Netto</label>
                                             <div class="col-md-5">
                                                   {{ formatAngka($datals->qtyNetto) }} KILOGRAM
                                             </div> 
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Nomor Packing List</label>
                                             <div class="col-md-8">
                                                   {{ $datals->incoterm }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                                <label class="col-md-4 form-label">Tanggal Packing List</label>
                                                <div class="col-md-8">
                                                   {{ reverseDate($datals->tglPeriksa) }}
                                                </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label mt-0">Jumlah Bruto</label>
                                             <div class="col-md-5"> 
                                                   {{ formatAngka($datals->qtyBruto) }} (KILOGRAM)                                          
                                                </div> 
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Incoterm</label>
                                             <div class="col-md-8">
                                                   {{ $datals->incoterm }}
                                             </div>
                                          </div>
                                       </div>
                                       <div class="col">
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Nilai Invoice</label>
                                             <div class="col-md-8">
                                                   {{ formatAngka($datals->nilaiInvoice) }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                                <label class="col-md-4 form-label">Nilai Invoice (IDR)</label>
                                                <div class="col-md-8">
                                                   {{ formatAngka($datals->nilaiInvoiceIDR) }} 
                                                </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Nilai Invoice (USD)</label>
                                             <div class="col-md-8">
                                                   {{formatAngka($datals->nilaiInvoiceUSD) }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                                <label class="col-md-4 form-label">Tanggal Pemeriksaan</label>
                                                <div class="col-md-8">
                                                   {{ reverseDate($datals->tglPeriksa) }}
                                                </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Tempat Pemeriksaan</label>
                                             <div class="col-md-8">
                                                   {{ $datals->kodeLokasiPeriksa.' - '.$datals->lokasiPeriksa }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Catatan Pemeriksaan</label>
                                             <div class="col-md-8">
                                                   {{ $datals->catatanPeriksa }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Kesimpulan Pemeriksaan</label>
                                             <div class="col-md-8">
                                                   {{ $datals->kesimpulanPeriksa }}
                                             </div>
                                          </div>
                                       </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>

                     <div aria-multiselectable="true" class="accordion-input mb-2" id="tab_transport" role="tablist">
                        <div class="card mb-0 mt-2">
                           <div class="card-header border-bottom-0" id="head_transport" role="tab">
                              <a class="accor-style2 collapsed" aria-controls="collapse2" aria-expanded="false" data-bs-toggle="collapse" href="#transport_collapse"><i class="fe fe-chevrons-down me-2"></i>TRANSPORT</a>
                           </div>
                           <div aria-labelledby="head_transport" class="collapse" data-bs-parent="#tab_transport" id="transport_collapse" role="tabpanel">
                              <div class="card-body mt-4">
                                 <div class="row">
                                       <div class="col">
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Moda Transportasi</label>
                                             <div class="form-group col-md-8">
                                                   {{ $datals->modaTransport }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Tipe Kargo</label>
                                             <div class="form-group col-md-8">
                                                   {{ $datals->cargoType }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Tipe Muatan</label>
                                             <div class="form-group col-md-8">
                                                   {{ $datals->tipeMuat }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Pelabuhan Muat</label>
                                             <div class="form-group col-md-8">
                                                   {{ $datals->kodePortMuat.' - '.$datals->portMuat }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Pelabuhan Transit</label>
                                             <div class="form-group col-md-8">
                                                   {{ $datals->kodePortTransit.' - '.$datals->portTransit }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Negara Transit</label>
                                             <div class="form-group col-md-8">
                                                   {{ $datals->kodeNegaraTransit.' - '.$datals->negaraTransit }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Pelabuhan Tujuan</label>
                                             <div class="form-group col-md-8">
                                                   {{ $datals->kodePortTujuan.' - '.$datals->portTujuan }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Negara Tujuan</label>
                                             <div class="form-group col-md-8">
                                                   {{ $datals->kodeNegaraTujuan.' - '.$datals->negaraTujuan }}
                                             </div>
                                          </div>
                                       </div>
                                       <div class="col">
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Nama Transportasi</label>
                                             <div class="col-md-8">
                                                   {{ $datals->namaTransport }}                                            
                                                </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Voyage Out / Flight Number</label>
                                             <div class="col-md-8">
                                                   {{ $datals->voyage }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Bendera Kapal</label>
                                             <div class="col-md-8">
                                                   {{ $datals->kodeBenderaKapal.' - '.$datals->benderaKapal }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Tanggal Muat</label>
                                             <div class="col-md-8">
                                                   {{ reverseDate($datals->tglMuat) }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Kapasitas Kapal</label>
                                             <div class="col-md-8">
                                                   {{ reverseDate($datals->kapasitasKapal) }}
                                             </div>
                                          </div>
                                          <div class="row mb-3">
                                             <label class="col-md-4 form-label">Tanggal Berangkat</label>
                                             <div class="col-md-8">
                                                   {{ reverseDate($datals->tglBerangkat) }}
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
            
            <div class="tab-pane" id="tabContainer">
               <div class="row">
                  <div class="col-md-6"> 
                     <div class="card"> 
                        <div class="card-body">
                              <div class="row">
                                 <div class="col-md-6">
                                    <h5 class="card-title fw-semibold">Package Data</h5> 
                                 </div>
                                 <div class="col-md-6" align="right"> 
                                 </div> 
                                 
                                 <div class="mb-12 mb-2" align="right">
                                    <table class="table  table-striped table-hover w-100" id="table-package">
                                       <thead>
                                             <tr>
                                                <th class="text-center">#</th>
                                                <th>Package Info</th>
                                                <th>Jumlah</th>
                                                <th>Unit</th>
                                             </tr>
                                       </thead>
                                       <tbody>
                                             @foreach ($packages as $package)
                                                <tr>
                                                   <td>{{ $loop->iteration }}</td>
                                                   <td>{{ $package->packageInfo }}</td>
                                                   <td>{{ $package->jml }}</td>
                                                   <td>{{ $package->unit.' - '.$package->uraiUnit }}</td>
                                                </tr>
                                             @endforeach
                                       </tbody>
                                    </table>
                                 </div> 
                              </div>  
                        </div>
                     </div>
                  </div>

                  <div class="col-md-6"> 
                     <div class="card"> 
                        <div class="card-body"> 
                              <div class="row">
                                 <div class="col-md-6"> 
                                    <h5 class="card-title fw-semibold mb-4">Container Data</h5> 
                                 </div>
                                 <div class="col-md-6" align="right"> 
                                 </div> 
                                 
                                 <div class="mb-12 mb-2" align="right">
                                    <table class="table  table-striped table-hover w-100" id="table-container">
                                       <thead>
                                             <tr>
                                                <th class="text-center">#</th>
                                                <th>Kode / Jenis</th>
                                                <th>Nomor</th>
                                             </tr>
                                       </thead>
                                       <tbody>
                                             @foreach ($containers as $container)
                                                <tr>
                                                   <td>{{ $loop->iteration }}</td>
                                                   <td>{{ $container->kode.' - '.$container->keterangan }}</td>
                                                   <td class="text-nowrap">{{ $container->nomor }}</td>
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
            
            <div class="tab-pane" id="tabReferensi">
               <div class="card">
                  <div class="card-body">
                     <div class="row"> 
                        <div class="mb-12 mb-4"> 
                           <div class="table-responsive">
                              <table class="table table-striped table-hover w-100" id="tab-referensi">
                                    <thead>
                                       <tr>
                                          <th class="text-center">#</th>
                                          <th>Negara Penerbit</th>
                                          <th>Jenis Dokumen</th>
                                          <th>No Dokumen</th>
                                          <th>Tgl Dokumen</th>
                                          <th>Tgl Akhir</th>
                                       </tr>
                                    </thead>
                                    <tbody>
                                       @foreach ($references as $reference)
                                          <tr>
                                             <td>{{ $loop->iteration }}</td>
                                             <td>{{ $reference->negara }}</td>
                                             <td>{{ $reference->jenisDok }}</td>
                                             <td>{{ $reference->noDokumen }}</td>
                                             <td>{{ reverseDate($reference->tglDokumen) }}</td>
                                             <td>{{ reverseDate($reference->tglAkhirDokumen) }}</td>
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
              
            <div class="tab-pane" id="tabKomoditas">  
               <div class="table-responsive mt-3">
                  <table class="table table-striped table-hover w-100" id="tab-komoditas">
                        <thead>
                           <tr>
                              <th class="text-center">Seri</th>
                              <th>Pos Tarif / Jumlah</th>
                              <th>Uraian / Spesifikasi</th>
                              <th>NTPN / IUP</th>
                              <th>Berat / Negara / Harga</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach ($komoditas as $hs)
                              @php
                                 $ntpnModel = model('tx_hsNtpn');
                                 $ntpnModel->join('tx_lse_ntpn', 'tx_lse_ntpn.id = tx_lsehsntpn.idNtpn');
                                 $ntpnModel->where('tx_lsehsntpn.idPosTarif',$hs->id);
                                 $ntpns = $ntpnModel->findAll();
                                 $nptn = array_column($ntpns, 'noNtpn');
                                 $ntpnHS = implode('<br>', $nptn);
                              @endphp
                              <tr>
                                 <td class="align-top">{{ $loop->iteration }}</td>
                                 <td class="align-top">{!! formatHS($hs->postarif).'<br>'.formatAngka($hs->jumlahBarang).' '.$hs->uraiSatuanBarang.' ('.$hs->kdSatuanBarang.')' !!}</td>
                                 <td class="align-top">{!! 'Uraian:<br>'.$hs->uraianBarang.'<br>Spesifikasi:<br>'.$hs->sepesifikasi !!}</td>
                                 <td class="align-top">{!! $ntpnHS.'<hr>'.$hs->noIup.'<br>Tgl IUP: '.$hs->tglIup !!}</td>
                                 <td class="align-top">
                                       <span>Berat Bersih: {!! formatAngka($hs->beratBersih) !!}</span><br>
                                       <span>Negara Asal: {!! $hs->negaraAsal !!}</span><br>
                                       <span>Harga Barang: {!! formatAngka($hs->hargaBarang).' '.$hs->currencyHargaBarang !!}</span><br>
                                       <span>Harga IDR:  {!! formatAngka($hs->hargaBarangIdr) !!}</span><br>
                                       <span>Harga USD: {!! formatAngka($hs->hargaBarangUsd) !!}</span><br>
                                 </td>
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
            
            @if (session()->get('sess_role') == 10 && $datals->statusDok == 'TERBIT' && $datals->statusProses == 'ISSUED')
               <!-- <a class="btn btn-danger btn-sm my-1" href="javascript:void(0)" onclick="GoBack();return false;" role="menuitem">
                  <i class="fa fa-ban me-1"></i>Cabut
               </a> -->
               <a class="btn btn-success btn-sm my-1" href="javascript:void(0)" onclick="create_perubahan();return false;" role="menuitem">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil-plus" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                     <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
                     <path d="M13.5 6.5l4 4"></path>
                     <path d="M16 19h6"></path>
                     <path d="M19 16v6"></path>
                  </svg> Perubahan
               </a>
            @endif
            
            <input type="hidden" id="idData" name="idData" value="{{ isset($datals->id)?encrypt_id($datals->id):'' }}">
            <input type="hidden" id="i_idJenisLS" name="i_idJenisLS" value="1">
        </div> 
    </div>
</div> 