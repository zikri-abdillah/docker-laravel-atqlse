@php
    $coaRecord = $coaRecord ?? [];
    $jenisPenerbitanLabel = $jenisPenerbitanLabel ?? '-';
    $statusBadge = $statusBadge ?? '';
    $infoCell = $infoCell ?? '';
    $coaFileUrl = $coaFileUrl ?? '';
    $coaRecordId = $coaRecordId ?? '';
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

    $specLabels = [
        'ash_arb' => 'Ash ARB (%)',
        'ash_adb' => 'Ash ADB (%)',
        'tm_arb' => 'TM ARB (%)',
        'inh_adb' => 'Inherent Moisture ADB (%)',
        'tsulf_arb' => 'Total Sulphur ARB (%)',
        'tsulf_adb' => 'Total Sulphur ADB (%)',
        'vol_matter' => 'Volatile Matter (%)',
        'fix_carb' => 'Fixed Carbon (%)',
        'size_0' => 'Size 0 (%)',
        'size_50' => 'Size 50 (%)',
        'hgi' => 'HGI',
    ];

    $paramLabels = [
        'gcv_arb' => 'GCV ARB',
        'gcv_adb' => 'GCV ADB',
        'ncv_arb' => 'NCV ARB',
    ];

    $jenisBadgeClass = 'bg-light-secondary text-secondary';
    if (($coaRecord['jns_penerbitan'] ?? '') === '1' || ($coaRecord['jns_penerbitan'] ?? null) === 1) {
        $jenisBadgeClass = 'bg-light-success text-success';
    } elseif (($coaRecord['jns_penerbitan'] ?? '') === '2' || ($coaRecord['jns_penerbitan'] ?? null) === 2) {
        $jenisBadgeClass = 'bg-light-warning text-warning';
    } elseif (($coaRecord['jns_penerbitan'] ?? '') === '9' || ($coaRecord['jns_penerbitan'] ?? null) === 9) {
        $jenisBadgeClass = 'bg-light-danger text-danger';
    }
@endphp

<style>
    .coa-view-card {
        border: 1px solid #e7edf3;
        box-shadow: 0 0.35rem 0.9rem rgba(27, 39, 51, 0.04);
    }

    .coa-view-muted {
        color: #6c7a89;
        font-size: 10px;
    }

    .coa-view-label {
        color: #6c7a89;
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.04em;
        margin-bottom: 0.35rem;
    }

    .coa-view-value {
        font-size: 15px;
        font-weight: 600;
        color: #22313f;
    }

    .coa-view-table td,
    .coa-view-table th {
        vertical-align: top;
    }

    .coa-view-soft {
        border: 1px dashed #cad5df;
        background: #fbfdff;
    }
</style>


