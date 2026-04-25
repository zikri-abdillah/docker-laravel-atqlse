@php
    $cowRecord = $cowRecord ?? [];
    $jenisPenerbitanLabel = $jenisPenerbitanLabel ?? '-';
    $statusBadge = $statusBadge ?? '';
    $infoCell = $infoCell ?? '';
    $cowFileUrl = $cowFileUrl ?? '';
    $cowRecordId = $cowRecordId ?? '';
    $statusKirim = $statusKirim ?? 'DRAFT';
    $canCreatePerubahan = $canCreatePerubahan ?? false;
    $canCreatePembatalan = $canCreatePembatalan ?? false;

    $formatDate = static function ($value) {
        $value = trim((string) $value);
        if ($value === '') {
            return '-';
        }

        $timestamp = strtotime($value);
        return $timestamp === false ? htmlspecialchars($value, ENT_QUOTES, 'UTF-8') : date('d-m-Y', $timestamp);
    };

    $formatNumber = static function ($value) {
        if ($value === null || $value === '') {
            return '-';
        }

        return rtrim(rtrim((string) $value, '0'), '.');
    };

    $jenisBadgeClass = 'bg-light-secondary text-secondary';
    if (($cowRecord['jns_penerbitan'] ?? '') === '1' || ($cowRecord['jns_penerbitan'] ?? null) === 1) {
        $jenisBadgeClass = 'bg-light-success text-success';
    } elseif (($cowRecord['jns_penerbitan'] ?? '') === '2' || ($cowRecord['jns_penerbitan'] ?? null) === 2) {
        $jenisBadgeClass = 'bg-light-warning text-warning';
    } elseif (($cowRecord['jns_penerbitan'] ?? '') === '9' || ($cowRecord['jns_penerbitan'] ?? null) === 9) {
        $jenisBadgeClass = 'bg-light-danger text-danger';
    }
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

    .cow-view-card {
        border: 1px solid #e7edf3;
        box-shadow: 0 0.35rem 0.9rem rgba(27, 39, 51, 0.04);
    }

    .cow-view-muted {
        color: #6c7a89;
        font-size: 10px;
    }

    .cow-view-label {
        color: #6c7a89;
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.04em;
        margin-bottom: 0.35rem;
    }

    .cow-view-value {
        font-size: 15px;
        font-weight: 600;
        color: #22313f;
    }
</style>


