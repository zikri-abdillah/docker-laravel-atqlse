@php
    $cowData = $cowData ?? [];
    $cowJenisMap = [
        '1' => 'Baru',
        '2' => 'Perubahan',
        '9' => 'Pembatalan',
    ];
    $cowJenisValue = (string) ($cowData['jns_penerbitan'] ?? '1');
    $cowJenisLabel = $cowJenisMap[$cowJenisValue] ?? 'Baru';
    $cowIsEdit = !empty($cowData['idData']);
    $cowLockNomorTanggal = $cowIsEdit && $cowJenisValue === '2';
@endphp

<style>
    .tabs-menu-form.cow-main-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        margin-bottom: 0;
        padding: 0 0 0.65rem 0;
        border-bottom: 1px solid #e4ebf3;
        background: transparent;
        overflow: visible;
    }

    .tabs-menu-form.cow-main-tabs > li {
        flex: 0 0 auto;
        float: none;
    }

    .tabs-menu-form.cow-main-tabs li a.cow-main-tab-link {
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

    .tabs-menu-form.cow-main-tabs li a.cow-main-tab-link:hover,
    .tabs-menu-form.cow-main-tabs li a.cow-main-tab-link:focus {
        color: #24384d;
        background: #f1f5f9;
        border-color: #d5e0ea;
    }

    .tabs-menu-form.cow-main-tabs li a.cow-main-tab-link.active {
        background: #eef4fa;
        border-color: #b9c8d8;
        color: #29425b;
        box-shadow: none;
    }

    .tabs-menu-form.cow-main-tabs li a.cow-main-tab-link svg {
        flex-shrink: 0;
        width: 16px;
        height: 16px;
    }

    .cow-main-tab-link::before {
        content: "";
        width: 2px;
        align-self: stretch;
        border-radius: 999px;
        background: transparent;
    }

    .tabs-menu-form.cow-main-tabs li a.cow-main-tab-link.active::before {
        background: #416589;
    }

    @media (max-width: 767.98px) {
        .tabs-menu-form.cow-main-tabs {
            gap: 0.4rem;
            padding-bottom: 0.6rem;
        }

        .tabs-menu-form.cow-main-tabs > li {
            flex: 1 1 100%;
        }

        .tabs-menu-form.cow-main-tabs li a.cow-main-tab-link {
            min-height: 38px;
            padding: 0.45rem 0.7rem;
        }
    }

    .cow-subtitle {
        color: #6c7a89;
        font-size: 10px;
    }

    .cow-actions {
        gap: 0.5rem;
    }

    .cow-stage-note {
        background: linear-gradient(135deg, #f7fbff 0%, #eef6ff 100%);
        border: 1px solid #d8e8fb;
        border-radius: 14px;
    }

    .cow-block-card {
        border: 1px solid #e7edf3;
        box-shadow: 0 0.35rem 0.9rem rgba(27, 39, 51, 0.04);
    }

    .cow-soft-panel {
        border: 1px dashed #cad5df;
        background: #fbfdff;
    }

    .cow-table td,
    .cow-table th {
        vertical-align: top;
    }

    .cow-hint {
        color: #6c7a89;
        font-size: 8px;
    }

    .cow-inline-feedback {
        display: none;
        border-radius: 10px;
        font-size: 12px;
    }
</style>

<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-3">{{ $page_title }}</h5>
        <form id="form-cow">
            <input type="hidden" id="idData" value="{{ $cowData['idData'] ?? '' }}">

            <ul class="nav tabs-menu-form cow-main-tabs" role="tablist">
                <li>
                    <a href="#cowUmumTab" data-bs-toggle="tab" class="active cow-main-tab-link" id="buttonUmum" role="tab">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-info" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                            <path d="M11 14h1v4h1" />
                            <path d="M12 11h.01" />
                        </svg>
                        Informasi Umum
                    </a>
                </li>
                <li>
                    <a href="#cowKomoditasTab" data-bs-toggle="tab" class="cow-main-tab-link" id="buttonKomoditas" role="tab">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-diamond" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M6 5h12l3 5l-8.5 9.5a.7 .7 0 0 1 -1 0l-8.5 -9.5l3 -5"></path>
                            <path d="M10 12l-2 -2.2l.6 -1"></path>
                        </svg>
                        Komoditas
                    </a>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <div class="tab-pane active" id="cowUmumTab" role="tabpanel">
                                <div class="card cow-block-card mb-4">
                                    <div class="card-header bg-primary text-white">
                                        <div class="fw-semibold">Informasi Header</div>
                                        <div class="cow-subtitle text-white">Silahkan lengkapi data berikut.</div>
                                    </div>
                                    <div class="card-body">
                                        <div id="ls-reference-feedback" class="alert cow-inline-feedback mb-3" role="alert"></div>
                                        <div class="row">
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Jenis Penerbitan</label>
                                                <input type="hidden" id="i_jns_penerbitan" value="{{ $cowJenisValue }}">
                                                <div class="form-control bg-light d-flex align-items-center justify-content-between">
                                                    <span>{{ $cowJenisValue }} : {{ $cowJenisLabel }}</span>
                                                    @php
                                                        $cowJenisBadgeClass = 'bg-light-secondary text-secondary';
                                                        if ($cowJenisValue === '1') {
                                                            $cowJenisBadgeClass = 'bg-light-success text-success';
                                                        } elseif ($cowJenisValue === '2') {
                                                            $cowJenisBadgeClass = 'bg-light-warning text-warning';
                                                        } elseif ($cowJenisValue === '9') {
                                                            $cowJenisBadgeClass = 'bg-light-danger text-danger';
                                                        }
                                                    @endphp
                                                    <span class="badge {{ $cowJenisBadgeClass }}">{{ $cowJenisLabel }}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Nomor COW</label>
                                                @if ($cowLockNomorTanggal)
                                                    <input type="hidden" id="i_nomor_cow" value="{{ $cowData['nomor_cow'] ?? '' }}">
                                                    <input type="text" class="form-control bg-light" value="{{ $cowData['nomor_cow'] ?? '' }}" placeholder="Nomor COW" readonly disabled>
                                                    <div class="cow-hint mt-1">Nomor COW untuk dokumen perubahan mengikuti dokumen asal.</div>
                                                @else
                                                    <input type="text" class="form-control" id="i_nomor_cow" value="{{ $cowData['nomor_cow'] ?? '' }}" placeholder="Nomor COW">
                                                @endif
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Tanggal COW</label>
                                                @if ($cowLockNomorTanggal)
                                                    <input type="hidden" id="i_tgl_cow" value="{{ $cowData['tgl_cow'] ?? '' }}">
                                                    <input type="text" class="form-control bg-light" value="{{ $cowData['tgl_cow'] ?? '' }}" placeholder="DD-MM-YYYY" readonly disabled>
                                                    <div class="cow-hint mt-1">Tanggal COW untuk dokumen perubahan mengikuti dokumen asal.</div>
                                                @else
                                                    <input type="text" class="form-control bs-datepicker" id="i_tgl_cow" value="{{ $cowData['tgl_cow'] ?? '' }}" placeholder="DD-MM-YYYY">
                                                @endif
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">File COW</label>
                                                @php
                                                    $viewFileCow = 'd-none';
                                                    $viewUploadCow = '';
                                                    if (!empty($cowData['path_file']) && file_exists(WRITEPATH . 'uploads/' . $cowData['path_file'])) {
                                                        $viewFileCow = '';
                                                        $viewUploadCow = 'd-none';
                                                    }
                                                @endphp
                                                <span id="span-view-cow" class="{{ $viewFileCow }}">
                                                    <button type="button" class="btn btn-sm btn-danger btn-delete-file-cow">
                                                        <i class="fadeIn animated bx bx-eraser" aria-hidden="true"></i>Hapus File
                                                    </button>
                                                    <a href="{{ !empty($cowData['url_cow']) ? base_url('doc/cow/' . $cowData['url_cow']) : 'javascript:;' }}"
                                                        target="_blank"
                                                        class="btn btn-sm btn-info btn-view-file-cow">
                                                        <i class="fadeIn animated lni lni-eye" aria-hidden="true"></i>Lihat File
                                                    </a>
                                                </span>
                                                <span id="span-file-cow" class="{{ $viewUploadCow }}">
                                                    <input type="file" class="form-control" id="i_file_cow" accept=".pdf,.jpg,.jpeg,.png,.webp,.gif">
                                                    <div class="cow-hint mt-1">Upload file COW dalam format PDF atau image (JPG/JPEG/PNG/WEBP/GIF) maksimal 5 MB.</div>
                                                </span>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Nomor LS</label>
                                                <input type="text" class="form-control" id="i_no_ls" list="cow-ls-suggestions" value="{{ $cowData['no_ls'] ?? '' }}" placeholder="Nomor LS">
                                                <datalist id="cow-ls-suggestions"></datalist>
                                                <div class="cow-hint mt-1">Ketik nomor LS untuk melihat autocomplete dari data LS yang sudah ada.</div>
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Tanggal LS</label>
                                                <input type="text" class="form-control bs-datepicker" id="i_tgl_ls" value="{{ $cowData['tgl_ls'] ?? '' }}" placeholder="DD-MM-YYYY">
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Kode LS</label>
                                                <input type="text" class="form-control" id="i_kode_ls" value="{{ $cowData['kode_ls'] ?? '' }}" placeholder="Kode LS">
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Tanggal Periksa</label>
                                                <input type="text" class="form-control bs-datepicker" id="i_tgl_periksa" value="{{ $cowData['tgl_periksa'] ?? '' }}" placeholder="DD-MM-YYYY">
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">NIB</label>
                                                <input type="text" class="form-control" id="i_nib" value="{{ $cowData['nib'] ?? '' }}" placeholder="NIB Pelaku Usaha">
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">NPWP</label>
                                                <input type="text" class="form-control" id="i_npwp" value="{{ $cowData['npwp'] ?? '' }}" placeholder="NPWP Pelaku Usaha">
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">NITKU</label>
                                                <input type="text" class="form-control" id="i_nitku" value="{{ $cowData['nitku'] ?? '' }}" placeholder="Nomor Identitas Tempat Kegiatan Usaha">
                                            </div>
                                            <div class="col-lg-4 mb-3">
                                                <label class="form-label">Nama Perusahaan</label>
                                                <input type="text" class="form-control" id="i_nama_perusahaan" value="{{ $cowData['nama_perusahaan'] ?? '' }}" placeholder="Nama Pelaku Usaha">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                <div class="tab-pane" id="cowKomoditasTab" role="tabpanel">
                                <div class="card cow-block-card">
                                    <div class="card-body">
                                        <div class="card cow-soft-panel">
                                            <div class="card-header bg-primary text-white">
                                                <div class="fw-semibold">Form Komoditas</div>
                                                <div class="cow-subtitle text-white">Silahkan lengkapi isian berikut.</div>
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

                                        <div class="card-header bg-primary text-white">
                                            <div class="fw-semibold mb-1">Data Komoditas</div>
                                            <div class="cow-subtitle text-white">Detail komoditas COW.</div>
                                        </div>
                                        <div class="border-top">
                                            

                                            <div class="table-responsive">
                                                <table class="table table-bordered table-hover cow-table align-middle">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th width="70">Seri</th>
                                                            <th>Uraian Barang</th>
                                                            <th>Spesifikasi</th>
                                                            <th width="150">Jumlah</th>
                                                            <th width="120">Satuan</th>
                                                            <th width="130">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="komoditas-table-body">
                                                        <tr id="komoditas-empty-row">
                                                            <td colspan="6" class="text-center text-muted py-4">Belum ada komoditas yang disimpan.</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                </div>
            </div>
        </form>
        <div class="col-md-12 mt-2 text-center">
            <button type="button" class="btn btn-dark btn-sm" id="btn-back-cow">
                <i class="bx bx-arrow-back me-1"></i>Kembali
            </button>
            <button type="button" class="btn btn-primary btn-sm my-1" id="btn-save-cow">
                <i class="bx bx-save me-1"></i>Simpan COW
            </button>
        </div>
    </div>
</div>