<div class="card">
    <div class="card-body">
        <h5 class="card-title fw-semibold mb-3">{{ $page_title }}</h5>
        <ul class="nav tabs-menu-form">
            <li>
                <a href="#coaSummaryTab" data-bs-toggle="tab" class="active" id="buttonUmum">
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
                <a href="#coaDetailTab" data-bs-toggle="tab" id="buttonDetailCoa">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-table-options" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 21h-7a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v3.5"/>
                        <path d="M3 10h18"/>
                        <path d="M10 3v18"/>
                        <path d="M19 17l3 3l-3 3"/>
                        <path d="M16 20h6"/>
                    </svg>
                    Detail COA
                </a>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <div class="tab-pane active" id="coaSummaryTab">
                <div class="card coa-view-card mb-4">
                    <div class="card-header bg-primary text-white">
                        <div class="fw-semibold">Ringkasan Header</div>
                        <div class="coa-view-muted text-white">Data tersimpan ditampilkan dalam mode baca.</div>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-lg-3 col-md-6">
                                <div class="coa-view-label">Jenis Penerbitan</div>
                                <div class="d-flex align-items-center gap-2 flex-wrap">
                                    <div class="coa-view-value">{{ ($coaRecord['jns_penerbitan'] ?? '-') . ' : ' . $jenisPenerbitanLabel }}</div>
                                    <span class="badge {{ $jenisBadgeClass }}">{{ $jenisPenerbitanLabel }}</span>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="coa-view-label">Nomor COA</div>
                                <div class="coa-view-value">{{ $coaRecord['nomor_coa'] ?? '-' }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="coa-view-label">Tanggal COA</div>
                                <div class="coa-view-value">{{ $formatDate($coaRecord['tgl_coa'] ?? '') }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="coa-view-label">Tanggal Periksa</div>
                                <div class="coa-view-value">{{ $formatDate($coaRecord['tgl_periksa'] ?? '') }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="coa-view-label">Nomor LS</div>
                                <div class="coa-view-value">{{ $coaRecord['no_ls'] ?? '-' }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="coa-view-label">Tanggal LS</div>
                                <div class="coa-view-value">{{ $formatDate($coaRecord['tgl_ls'] ?? '') }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="coa-view-label">Kode LS</div>
                                <div class="coa-view-value">{{ $coaRecord['kode_ls'] ?? '-' }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="coa-view-label">Status</div>
                                <div>{!! $statusBadge !!}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="coa-view-label">NIB</div>
                                <div class="coa-view-value">{{ $coaRecord['nib'] ?? '-' }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="coa-view-label">NPWP</div>
                                <div class="coa-view-value">{{ $coaRecord['npwp'] ?? '-' }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="coa-view-label">NITKU</div>
                                <div class="coa-view-value">{{ $coaRecord['nitku'] ?? '-' }}</div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="coa-view-label">Nama Perusahaan</div>
                                <div class="coa-view-value">{{ $coaRecord['nama_perusahaan'] ?? '-' }}</div>
                            </div>
                            <div class="col-lg-6">
                                <div class="coa-view-label">Info Input</div>
                                <div class="p-3 rounded-3 bg-light">{!! $infoCell !!}</div>
                            </div>
                            <div class="col-lg-6">
                                <div class="coa-view-label">File COA</div>
                                @if (!empty($coaFileUrl))
                                    <a href="{{ $coaFileUrl }}" target="_blank" class="btn btn-info btn-sm">
                                        <i class="bx bx-show-alt me-1"></i>Lihat File
                                    </a>
                                @else
                                    <div class="coa-view-value">Belum ada file</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                @if (($canCreatePerubahan || $canCreatePembatalan) && !empty($coaRecordId))
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const perubahanButton = document.getElementById('btn-create-perubahan-coa');
                            const pembatalanButton = document.getElementById('btn-create-pembatalan-coa');

                            function sendPembatalan(id) {
                                const formData = new FormData();
                                formData.append('id', id);

                                postAjax(baseurl + 'ekspor/coa/pembatalan', formData, function (resp) {
                                    showAlert(resp);
                                    if (resp.code === '00') {
                                        window.location.replace(baseurl + 'ekspor/coa');
                                    }
                                });
                            }

                            if (perubahanButton) {
                                perubahanButton.addEventListener('click', function () {
                                    const id = this.dataset.id || '';
                                    if (!id) {
                                        return;
                                    }

                                    swal_confirm('Konfirmasi Perubahan', 'Buat draft perubahan dari data COA ini?', function (confirm) {
                                        if (!confirm) {
                                            return;
                                        }

                                        const formData = new FormData();
                                        formData.append('id', id);

                                        postAjax(baseurl + 'ekspor/coa/perubahan', formData, function (resp) {
                                            showAlert(resp);

                                            if (resp.code === '00' && resp.data && resp.data.id) {
                                                $.redirect(baseurl + 'ekspor/coa/edit', { id: resp.data.id, csrf_appls: csrfName }, 'POST', '_self');
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

                                    swal_confirm('Konfirmasi Pembatalan', 'Kirim pembatalan untuk data COA ini ke Inatrade?', function (confirm) {
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

            <div class="tab-pane" id="coaDetailTab">
                <div class="card coa-view-card">
                    <div class="card-header bg-primary text-white">
                        <div class="fw-semibold">Data Komoditas & COA</div>
                        <div class="coa-view-muted text-white">Menampilkan komoditas tersimpan beserta data COA, spec, dan param.</div>
                    </div>
                    <div class="card-body">
                        @forelse (($coaRecord['komoditas'] ?? []) as $commodityIndex => $commodity)
                            <div class="card coa-view-soft mb-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                                        <div>
                                            <div class="fw-semibold fs-6">Komoditas {{ $commodityIndex + 1 }}</div>
                                            <div class="coa-view-muted">{{ $commodity['ur_barang'] ?? '-' }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="coa-view-label mb-0">Jumlah / Satuan</div>
                                            <div class="coa-view-value">{{ $formatNumber($commodity['jml_barang'] ?? '') }} {{ $commodity['satuan_label'] ?? ($commodity['satuan'] ?? '') }}</div>
                                        </div>
                                    </div>

                                    <div class="row g-3 mb-4">
                                        <div class="col-lg-6">
                                            <div class="coa-view-label">Uraian Barang</div>
                                            <div>{{ $commodity['ur_barang'] ?? '-' }}</div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="coa-view-label">Spesifikasi</div>
                                            <div>{{ $commodity['spesifikasi'] ?? '-' }}</div>
                                        </div>
                                    </div>

                                    @forelse (($commodity['coa'] ?? []) as $groupIndex => $group)
                                        <div class="card border mb-3">
                                            <div class="card-header bg-light">
                                                <div class="fw-semibold">{{ count($commodity['coa'] ?? []) > 1 ? 'Data COA ' . ($groupIndex + 1) : 'Data COA' }}</div>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-4">
                                                    <div class="col-xl-8">
                                                        <div class="fw-semibold mb-3">Spec</div>
                                                        @forelse (($group['spec'] ?? []) as $specIndex => $spec)
                                                            <div class="table-responsive mb-3">
                                                                <table class="table table-sm table-bordered coa-view-table">
                                                                    <tbody>
                                                                        @foreach ($specLabels as $field => $label)
                                                                            <tr>
                                                                                <th class="bg-light" style="width: 40%;">{{ $label }}</th>
                                                                                <td>{{ $formatNumber($spec[$field] ?? '') }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @empty
                                                            <div class="text-muted">Belum ada data spec.</div>
                                                        @endforelse
                                                    </div>
                                                    <div class="col-xl-4">
                                                        <div class="fw-semibold mb-3">Param</div>
                                                        @forelse (($group['param'] ?? []) as $paramIndex => $param)
                                                            <div class="table-responsive mb-3">
                                                                <table class="table table-sm table-bordered coa-view-table">
                                                                    <tbody>
                                                                        @foreach ($paramLabels as $field => $label)
                                                                            <tr>
                                                                                <th class="bg-light" style="width: 45%;">{{ $label }}</th>
                                                                                <td>{{ $formatNumber($param[$field] ?? '') }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        @empty
                                                            <div class="text-muted">Belum ada data param.</div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-muted">Belum ada data COA untuk komoditas ini.</div>
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <div class="text-muted">Belum ada komoditas yang tersimpan.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mt-2 text-center">
            <a href="{{ base_url('ekspor/coa') }}" class="btn btn-dark btn-sm">
                <i class="bx bx-arrow-back me-1"></i>Kembali
            </a>
            @if ($canCreatePerubahan && !empty($coaRecordId))
                <button type="button" class="btn btn-success btn-sm my-1" id="btn-create-perubahan-coa" data-id="{{ $coaRecordId }}">
                    <i class="bx bx-git-compare me-1"></i>Perubahan
                </button>
            @endif
            @if ($canCreatePembatalan && !empty($coaRecordId))
                <button type="button" class="btn btn-danger btn-sm my-1" id="btn-create-pembatalan-coa" data-id="{{ $coaRecordId }}">
                    <i class="bx bx-x-circle me-1"></i>Pembatalan
                </button>
            @endif
        </div>
    </div>
</div>