<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-3">{{ $page_title }}</h5>
        <ul class="nav tabs-menu-form cow-main-tabs">
            <li>
                <a href="#cowSummaryTab" data-bs-toggle="tab" class="active cow-main-tab-link" id="buttonUmum">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-info" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
                        <path d="M11 14h1v4h1" />
                        <path d="M12 11h.01" />
                    </svg>
                    Informasi Umum
                </a>
            </li>
            <li>
                <a href="#cowDetailTab" data-bs-toggle="tab" class="cow-main-tab-link" id="buttonKomoditas">
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
            <div class="tab-pane active" id="cowSummaryTab">
                <div class="card cow-view-card mb-4">
                    <div class="card-header bg-primary text-white">
                        <div class="fw-semibold">Ringkasan Header</div>
                        <div class="cow-view-muted text-white">Data tersimpan ditampilkan dalam mode baca.</div>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-lg-3 col-md-6">
                                <div class="cow-view-label">Jenis Penerbitan</div>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <div class="cow-view-value">{{ ($cowRecord['jns_penerbitan'] ?? '-') . ' : ' . $jenisPenerbitanLabel }}</div>
                                    <span class="badge {{ $jenisBadgeClass }}">{{ $jenisPenerbitanLabel }}</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="cow-view-label">Nomor COW</div>
                                <div class="cow-view-value">{{ $cowRecord['nomor_cow'] ?? '-' }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="cow-view-label">Tanggal COW</div>
                                <div class="cow-view-value">{{ $formatDate($cowRecord['tgl_cow'] ?? '') }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="cow-view-label">Tanggal Periksa</div>
                                <div class="cow-view-value">{{ $formatDate($cowRecord['tgl_periksa'] ?? '') }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="cow-view-label">Nomor LS</div>
                                <div class="cow-view-value">{{ $cowRecord['no_ls'] ?? '-' }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="cow-view-label">Tanggal LS</div>
                                <div class="cow-view-value">{{ $formatDate($cowRecord['tgl_ls'] ?? '') }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="cow-view-label">Kode LS</div>
                                <div class="cow-view-value">{{ $cowRecord['kode_ls'] ?? '-' }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="cow-view-label">Status</div>
                                <div>{!! $statusBadge !!}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="cow-view-label">NIB</div>
                                <div class="cow-view-value">{{ $cowRecord['nib'] ?? '-' }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="cow-view-label">NPWP</div>
                                <div class="cow-view-value">{{ $cowRecord['npwp'] ?? '-' }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="cow-view-label">NITKU</div>
                                <div class="cow-view-value">{{ $cowRecord['nitku'] ?? '-' }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="cow-view-label">Nama Perusahaan</div>
                                <div class="cow-view-value">{{ $cowRecord['nama_perusahaan'] ?? '-' }}</div>
                            </div>
                            <div class="col-lg-6">
                                <div class="cow-view-label">Info Input</div>
                                <div class="p-3 rounded-3 bg-light">{!! $infoCell !!}</div>
                            </div>
                            <div class="col-lg-6">
                                <div class="cow-view-label">File COW</div>
                                @if (!empty($cowFileUrl))
                                    <a href="{{ $cowFileUrl }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="bx bx-show-alt me-1"></i>Lihat File
                                    </a>
                                @else
                                    <div class="cow-view-value">Belum ada file</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @if (($canCreatePerubahan || $canCreatePembatalan) && !empty($cowRecordId))
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const perubahanButton = document.getElementById('btn-create-perubahan-cow');
                            const pembatalanButton = document.getElementById('btn-create-pembatalan-cow');

                            function sendPembatalan(id) {
                                const formData = new FormData();
                                formData.append('id', id);

                                postAjax(baseurl + 'ekspor/cow/pembatalan', formData, function (resp) {
                                    showAlert(resp);
                                    if (resp.code === '00') {
                                        window.location.replace(baseurl + 'ekspor/cow');
                                    }
                                });
                            }

                            if (perubahanButton) {
                                perubahanButton.addEventListener('click', function () {
                                    const id = this.dataset.id || '';
                                    if (!id) {
                                        return;
                                    }

                                    swal_confirm('Konfirmasi Perubahan', 'Buat draft perubahan dari data COW ini?', function (confirm) {
                                        if (!confirm) {
                                            return;
                                        }

                                        const formData = new FormData();
                                        formData.append('id', id);

                                        postAjax(baseurl + 'ekspor/cow/perubahan', formData, function (resp) {
                                            showAlert(resp);

                                            if (resp.code === '00' && resp.data && resp.data.id) {
                                                $.redirect(baseurl + 'ekspor/cow/edit', { id: resp.data.id, csrf_appls: csrfName }, 'POST', '_self');
                                            }
                                        });
                                    });
                                });
                            }

                            if (pembatalanButton) {
                                pembatalanButton.addEventListener('click', function () {
                                    const id = this.dataset.id || '';
                                    if (!id) {
                                        return;
                                    }

                                    swal_confirm('Konfirmasi Pembatalan', 'Kirim pembatalan untuk data COW ini ke Inatrade?', function (confirm) {
                                        if (!confirm) {
                                            return;
                                        }

                                        sendPembatalan(id);
                                    });
                                });
                            }
                        });
                    </script>
                @endif

            </div>

                <div class="tab-pane" id="cowDetailTab">
                <div class="card cow-view-card">
                    <div class="card-header bg-primary text-white">
                        <div class="fw-semibold">Data Komoditas</div>
                        <div class="cow-view-muted text-white">Menampilkan seluruh komoditas yang tersimpan pada dokumen COW ini.</div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">No</th>
                                        <th>Uraian Barang</th>
                                        <th>Spesifikasi</th>
                                        <th style="width: 180px;">Jumlah</th>
                                        <th style="width: 180px;">Satuan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse (($cowRecord['komoditas'] ?? []) as $index => $commodity)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $commodity['ur_barang'] ?? '-' }}</td>
                                            <td>{{ $commodity['spesifikasi'] ?? '-' }}</td>
                                            <td>{{ $formatNumber($commodity['jml_barang'] ?? '') }}</td>
                                            <td>{{ $commodity['satuan_label'] ?? ($commodity['satuan'] ?? '-') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Belum ada komoditas yang tersimpan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-2 text-center">
                <a href="{{ base_url('ekspor/cow') }}" class="btn btn-dark btn-sm">
                    <i class="bx bx-arrow-back me-1"></i>Kembali
                </a>
                @if ($canCreatePerubahan && !empty($cowRecordId))
                    <button type="button" class="btn btn-success btn-sm my-1" id="btn-create-perubahan-cow" data-id="{{ $cowRecordId }}">
                        <i class="bx bx-git-compare me-1"></i>Perubahan
                    </button>
                @endif
                @if ($canCreatePembatalan && !empty($cowRecordId))
                    <button type="button" class="btn btn-danger btn-sm my-1" id="btn-create-pembatalan-cow" data-id="{{ $cowRecordId }}">
                        <i class="bx bx-x-circle me-1"></i>Pembatalan
                    </button>
                @endif
        </div>
    </div>
</div>
