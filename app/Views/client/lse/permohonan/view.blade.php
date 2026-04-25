<div class="app-content main-content mt-0">
    <div class="side-app">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">{{ $page_title }}</h1>
                </div>
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">Pengajuan Online</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lihat Pengajuan</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <div class="row" id="user-profile">
               <div class="col-lg-12">
                  <div class="card">
                     <div class="border-top">
                        <div class="wideget-user-tab">
                           <div class="tab-menu-heading">
                              <div class="tabs-menu1">
                                 <ul class="nav">
                                    <li><a href="#datals" class="show active" data-bs-toggle="tab">PENGAJUAN</a></li>
                                    <li><a href="#referensi" data-bs-toggle="tab" class="">DOK REFERENSI</a></li>
                                    <li><a href="#komoditas" data-bs-toggle="tab" class="">KOMODITAS</a></li>
                                 </ul>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="tab-content">
                     <div class="tab-pane show active" id="datals">
                        <div class="card">
                           <div class="card-body">
                              <form class="form-horizontal">
                                 <h3 class="card-title">DATA PENGAJUAN</h3>
                                 <hr>
                                 <div class="row">
                                    <div class="col-md-6">
                                       <input type="hidden" id="idPermohonan" name="idPermohonan" value="{{ encrypt_id($pengajuan->id) }}">
                                       <div class="row">
                                          <label class="col-md-4 form-label">No & Tgl Aju</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->nomorAju.' / '.reverseDate($pengajuan->tanggalAju) }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">No & Tgl Permohonan</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->nomorPermohonan.' / '.reverseDate($pengajuan->tglPermohonan) }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Waktu Verifikasi</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->nomorAju.' / '.reverseDate($pengajuan->tanggalAju) }}</label>
                                       </div>

                                    </div>

                                    <div class="col-md-6">

                                       <div class="row">
                                          <label class="col-md-4 form-label">Jenis Pengajuan</label>
                                          <label class="col-md-7 form-label">{{ $pengajuan->uraiJenisPengajuan }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Perihal</label>
                                          <label class="col-md-7 form-label">{{ $pengajuan->perihal }}</label>
                                       </div>

                                    </div>
                                 </div>

                                 <hr>
                                 <h3 class="card-title mt-5">EKSPORTIR</h3>
                                 <hr>
                                 <div class="row">
                                    <div class="col-md-6">
                                       <div class="row">
                                          <label class="col-md-4 form-label">Nama Eksportir</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->namaPerusahaan }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Nomor Identitas</label>
                                          <label class="col-md-8 form-label">{{ formatNPWP($pengajuan->nomorIdentitas) }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Jenis IUP</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->uraiJenisIup }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Nomor IUP</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->nomorIup }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Nomor ET Mineral</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->nomorEt }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Alamat</label>
                                          <label class="col-md-8 form-label">{{ nl2br($pengajuan->alamatPerusahaan) }}</label>
                                       </div>
                                    </div>

                                    <div class="col-md-6">
                                       <div class="row">
                                          <label class="col-md-4 form-label">Telp</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->teleponPerusahaan }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Fax</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->faxPerusahaan }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Email</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->emailPerusahaan }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Nama CP</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->namaCp }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Jabatan CP</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->jabatanCp }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Telp CP</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->teleponCp }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Email CP</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->emailCp }}</label>
                                       </div>
                                    </div>
                                 </div>
                                 <hr>
                                 <h3 class="card-title mt-5">IMPORTIR</h3>
                                 <hr>
                                 <div class="row">
                                    <div class="col-md-6">
                                       <div class="row">
                                          <label class="col-md-4 form-label">Nama Importir</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->namaImportir }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Alamat</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->alamatImportir }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Negara</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->uraiNegaraImportir }}</label>
                                       </div>

                                    </div>

                                    <div class="col-md-6">
                                       <div class="row">
                                          <label class="col-md-4 form-label">Telp</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->teleponImportir }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Fax</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->faxImportir }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Email</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->emailImportir }}</label>
                                       </div>
                                    </div>
                                 </div>

                                 <hr>
                                 <h3 class="card-title mt-5">PENGAPALAN</h3>
                                 <hr>
                                 <div class="row">
                                    <div class="col-md-6">
                                       <div class="row">
                                          <label class="col-md-4 form-label">Cara Kirim</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->namaCaraPengirim }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Nama Alat Pengiriman</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->namaAlatPengirim }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Ukuran Tongkang</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->ukuranTongkang }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Lokasi Muat Vessel</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->lokasiMuatVessel }}</label>
                                       </div>
                                    </div>

                                    <div class="col-md-6">
                                       <div class="row">
                                          <label class="col-md-4 form-label">Incoterm</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->uraiPenjualan }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Tanggal Muat Awal</label>
                                          <label class="col-md-8 form-label">{{ reverseDate($pengajuan->tanggalPemuatanAwal) }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Tanggal Muat Akhir</label>
                                          <label class="col-md-8 form-label">{{ reverseDate($pengajuan->tanggalPemuatanAkhir) }}</label>
                                       </div>
                                    </div>
                                 </div>

                                 <hr>
                                 <h3 class="card-title mt-5">PELABUHAN</h3>
                                 <hr>
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


                                 <hr>
                                 <h3 class="card-title mt-5">ASURANSI</h3>
                                 <hr>
                                 <div class="row">
                                    <div class="col-md-6">
                                       <div class="row">
                                          <label class="col-md-4 form-label">Perusahaan Asuransi (Kapal)</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->nmPerusahaanAsuransiKapal }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Polis Asuransi (Kapal)</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->noPolisSertifikatAsuransiKapal }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Sertifikat Asuransi (Kapal)</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->noSertifikatAsuransiKapal }}</label>
                                       </div>
                                    </div>

                                    <div class="col-md-6">
                                       <div class="row">
                                          <label class="col-md-4 form-label">Perusahaan Asuransi (Kargo)</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->nmPerusahaanAsuransiCargo }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Polis Asuransi (Kargo)</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->noPolisSertifikatAsuransiCargo }}</label>
                                       </div>
                                       <div class="row">
                                          <label class="col-md-4 form-label">Sertifikat Asuransi (Kargo)</label>
                                          <label class="col-md-8 form-label">{{ $pengajuan->noSertifikatAsuransiCargo }}</label>
                                       </div>
                                    </div>
                                 </div>
                              </form>
                           </div>
                        </div>
                     </div>

                     <div class="tab-pane" id="referensi">
                        <div class="card">
                           <div class="card-body border-0">
                              <div class="row">
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

                     <div class="tab-pane" id="komoditas">
                        <div class="card">
                           <div class="card-body border-0">
                              <div class="row">
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
                                             <td class="align-top">{!! formatHS($hs->kodeHs.'<br>'.nl2br($hs->uraianBarang)) !!}</td>
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
                     </div>
                  </div>
               </div>
               <!-- COL-END -->
            </div>

            <div class="row pb-4">
               <div class="btn-list text-center">
                   <input type="hidden" id="idCabang" name="idCabang" readonly>
                   <button type="button" class="btn d-w-md btn-dark" onclick="GoBack();return false;"><i class="fa fa-rotate-left me-2" aria-hidden="true"></i>Kembali</button>
               </div>
           </div>

        </div>

    </div>
    <!-- CONTAINER CLOSED -->
</div>
