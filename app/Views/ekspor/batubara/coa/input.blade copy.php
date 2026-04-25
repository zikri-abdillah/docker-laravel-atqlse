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

<!-- ================= HEADER ================= -->
<div class="content__header content__boxed overlapping">
    <div class="content__wrap">
        <h1 class="page-title mt-2 mb-2">{{ $page_title }}</h1>
    </div>
</div>

<!-- ================= CONTENT ================= -->
<div class="content__boxed">
<div class="content__wrap">

<div class="row">
<div class="col-md-12">

<div class="tab-base">

    <!-- ================= TAB NAV ================= -->
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#_dm-tabsSI">
                INFORMASI UMUM
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#_dm-tabsDokumen">
                KOMODITAS
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#_dm-tabsPackage">
                DETAIL COA
            </button>
        </li>
    </ul>

    <!-- ================= FORM ================= -->
    <form id="form-coa">
        <input type="hidden" id="idData" value="{{ $coaData['idData'] ?? '' }}">

        <div class="tab-content pt-3">

            <!-- ================================================= -->
            <!-- TAB 1 : INFORMASI -->
            <!-- ================================================= -->
            <div class="tab-pane fade show active" id="_dm-tabsSI">

                <div class="card coa-block-card shadow-sm border-0">

                    <div class="card-header bg-primary text-white">
                        <div class="fw-semibold">📄 Informasi COA</div>
                        <small>Silahkan lengkapi data berikut</small>
                    </div>

                    <div class="card-body">

                        <div class="row">

                            <!-- Jenis -->
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Jenis Penerbitan</label>
                                <input type="hidden" id="i_jns_penerbitan" value="{{ $coaJenisValue }}">

                                <div class="form-control bg-light d-flex justify-content-between">
                                    <span>{{ $coaJenisLabel }}</span>

                                    @php
                                        $badge = 'secondary';
                                        if ($coaJenisValue == '1') $badge = 'success';
                                        elseif ($coaJenisValue == '2') $badge = 'warning';
                                        elseif ($coaJenisValue == '9') $badge = 'danger';
                                    @endphp

                                    <span class="badge bg-{{ $badge }}">
                                        {{ $coaJenisLabel }}
                                    </span>
                                </div>
                            </div>

                            <!-- Nomor COA -->
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Nomor COA</label>
                                <input type="text" class="form-control" id="i_nomor_coa"
                                    value="{{ $coaData['nomor_coa'] ?? '' }}">
                            </div>

                            <!-- Tanggal -->
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Tanggal COA</label>
                                <input type="text" class="form-control bs-datepicker" id="i_tgl_coa"
                                    value="{{ $coaData['tgl_coa'] ?? '' }}">
                            </div>

                            <!-- Nomor LS -->
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Nomor LS</label>
                                <input type="text" class="form-control"
                                    id="i_no_ls"
                                    list="coa-ls-suggestions"
                                    value="{{ $coaData['no_ls'] ?? '' }}">
                                <datalist id="coa-ls-suggestions"></datalist>
                            </div>

                            <!-- lainnya tetap -->
                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Tanggal LS</label>
                                <input type="text" class="form-control bs-datepicker"
                                    id="i_tgl_ls_coa"
                                    value="{{ $coaData['tgl_ls'] ?? '' }}">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Kode LS</label>
                                <input type="text" class="form-control"
                                    id="i_kode_ls"
                                    value="{{ $coaData['kode_ls'] ?? '' }}">
                            </div>

                            <div class="col-lg-4 mb-3">
                                <label class="form-label">Nama Perusahaan</label>
                                <input type="text" class="form-control"
                                    id="i_nama_perusahaan"
                                    value="{{ $coaData['nama_perusahaan'] ?? '' }}">
                            </div>

                        </div>

                    </div>
                </div>

            </div>

            <!-- ================================================= -->
            <!-- TAB 2 : KOMODITAS -->
            <!-- ================================================= -->
            <div class="tab-pane fade" id="_dm-tabsDokumen">

                <div class="card coa-block-card shadow-sm border-0">

                    <div class="card-header bg-primary text-white">
                        📦 Manajemen Komoditas
                    </div>

                    <div class="card-body">

                        <!-- FORM -->
                        <div class="card coa-soft-panel mb-4 shadow-sm">
                            <div class="card-body">

                                <input type="hidden" id="commodity-edit-key">

                                <div class="row">

                                    <div class="col-lg-6 mb-3">
                                        <label>Uraian</label>
                                        <textarea class="form-control" id="commodity_ur_barang"></textarea>
                                    </div>

                                    <div class="col-lg-6 mb-3">
                                        <label>Spesifikasi</label>
                                        <textarea class="form-control" id="commodity_spesifikasi"></textarea>
                                    </div>

                                    <div class="col-lg-4 mb-3">
                                        <label>Jumlah</label>
                                        <input class="form-control" id="commodity_jml_barang">
                                    </div>

                                    <div class="col-lg-4 mb-3">
                                        <label>Satuan</label>
                                        <select class="form-control" id="commodity_satuan"></select>
                                    </div>

                                </div>

                                <div class="text-end">
                                    <button type="button" class="btn btn-primary" id="btn-save-komoditas">
                                        Simpan
                                    </button>
                                </div>

                            </div>
                        </div>

                        <!-- TABLE -->
                        <div class="card border-0 shadow-sm">
                                
                            <div class="card-header bg-light fw-semibold">
                                📋 Data Komoditas
                                <div class="small text-muted fw-normal">
                                    Lengkapi Spec & Param di tab Detail COA
                                </div>
                            </div>
            
                            <div class="card-body">
            
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle coa-table">
            
                                        <thead class="table-light text-center">
                                            <tr>
                                                <th width="60">No</th>
                                                <th class="text-start">Uraian Barang</th>
                                                <th class="text-start">Spesifikasi</th>
                                                <th width="120">Jumlah</th>
                                                <th width="120">Satuan</th>
                                                <th width="130">COA Group</th>
                                                <th width="140">Aksi</th>
                                            </tr>
                                        </thead>
            
                                        <tbody id="komoditas-table-body">
            
                                            <tr id="komoditas-empty-row">
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    <i class="bx bx-box fs-3 d-block mb-2"></i>
                                                    Belum ada komoditas
                                                </td>
                                            </tr>
            
                                        </tbody>
            
                                    </table>
                                </div>
            
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ================================================= -->
            <!-- TAB 3 : DETAIL COA -->
            <!-- ================================================= -->
            <div class="tab-pane fade" id="_dm-tabsPackage">

                <div class="card coa-block-card shadow-sm border-0">

                    <div class="card-header bg-primary text-white">
                        📑 Detail COA
                    </div>

                    <div class="card-body">

                        <div id="coa-commodity-list"
                            class="accordion d-flex flex-column gap-3"></div>

                        <div id="coa-commodity-empty"
                            class="text-center text-muted py-5">
                            Belum ada komoditas
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </form>

    <!-- ================= GLOBAL ACTION ================= -->
    <div class="card mt-4 border-0 shadow-sm">
        <div class="card-body text-center">

            <button type="button"
                class="btn btn-outline-secondary px-4"
                id="btn-back-coa">
                ← Kembali
            </button>

            <button type="button"
                class="btn btn-primary px-4"
                id="btn-save-coa">
                💾 Simpan COA
            </button>

        </div>
    </div>

</div>
</div>
</div>
</div>
</div>

<template id="template-commodity-coa-panel">
    <div class="accordion-item card border">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" data-bs-toggle="collapse">
                <span class="commodity-panel-title fw-semibold">Komoditas</span>
            </button>
        </h2>

        <div class="collapse show">
            <div class="card-body">

                <div class="commodity-group-list"></div>

                <div class="text-center text-muted commodity-group-empty d-none">
                    Belum ada group
                </div>

            </div>
        </div>
    </div>
</template>

<template id="template-group">
    <div class="card border mb-3">

        <div class="card-header d-flex justify-content-between">
            <span class="fw-semibold">COA Group</span>
            <button class="btn btn-sm btn-danger btn-remove-group">Hapus</button>
        </div>

        <div class="card-body">

            <ul class="nav nav-tabs mb-3">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab">Spec</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab">Param</button>
                </li>
            </ul>

            <div class="tab-content">

                <div class="tab-pane fade show active">
                    <input class="form-control mb-2 spec-input" placeholder="Ash ARB">
                </div>

                <div class="tab-pane fade">
                    <input class="form-control mb-2 param-input" placeholder="GCV ARB">
                </div>

            </div>

        </div>

    </div>
</template>