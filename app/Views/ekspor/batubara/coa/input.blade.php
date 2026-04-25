@php
    $coaData = $coaData ?? [];
    $coaJenisMap = [
        '1' => 'Baru',
        '2' => 'Perubahan',
        '9' => 'Pembatalan',
    ];
    $coaJenisValue = (string) ($coaData['jns_penerbitan'] ?? '1');
    $coaJenisLabel = $coaJenisMap[$coaJenisValue] ?? 'Baru';
    $coaIsEdit = !empty($coaData['idData']);
    $coaLockNomorTanggal = $coaIsEdit && $coaJenisValue === '2';
@endphp

<style>
    .tabs-menu-form.coa-main-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-bottom: 0;
        padding: 0 0 0.65rem 0;
        border-bottom: 1px solid #e4ebf3;
        background: transparent;
        overflow: visible;
    }

    .tabs-menu-form.coa-main-tabs > li {
        flex: 0 0 auto;
        float: none;
    }

    .tabs-menu-form.coa-main-tabs li a.coa-main-tab-link {
        display: flex;
        align-items: center;
        gap: 0.45rem;
        min-height: 40px;
        height: 100%;
        padding: 0.45rem 0.8rem;
        border: 1px solid #d9e2ea;
        border-radius: 8px;
        background: #fff;
        color: #31465c;
        font-size: 13px;
        font-weight: 600;
        line-height: 1.25;
        text-align: left;
        transition: all 0.2s ease;
    }

    .tabs-menu-form.coa-main-tabs li a.coa-main-tab-link:hover,
    .tabs-menu-form.coa-main-tabs li a.coa-main-tab-link:focus {
        color: #24384d;
        background: #f1f5f9;
        border-color: #d5e0ea;
    }

    .tabs-menu-form.coa-main-tabs li a.coa-main-tab-link.active {
        background: #eef4fa;
        border-color: #b9c8d8;
        color: #29425b;
        box-shadow: none;
    }

    .tabs-menu-form.coa-main-tabs li a.coa-main-tab-link svg {
        flex-shrink: 0;
        width: 16px;
        height: 16px;
    }

    .coa-main-tab-text {
        display: flex;
        flex-direction: column;
        gap: 0;
    }

    .tabs-menu-form.coa-main-tabs li a.coa-main-tab-link.active .coa-main-tab-text,
    .tabs-menu-form.coa-main-tabs li a.coa-main-tab-link.active svg {
        color: #29425b;
    }

    .coa-main-tab-text small {
        display: none;
    }

    .coa-main-tab-link::before {
        content: "";
        width: 2px;
        align-self: stretch;
        border-radius: 999px;
        background: transparent;
    }

    .tabs-menu-form.coa-main-tabs li a.coa-main-tab-link.active::before {
        background: #416589;
    }

    @media (max-width: 767.98px) {
        .tabs-menu-form.coa-main-tabs {
            gap: 0.4rem;
            padding-bottom: 0.6rem;
        }

        .tabs-menu-form.coa-main-tabs > li {
            flex: 1 1 100%;
        }

        .tabs-menu-form.coa-main-tabs li a.coa-main-tab-link {
            min-height: 38px;
            padding: 0.45rem 0.7rem;
        }
    }

    .coa-subtitle {
        color: #6c7a89;
        font-size: 10px;
    }

    .coa-actions {
        gap: 0.5rem;
    }

    .coa-stage-note {
        background: linear-gradient(135deg, #f7fbff 0%, #eef6ff 100%);
        border: 1px solid #d8e8fb;
        border-radius: 14px;
    }

    .coa-block-card {
        border: 1px solid #e7edf3;
        box-shadow: 0 0.35rem 0.9rem rgba(27, 39, 51, 0.04);
    }

    .coa-soft-panel {
        border: 1px dashed #cad5df;
        background: #fbfdff;
    }

    .coa-group-button {
        background: #fff;
        color: #22313f;
        box-shadow: none;
    }

    .coa-group-button:not(.collapsed) {
        background: #25476a;
        color: white;
        box-shadow: none;
    }

    .coa-empty {
        border: 1px dashed #cbd7e3;
        border-radius: 14px;
        padding: 1rem;
        background: #fafcff;
    }

    .coa-table td,
    .coa-table th {
        vertical-align: top;
    }

    .coa-hint {
        color: #6c7a89;
        font-size: 8px;
    }

    .coa-inline-feedback {
        display: none;
        border-radius: 10px;
        font-size: 12px;
    }
</style>


<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-3">{{ $page_title }}</h5>
        <form id="form-coa">
            <input type="hidden" id="idData" value="{{ $coaData['idData'] ?? '' }}">

            <ul class="nav tabs-menu-form coa-main-tabs" role="tablist">
                <li>
                    <a href="#coaUmumTab" data-bs-toggle="tab" class="active coa-main-tab-link" id="buttonUmum" role="tab">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-info" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            <path d="M11 14h1v4h1" />
                            <path d="M12 11h.01" />
                        </svg>
                        <span class="coa-main-tab-text">
                            <span>Informasi Umum</span>
                            <small>Header dan identitas COA</small>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#coaKomoditasTab" data-bs-toggle="tab" class="coa-main-tab-link" id="buttonKomoditas" role="tab">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-diamond" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M6 5h12l3 5l-8.5 9.5a.7 .7 0 0 1 -1 0l-8.5 -9.5l3 -5"></path>
                            <path d="M10 12l-2 -2.2l.6 -1"></path>
                        </svg>
                        <span class="coa-main-tab-text">
                            <span>Komoditas</span>
                            <small>Daftar barang dan kuantitas</small>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="#coaGroupTab" data-bs-toggle="tab" class="coa-main-tab-link" id="buttonDetailCoa" role="tab">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-table-options" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M12 21h-7a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v3.5"/>
                            <path d="M3 10h18"/>
                            <path d="M10 3v18"/>
                            <path d="M19 17l3 3l-3 3"/>
                            <path d="M16 20h6"/>
                        </svg>
                        <span class="coa-main-tab-text">
                            <span>Detail COA</span>
                            <small>Spec dan param per komoditas</small>
                        </span>
                    </a>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <div class="tab-pane active" id="coaUmumTab" role="tabpanel">
                                <div class="card coa-block-card mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <div class="fw-semibold">Informasi Header</div>
                                        <div class="coa-subtitle text-white">Silahkan lengkapi data berikut.</div>
                                    </div>
                                    <div class="card-body">
                                        <div id="ls-reference-feedback" class="alert coa-inline-feedback mb-3" role="alert"></div>
                                        <div class="row">
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Jenis Penerbitan</label>
                                                <input type="hidden" id="i_jns_penerbitan" value="{{ $coaJenisValue }}">
                                                <div class="form-control bg-light d-flex align-items-center justify-content-between">
                                                    <span>{{ $coaJenisValue }} : {{ $coaJenisLabel }}</span>
                                                    @php
                                                        $coaJenisBadgeClass = 'bg-light-secondary text-secondary';
                                                        if ($coaJenisValue === '1') {
                                                            $coaJenisBadgeClass = 'bg-light-success text-success';
                                                        } elseif ($coaJenisValue === '2') {
                                                            $coaJenisBadgeClass = 'bg-light-warning text-warning';
                                                        } elseif ($coaJenisValue === '9') {
                                                            $coaJenisBadgeClass = 'bg-light-danger text-danger';
                                                        }
                                                    @endphp
                                                    <span class="badge {{ $coaJenisBadgeClass }}">{{ $coaJenisLabel }}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Nomor COA</label>
                                                @if ($coaLockNomorTanggal)
                                                    <input type="hidden" id="i_nomor_coa" value="{{ $coaData['nomor_coa'] ?? '' }}">
                                                    <input type="text" class="form-control bg-light" value="{{ $coaData['nomor_coa'] ?? '' }}" placeholder="Nomor COA" readonly disabled>
                                                    <div class="coa-hint mt-1">Nomor COA untuk dokumen perubahan mengikuti dokumen asal.</div>
                                                @else
                                                    <input type="text" class="form-control" id="i_nomor_coa" value="{{ $coaData['nomor_coa'] ?? '' }}" placeholder="Nomor COA">
                                                @endif
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Tanggal COA</label>
                                                @if ($coaLockNomorTanggal)
                                                    <input type="hidden" id="i_tgl_coa" value="{{ $coaData['tgl_coa'] ?? '' }}">
                                                    <input type="text" class="form-control bg-light" value="{{ $coaData['tgl_coa'] ?? '' }}" placeholder="DD-MM-YYYY" readonly disabled>
                                                    <div class="coa-hint mt-1">Tanggal COA untuk dokumen perubahan mengikuti dokumen asal.</div>
                                                @else
                                                    <input type="text" class="form-control bs-datepicker" id="i_tgl_coa" value="{{ $coaData['tgl_coa'] ?? '' }}" placeholder="DD-MM-YYYY">
                                                @endif
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">File COA</label>
                                                @php
                                                    $viewFileCoa = 'd-none';
                                                    $viewUploadCoa = '';
                                                    if (!empty($coaData['path_file']) && file_exists(WRITEPATH . 'uploads/' . $coaData['path_file'])) {
                                                        $viewFileCoa = '';
                                                        $viewUploadCoa = 'd-none';
                                                    }
                                                @endphp
                                                <span id="span-view-coa" class="{{ $viewFileCoa }}">
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete-file-coa">
                                                        <i class="fadeIn animated bx bx-eraser" aria-hidden="true"></i>Hapus File
                                                    </button>
                                                    <a href="{{ !empty($coaData['url_coa']) ? base_url('doc/coa/' . $coaData['url_coa']) : 'javascript:;' }}"
                                                        target="_blank"
                                                        class="btn btn-sm btn-info btn-view-file-coa">
                                                        <i class="fadeIn animated lni lni-eye" aria-hidden="true"></i>Lihat File
                                                    </a>
                                                </span>
                                                <span id="span-file-coa" class="{{ $viewUploadCoa }}">
                                                    <input type="file" class="form-control" id="i_file_coa" accept=".pdf,.jpg,.jpeg,.png,.webp,.gif">
                                                    <div class="coa-hint mt-1">Upload file COA dalam format PDF atau image (JPG/JPEG/PNG/WEBP/GIF) maksimal 5 MB</div>
                                                </span>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Nomor LS</label>
                                                <input type="text" class="form-control" id="i_no_ls" list="coa-ls-suggestions" value="{{ $coaData['no_ls'] ?? '' }}" placeholder="Nomor LS">
                                                <datalist id="coa-ls-suggestions"></datalist>
                                                <div class="coa-hint mt-1">Ketik nomor LS untuk melihat autocomplete dari data LS yang sudah ada.</div>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Tanggal LS</label>
                                                <input type="text" class="form-control bs-datepicker" id="i_tgl_ls" value="{{ $coaData['tgl_ls'] ?? '' }}" placeholder="DD-MM-YYYY">
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Kode LS</label>
                                                <input type="text" class="form-control" id="i_kode_ls" value="{{ $coaData['kode_ls'] ?? '' }}" placeholder="Kode LS">
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Tanggal Periksa</label>
                                                <input type="text" class="form-control bs-datepicker" id="i_tgl_periksa" value="{{ $coaData['tgl_periksa'] ?? '' }}" placeholder="DD-MM-YYYY">
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">NIB</label>
                                                <input type="text" class="form-control" id="i_nib" value="{{ $coaData['nib'] ?? '' }}" placeholder="NIB Pelaku Usaha">
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">NPWP</label>
                                                <input type="text" class="form-control" id="i_npwp" value="{{ $coaData['npwp'] ?? '' }}" placeholder="NPWP Pelaku Usaha">
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">NITKU</label>
                                                <input type="text" class="form-control" id="i_nitku" value="{{ $coaData['nitku'] ?? '' }}" placeholder="Nomor Identitas Tempat Kegiatan Usaha">
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Nama Perusahaan</label>
                                                <input type="text" class="form-control" id="i_nama_perusahaan" value="{{ $coaData['nama_perusahaan'] ?? '' }}" placeholder="Nama Pelaku Usaha">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                <div class="tab-pane" id="coaKomoditasTab" role="tabpanel">
                                <div class="card coa-block-card">
                                    <div class="card-body">
                                        <div class="card coa-soft-panel mb-4">
                                            <div class="card-header bg-primary text-white">
                                                <div class="fw-semibold">Informasi Komiditas</div>
                                                <div class="coa-subtitle text-white"></div>
                                            </div>
                                            <div class="card-body">
                                                <input type="hidden" id="commodity-edit-key">

                                                <div class="row">
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">Uraian Barang</label>
                                                        <textarea class="form-control" id="commodity_ur_barang" rows="4" placeholder="Uraian Barang Komoditi"></textarea>
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">Spesifikasi Barang</label>
                                                        <textarea class="form-control" id="commodity_spesifikasi" rows="4" placeholder="Spesifikasi Barang"></textarea>
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">Jumlah Barang</label>
                                                        <input type="text" class="form-control" id="commodity_jml_barang" placeholder="Jumlah Barang">
                                                    </div>
                                                    <div class="col-lg-6 mb-3">
                                                        <label class="form-label">Satuan</label>
                                                        <select class="form-control select2-show-search form-select select-satuan" id="commodity_satuan" data-placeholder="Pilih satuan" style="width: 100%;">
                                                            <option label="Pilih satuan"></option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="btn-list text-center">
                                                    <button type="button" class="btn d-w-md btn-danger btn-sm" id="btn-reset-komoditas">
                                                        <i class="bx bx-eraser me-1"></i>Reset
                                                    </button>
                                                    <button type="button" class="btn d-w-md btn-primary btn-sm" id="btn-save-komoditas">
                                                        <i class="bx bx-save me-1"></i>Simpan Komoditas
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="border-top pt-4">
                                            <div class="card-header bg-primary text-white">
                                                <div class="fw-semibold">Data Komiditas</div>
                                                <div class="coa-subtitle text-white">Semua komoditas perlu dilengkapi `Spec`, dan `Param` pada tab `Detail COA`.</div>
                                            </div>
                                            

                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover coa-table align-middle">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="70">Seri</th>
                                                            <th>Uraian Barang</th>
                                                            <th>Spesifikasi</th>
                                                            <th width="150">Jumlah</th>
                                                            <th width="120">Satuan</th>
                                                            <th width="120">COA Group</th>
                                                            <th width="130">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="komoditas-table-body">
                                                        <tr id="komoditas-empty-row">
                                                            <td colspan="7" class="text-center text-muted py-4">Belum ada komoditas yang disimpan.</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                <div class="tab-pane" id="coaGroupTab" role="tabpanel">
                                <div class="card coa-block-card">
                                    <div class="card-header bg-primary text-white">
                                        <div class="">
                                            <div class="fw-semibold">Detail COA per Komoditas</div>
                                            <div class="coa-subtitle text-white">Pilih komoditas yang ingin dilengkapi, lalu isi tab 'Spec' dan 'Param' di dalam panel komoditas tersebut.</div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="coa-commodity-list" class="accordion d-flex flex-column gap-3"></div>
                                        <div id="coa-commodity-empty" class="coa-empty text-center mt-3 d-none">
                                            Belum ada komoditas. Tambahkan komoditas terlebih dahulu, lalu lengkapi detail COA pada masing-masing komoditas.
                                        </div>
                                    </div>
                                </div>
                </div>
            </div>
        </form>
        <div class="col-md-12 mt-2 text-center">
            <button type="button" class="btn btn-dark btn-sm" id="btn-back-coa">
                <i class="bx bx-arrow-back me-1"></i>Kembali
            </button>
            <button type="button" class="btn btn-primary btn-sm my-1" id="btn-save-coa">
                <i class="bx bx-save me-1"></i>Simpan COA
            </button>
        </div>
    </div>
</div>

<template id="template-commodity-coa-panel">
    <div class="accordion-item card border coa-commodity-panel">
        <h2 class="accordion-header bg-primary">
            <div class="d-flex align-items-center justify-content-between gap-3 px-3 py-2">
                <button class="accordion-button coa-group-button collapsed px-0 py-2 shadow-none commodity-panel-toggle"
                    type="button" data-bs-toggle="collapse" style="background: #5d87ff !important;">
                    <span>
                        <span class="fw-semibold commodity-panel-title d-block">Komoditas</span>
                        <small class="coa-subtitle commodity-panel-subtitle text-white">Kelola COA Group untuk komoditas ini.</small>
                    </span>
                </button>
                <button type="button" class="btn btn-primary btn-sm btn-add-group d-none">
                    <i class="bx bx-plus me-1"></i>Tambah COA Group
                </button>
            </div>
        </h2>
        <div class="collapse show commodity-panel-collapse">
            <div class="card-body">
                <div class="commodity-group-list d-flex flex-column gap-3"></div>
                <div class="commodity-group-empty coa-empty text-center d-none">
                    Belum ada COA Group untuk komoditas ini.
                </div>
            </div>
        </div>
    </div>
</template>

<template id="template-group">
    <div class="accordion-item card border coa-item">
        <h2 class="accordion-header bg-primary">
            <div class="d-flex align-items-center justify-content-between gap-3 px-3 py-2">
                <button class="accordion-button coa-group-button collapsed px-0 py-2 shadow-none group-toggle"
                    type="button" data-bs-toggle="collapse" style="background: #5d87ff !important;">
                    <span>
                        <span class="fw-semibold group-title d-block">Data COA</span>
                        <small class="coa-subtitle group-subtitle text-white">Pilih komoditas lalu lengkapi Spec dan Param.</small>
                    </span>
                </button>
                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-group flex-shrink-0 d-none">
                    <i class="bx bx-trash me-1"></i>Hapus Group
                </button>
            </div>
        </h2>
        <div class="collapse show group-collapse">
            <div class="card-body">
                <ul class="nav nav-tabs mb-3 group-detail-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active group-spec-tab" data-bs-toggle="tab" type="button" role="tab">Spec</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link group-param-tab" data-bs-toggle="tab" type="button" role="tab">Param</button>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="tab-pane fade show active group-spec-pane" role="tabpanel">
                        <div class="card coa-soft-panel mb-4">
                            <div class="card-header bg-primary text-white">
                                <div class="fw-semibold mb-1">Form Spec</div>
                                <div class="coa-subtitle text-white">Isi data spec, lalu simpan ke tabel spec pada COA Group ini.</div>
                            </div>
                            <div class="card-body">
                                
                                <input type="hidden" class="spec-edit-key">

                                <div class="row">
                                    <div class="col-md-4 mb-3"><label class="form-label">Ash ARB (%)</label><input type="text" class="form-control spec-input" data-field="ash_arb"></div>
                                    <div class="col-md-4 mb-3"><label class="form-label">Ash ADB (%)</label><input type="text" class="form-control spec-input" data-field="ash_adb"></div>
                                    <div class="col-md-4 mb-3"><label class="form-label">TM ARB (%)</label><input type="text" class="form-control spec-input" data-field="tm_arb"></div>
                                    <div class="col-md-4 mb-3"><label class="form-label">Inherent Moisture ADB (%)</label><input type="text" class="form-control spec-input" data-field="inh_adb"></div>
                                    <div class="col-md-4 mb-3"><label class="form-label">Total Sulphur ARB (%)</label><input type="text" class="form-control spec-input" data-field="tsulf_arb"></div>
                                    <div class="col-md-4 mb-3"><label class="form-label">Total Sulphur ADB (%)</label><input type="text" class="form-control spec-input" data-field="tsulf_adb"></div>
                                    <div class="col-md-4 mb-3"><label class="form-label">Volatile Matter ADB (%)</label><input type="text" class="form-control spec-input" data-field="vol_matter"></div>
                                    <div class="col-md-4 mb-3"><label class="form-label">Fixed Carbon ARB (%)</label><input type="text" class="form-control spec-input" data-field="fix_carb"></div>
                                    <div class="col-md-4 mb-3"><label class="form-label">Size 0 to 50 mm (%)</label><input type="text" class="form-control spec-input" data-field="size_0"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">Size Above 50 mm (%)</label><input type="text" class="form-control spec-input" data-field="size_50"></div>
                                    <div class="col-md-6 mb-3"><label class="form-label">HGI (%)</label><input type="text" class="form-control spec-input" data-field="hgi"></div>
                                </div>

                                <div class="btn-list text-center">
                                    <button type="button" class="btn d-w-md btn-danger btn-sm btn-reset-spec">
                                        <i class="bx bx-eraser me-1"></i>Reset
                                    </button>
                                    <button type="button" class="btn d-w-md btn-primary btn-sm btn-save-spec">
                                        <i class="bx bx-save me-1"></i>Simpan Spec
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="card-header bg-primary text-white">
                                <div class="fw-semibold mb-1">Data Spec</div>
                                <div class="coa-subtitle text-white">Data spec yang sudah di input akan tampil di table ini.</div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover coa-table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="70">No</th>
                                            <th>Ash / Moisture / Sulphur</th>
                                            <th>Volatile / Carbon / Size</th>
                                            <th width="120">HGI</th>
                                            <th width="130">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="spec-table-body">
                                        <tr class="spec-empty-row">
                                            <td colspan="5" class="text-center text-muted py-4">Belum ada data spec.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade group-param-pane" role="tabpanel">
                        <div class="card coa-soft-panel mb-4">
                            <div class="card-header bg-primary text-white">
                                <div class="fw-semibold mb-1">Form Param</div>
                                <div class="coa-subtitle text-white">Lengkapi data param untuk komoditasa yang dipilih.</div>
                            </div>
                            <div class="card-body">
                                
                                <input type="hidden" class="param-edit-key">

                                <div class="row">
                                    <div class="col-lg-12 mb-3"><label class="form-label">GCV ARB (Kcal/Kg)</label><input type="text" class="form-control param-input" data-field="gcv_arb"></div>
                                    <div class="col-lg-12 mb-3"><label class="form-label">GCV ADB (Kcal/Kg)</label><input type="text" class="form-control param-input" data-field="gcv_adb"></div>
                                    <div class="col-lg-12 mb-3"><label class="form-label">NCV ARB (Kcal/Kg)</label><input type="text" class="form-control param-input" data-field="ncv_arb"></div>
                                </div>

                                <div class="btn-list text-center">
                                    <button type="button" class="btn d-w-md btn-danger btn-sm btn-reset-param">
                                        <i class="bx bx-eraser me-1"></i>Reset
                                    </button>
                                    <button type="button" class="btn d-w-md btn-primary btn-sm btn-save-param">
                                        <i class="bx bx-save me-1"></i>Simpan Param
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="card-header bg-primary text-white">
                                <div class="fw-semibold mb-1">Data Param</div>
                                <div class="coa-subtitle text-white">Semua data parameter yang sudah di input akan tampil di table ini.</div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover coa-table align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="70">No</th>
                                            <th>GCV ARB</th>
                                            <th>GCV ADB</th>
                                            <th>NCV ARB</th>
                                            <th width="130">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="param-table-body">
                                        <tr class="param-empty-row">
                                            <td colspan="5" class="text-center text-muted py-4">Belum ada data param.</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
