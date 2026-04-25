<?php

namespace App\Controllers\Internal\Lse\Batubara;

use App\Controllers\BaseController;
use Throwable;

class Coa extends BaseController
{
    protected array $jenisPenerbitan = [
        '1' => 'Baru',
        '2' => 'Perubahan',
        '9' => 'Pembatalan',
    ];

    protected array $specFields = [
        'ash_arb',
        'ash_adb',
        'tm_arb',
        'inh_adb',
        'tsulf_arb',
        'tsulf_adb',
        'vol_matter',
        'fix_carb',
        'size_0',
        'size_50',
        'hgi',
    ];

    protected array $paramFields = [
        'gcv_arb',
        'gcv_adb',
        'ncv_arb',
    ];

    public function index()
    {
        $page = [
            'table_title' => 'Data COA Batubara',
            'breadcrumb_active' => 'COA Batubara',
        ];

        $param['content'] = $this->render('ekspor.batubara.coa.index', $page);
        $param['addJS'] = '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/datatable/js/dataTables.buttons.min.js"></script>'; 
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/datatable/dataTables.responsive.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/datatable/responsive.bootstrap5.min.js"></script>';
        
        $param['addJS'] .= '<script src="' . base_url() . '/js/ekspor/coal/coa.js?v=' . date('YmdHis') . '"></script>';

        return $this->render('layout.template', $param);
    }

    public function input()
    {
        return $this->renderForm();
    }

    public function edit()
    {
        try {
            $id = decrypt_id($this->request->getPost('id'));
            $record = $this->findRecord($id);

            if (empty($record)) {
                return redirect()->to(base_url('ekspor/coa'));
            }

            if ($this->resolveStatusKirim($record) === 'SENT') {
                return redirect()->to(base_url('ekspor/coa'));
            }

            return $this->renderForm($record);
        } catch (Throwable $e) {
            return redirect()->to(base_url('ekspor/coa'));
        }
    }

    public function view()
    {
        try {
            $id = decrypt_id($this->request->getPost('id'));
            $record = $this->findRecord($id);

            if (empty($record)) {
                return redirect()->to(base_url('ekspor/coa'));
            }

            return $this->renderReadonly($record);
        } catch (Throwable $e) {
            return redirect()->to(base_url('ekspor/coa'));
        }
    }

    public function create_perubahan()
    {
        try {
            $id = decrypt_id($this->request->getPost('id'));
            $record = $this->findRecord($id);

            if (empty($record)) {
                return $this->response->setJSON(resp_error('Data COA tidak ditemukan.'));
            }

            if ($this->resolveStatusKirim($record) !== 'SENT') {
                return $this->response->setJSON(resp_error('Perubahan hanya dapat dibuat dari data COA yang sudah terkirim.'));
            }

            if (strtoupper((string) ($record['isPembatalan'] ?? 'N')) === 'Y') {
                return $this->response->setJSON(resp_error('Data COA yang sudah dibatalkan tidak dapat dibuat perubahan.'));
            }

            if ($this->hasChildDocument($id)) {
                return $this->response->setJSON(resp_error('Draft perubahan hanya dapat dibuat dari data COA terkirim terbaru.'));
            }

            $schemaValidation = $this->validatePersistenceSchema();
            if ($schemaValidation !== true) {
                return $this->response->setJSON(resp_error($schemaValidation));
            }

            $headerData = [
                'parent_id' => $id,
                'jns_penerbitan' => 2,
                'nib' => $record['nib'] ?? '',
                'npwp' => $record['npwp'] ?? '',
                'nitku' => $record['nitku'] ?? '',
                'nama_perusahaan' => $record['nama_perusahaan'] ?? '',
                'no_ls' => $record['no_ls'] ?? '',
                'tgl_ls' => $record['tgl_ls'] ?? null,
                'kode_ls' => $record['kode_ls'] ?? '',
                'nomor_coa' => $record['nomor_coa'] ?? '',
                'tgl_coa' => $record['tgl_coa'] ?? null,
                'tgl_periksa' => $record['tgl_periksa'] ?? null,
                'url_coa' => '',
                'pathFile' => null,
                'username' => session()->get('sess_username') ?: ($record['username'] ?? ''),
                'statusKirim' => 'DRAFT',
                'waktuKirim' => null,
            ];

            $this->db->transStart();
            $this->db->table('tx_coa')->insert($headerData);
            $newCoaId = (int) $this->db->insertID();
            $this->persistKomoditas($newCoaId, $record['komoditas'] ?? []);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON(resp_error($this->buildDbErrorMessage('Gagal membuat draft perubahan COA.')));
            }

            return $this->response->setJSON(resp_success('Draft perubahan COA berhasil dibuat.', [
                'id' => encrypt_id($newCoaId),
            ]));
        } catch (Throwable $e) {
            return $this->response->setJSON(resp_error('Gagal membuat draft perubahan COA. ' . $e->getMessage()));
        }
    }

    public function create_pembatalan()
    {
        return $this->response->setJSON(resp_error('Aksi pembatalan COA diproses langsung melalui endpoint pengiriman.'));
    }

    public function list()
    {
        $searchParam = $this->request->getPost('searchParam');
        $arrParam = !empty($searchParam) ? post_ajax_toarray($searchParam) : [];

        $builder = $this->db->table('tx_coa c');
        $builder->select('c.*');
        $builder->select('COUNT(DISTINCT k.id) AS komoditas_count', false);
        $builder->select('(SELECT k2.ur_barang FROM tx_coa_komoditas k2 WHERE k2.tx_coa_id = c.id ORDER BY k2.id ASC LIMIT 1) AS first_komoditas', false);
        $builder->select('(SELECT COUNT(*) FROM tx_coa_group g JOIN tx_coa_komoditas kg ON kg.id = g.tx_coa_komoditas_id WHERE kg.tx_coa_id = c.id) AS group_count', false);
        $builder->select('(SELECT COUNT(*) FROM tx_coa c2 WHERE c2.parent_id = c.id) AS child_count', false);
        $builder->select('(SELECT COUNT(*) FROM tx_coa c2 WHERE c2.parent_id = c.id AND c2.jns_penerbitan = 2) AS perubahan_child_count', false);
        $builder->join('tx_coa_komoditas k', 'k.tx_coa_id = c.id', 'left');

        if (!empty($arrParam['namaPerusahaan'])) {
            $builder->like('c.nama_perusahaan', $arrParam['namaPerusahaan']);
        }

        if (!empty($arrParam['noLs'])) {
            $builder->like('c.no_ls', $arrParam['noLs']);
        }

        if (!empty($arrParam['nomorCoa'])) {
            $builder->like('c.nomor_coa', $arrParam['nomorCoa']);
        }

        $builder->groupBy('c.id');

        $recordsTotal = $this->db->table('tx_coa')->countAllResults();
        $recordsFiltered = count((clone $builder)->get()->getResultArray());

        $start = (int) ($this->request->getPost('start') ?? 0);
        $length = (int) ($this->request->getPost('length') ?? 10);

        $builder->orderBy('c.updated_at', 'DESC');
        if ($length > 0) {
            $builder->limit($length, $start);
        }

        $records = $builder->get()->getResultArray();

        $rows = [];
        $no = $start + 1;

        foreach ($records as $record) {
            $komoditasCount = (int) ($record['komoditas_count'] ?? 0);
            $coaCount = (int) ($record['group_count'] ?? 0);
            $firstKomoditas = $record['first_komoditas'] ?? '-';
            $statusKirim = $this->resolveStatusKirim($record);
            $hasChildDocument = (int) ($record['child_count'] ?? 0) > 0;
            $hasPerubahanChild = (int) ($record['perubahan_child_count'] ?? 0) > 0;

            $btnView = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-info me-1 mb-1" onclick="viewData(\'' . encrypt_id($record['id']) . '\')" title="Lihat"><i class="fa fa-eye"></i></button> ';
            $actionButtons = [$btnView];

            if (in_array($statusKirim, ['DRAFT', 'FAILED'], true)) {
                $sendTitle = $statusKirim === 'FAILED' ? 'Kirim Ulang' : 'Kirim';
                $actionButtons[] = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-primary me-1 mb-1" onclick="sendCoa(\'' . encrypt_id($record['id']) . '\')" title="' . $sendTitle . '"><i class="fa fa-send"></i></button> ';
                $actionButtons[] = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning me-1 mb-1" onclick="edit(\'' . encrypt_id($record['id']) . '\')" title="Edit"><i class="fa fa-edit"></i></button> ';
                $actionButtons[] = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger mb-1" onclick="del(\'' . encrypt_id($record['id']) . '\')" title="Hapus"><i class="fa fa-trash"></i></button>';
            } elseif ($statusKirim === 'SENT' && !$hasChildDocument && strtoupper((string) ($record['isPembatalan'] ?? 'N')) !== 'Y' && (string) ($record['jns_penerbitan'] ?? '') !== '9') {
                $actionButtons[] = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger mb-1" onclick="cancelCoa(\'' . encrypt_id($record['id']) . '\')" title="Pembatalan"><i class="fa fa-ban"></i></button>';
            }

            $columns = [];
            $columns[] = $no++;
            $jenisPenerbitan = $this->jenisPenerbitan[(string) ($record['jns_penerbitan'] ?? '')] ?? '-';
            $badgeClass = 'bg-light-secondary text-secondary';
            if (($record['jns_penerbitan'] ?? '') === '1' || ($record['jns_penerbitan'] ?? null) === 1) {
                $badgeClass = 'bg-light-success text-success';
            } elseif (($record['jns_penerbitan'] ?? '') === '2' || ($record['jns_penerbitan'] ?? null) === 2) {
                $badgeClass = 'bg-light-warning text-warning';
            } elseif (($record['jns_penerbitan'] ?? '') === '9' || ($record['jns_penerbitan'] ?? null) === 9) {
                $badgeClass = 'bg-light-danger text-danger';
            }
            $columns[] = '<div class="d-flex align-items-center gap-2 flex-wrap"><span class="fw-semibold">' . $this->escape($record['nomor_coa'] ?? '-') . '</span><span class="badge ' . $badgeClass . '">' . $this->escape($jenisPenerbitan) . '</span></div><span>No. LS: ' . $this->escape($record['no_ls'] ?? '-') . '</span><br><span>Tgl COA: ' . $this->formatDate($record['tgl_coa'] ?? '') . '</span>';
            $columns[] = '<span class="fw-semibold">' . $this->escape($record['nama_perusahaan'] ?? '-') . '</span><br><span>NIB: ' . $this->escape($record['nib'] ?? '-') . '</span><br><span>NPWP: ' . $this->escape($record['npwp'] ?? '-') . '</span>';
            $columns[] = '<span class="fw-semibold">' . $komoditasCount . ' komoditas</span><br><span>' . $this->escape($firstKomoditas) . '</span><br><span>' . $coaCount . ' grup COA</span>';
            $columns[] = $this->renderInfoCell($record['username'] ?? '', $record['created_at'] ?? '');
            $columns[] = $this->renderStatusBadge($statusKirim, $record['waktuKirim'] ?? '', $hasPerubahanChild, strtoupper((string) ($record['isPembatalan'] ?? 'N')) === 'Y');
            $columns[] = '<div class="btn-list text-nowrap">' . implode('', $actionButtons) . '</div>';

            $rows[] = $columns;
        }

        return $this->response->setJSON([
            'draw' => $this->request->getPost('draw'),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $rows,
        ]);
    }

    public function save()
    {
        try {
            $postdata = $this->request->getPost('postdata');
            $payload = json_decode((string) $postdata, true);

            if (!is_array($payload)) {
                return $this->response->setJSON(resp_error('Payload COA tidak valid.'));
            }

            $payload = $this->normalizeIncomingPayload($payload);
            $headerId = !empty($payload['idData']) ? decrypt_id($payload['idData']) : null;
            $existingRecord = !empty($headerId)
                ? $this->db->table('tx_coa')->where('id', $headerId)->get()->getRowArray()
                : [];

            if (!empty($existingRecord) && $this->resolveStatusKirim($existingRecord) === 'SENT') {
                return $this->response->setJSON(resp_error('Data COA yang sudah terkirim tidak dapat diubah.'));
            }

            $uploadResult = $this->handleUploadedFile($existingRecord);
            if ($uploadResult['status'] !== true) {
                return $this->response->setJSON(resp_error($uploadResult['message']));
            }

            $payload['url_coa'] = $uploadResult['url_coa'];
            $payload['pathFile'] = $uploadResult['pathFile'];

            $payload = $this->sanitizePayload($payload);
            if (!empty($existingRecord)) {
                $payload['jns_penerbitan'] = (string) ($existingRecord['jns_penerbitan'] ?? $payload['jns_penerbitan']);

                if ((string) ($existingRecord['jns_penerbitan'] ?? '') === '2') {
                    $payload['nomor_coa'] = (string) ($existingRecord['nomor_coa'] ?? $payload['nomor_coa']);
                    $payload['tgl_coa'] = (string) ($existingRecord['tgl_coa'] ?? $payload['tgl_coa']);
                }
            }
            $validation = $this->validatePayload($payload, $headerId);

            if ($validation !== true) {
                return $this->response->setJSON(resp_error($validation));
            }

            $schemaValidation = $this->validatePersistenceSchema();
            if ($schemaValidation !== true) {
                return $this->response->setJSON(resp_error($schemaValidation));
            }

            $headerData = $this->buildHeaderData($payload);

            $this->db->transStart();

            if (!empty($headerId)) {
                $this->db->table('tx_coa')->where('id', $headerId)->update($headerData);
                $this->deleteChildRecords($headerId);
                $coaId = $headerId;
            } else {
                $headerData['statusKirim'] = 'DRAFT';
                $headerData['waktuKirim'] = null;
                $this->db->table('tx_coa')->insert($headerData);
                $coaId = (int) $this->db->insertID();
            }

            $this->persistKomoditas($coaId, $payload['komoditas']);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON(resp_error($this->buildDbErrorMessage('Gagal menyimpan data COA ke database.')));
            }

            return $this->response->setJSON(resp_success('Data COA berhasil disimpan.', [
                'id' => encrypt_id($coaId),
            ]));
        } catch (Throwable $e) {
            return $this->response->setJSON(resp_error('Gagal menyimpan data COA. ' . $e->getMessage()));
        }
    }

    public function delete()
    {
        try {
            $id = decrypt_id($this->request->getPost('id'));
            $header = $this->db->table('tx_coa')->where('id', $id)->get()->getRowArray();

            if (empty($header)) {
                return $this->response->setJSON(resp_error('Data COA tidak ditemukan.'));
            }

            if ($this->resolveStatusKirim($header) === 'SENT') {
                return $this->response->setJSON(resp_error('Data COA yang sudah terkirim tidak dapat dihapus.'));
            }

            $this->db->transStart();
            $this->deleteChildRecords($id);
            $this->db->table('tx_coa')->where('id', $id)->delete();
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON(resp_error($this->buildDbErrorMessage('Gagal menghapus data COA dari database.')));
            }

            return $this->response->setJSON(resp_success('Data COA berhasil dihapus.'));
        } catch (Throwable $e) {
            return $this->response->setJSON(resp_error('Gagal menghapus data COA. ' . $e->getMessage()));
        }
    }

    public function delete_file()
    {
        try {
            $id = decrypt_id($this->request->getPost('id'));
            $record = $this->db->table('tx_coa')->where('id', $id)->get()->getRowArray();

            if (empty($record)) {
                return $this->response->setJSON(resp_error('Data COA tidak ditemukan.'));
            }

            if ($this->resolveStatusKirim($record) === 'SENT') {
                return $this->response->setJSON(resp_error('File COA pada data yang sudah terkirim tidak dapat dihapus.'));
            }

            if (!empty($record['pathFile']) && is_file(WRITEPATH . 'uploads/' . $record['pathFile'])) {
                unlink(WRITEPATH . 'uploads/' . $record['pathFile']);
            }

            $updated = $this->db->table('tx_coa')
                ->where('id', $id)
                ->set('pathFile', null)
                ->update();

            if (!$updated) {
                return $this->response->setJSON(resp_error($this->buildDbErrorMessage('File COA gagal dihapus.')));
            }

            return $this->response->setJSON(resp_success('File COA berhasil dihapus.'));
        } catch (Throwable $e) {
            return $this->response->setJSON(resp_error('Gagal menghapus file COA. ' . $e->getMessage()));
        }
    }

    public function lsSuggestions()
    {
        $keyword = trim((string) $this->request->getPost('q'));

        if ($keyword === '') {
            return $this->response->setJSON([
                'data' => [],
            ]);
        }

        $rows = $this->db->table('tx_lsehdr h')
            ->select('h.noLs')
            ->like('h.noLs', $keyword)
            ->orderBy('h.tglLs', 'DESC')
            ->orderBy('h.id', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();

        $results = [];
        foreach ($rows as $row) {
            if (empty($row['noLs'])) {
                continue;
            }

            $results[] = [
                'value' => $row['noLs'],
                'label' => $row['noLs'],
            ];
        }

        return $this->response->setJSON([
            'data' => $results,
        ]);
    }

    public function lsReference()
    {
        $noLs = trim((string) $this->request->getPost('no_ls'));

        if ($noLs === '') {
            return $this->response->setJSON(resp_error('Nomor LS wajib diisi.'));
        }

        $row = $this->db->table('tx_lsehdr h')
            ->select("
                h.noLs,
                h.tglLs,
                h.nib,
                COALESCE(NULLIF(h.npwp16, ''), h.npwp) AS npwp,
                h.nitku,
                h.namaPersh AS nama_perusahaan,
                j.kodeinatrade AS kode_ls
            ", false)
            ->join('m_jenisls j', 'j.id = h.idJenisLS', 'left')
            ->where('h.noLs', $noLs)
            ->orderBy('h.id', 'DESC')
            ->get()
            ->getRowArray();

        if (empty($row)) {
            return $this->response->setJSON(resp_error('Nomor LS tidak ditemukan.'));
        }

        return $this->response->setJSON(resp_success('Data LS ditemukan.', [
            'no_ls' => $row['noLs'] ?? '',
            'tgl_ls' => $this->formatDateForInput($row['tglLs'] ?? ''),
            'kode_ls' => $row['kode_ls'] ?? '',
            'nib' => $row['nib'] ?? '',
            'npwp' => $row['npwp'] ?? '',
            'nitku' => $row['nitku'] ?? '',
            'nama_perusahaan' => $row['nama_perusahaan'] ?? '',
        ]));
    }

    private function renderForm(array $record = [])
    {
        $page = [
            'page_title' => empty($record) ? 'Input COA' : 'Edit COA',
            'coaData' => $this->prepareFormData($record),
            'coaNestedDataJson' => json_encode(
                $record['komoditas'] ?? [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
            ),
        ];

        $param['addJS'] = '<script>window.coaNestedData = ' . $page['coaNestedDataJson'] . ';</script>';
        $param['addJS'] .= '<script src="' . base_url() . '/js/ekspor/coal/coa-input.js?v=' . date('YmdHis') . '"></script>';
        $param['content'] = $this->render('ekspor.batubara.coa.input', $page);

        return $this->render('layout.template', $param);
    }

    private function renderReadonly(array $record)
    {
        $page = [
            'page_title' => 'Lihat COA',
            'coaRecord' => $record,
            'coaRecordId' => !empty($record['id']) ? encrypt_id($record['id']) : '',
            'jenisPenerbitanLabel' => $this->jenisPenerbitan[(string) ($record['jns_penerbitan'] ?? '')] ?? '-',
            'statusKirim' => $this->resolveStatusKirim($record),
            'statusBadge' => $this->renderStatusBadge(
                $this->resolveStatusKirim($record),
                $record['waktuKirim'] ?? '',
                $this->hasPerubahanChild((int) ($record['id'] ?? 0)),
                strtoupper((string) ($record['isPembatalan'] ?? 'N')) === 'Y'
            ),
            'infoCell' => $this->renderInfoCell($record['username'] ?? '', $record['created_at'] ?? ''),
            'coaFileUrl' => !empty($record['url_coa']) ? base_url('doc/coa/' . $record['url_coa']) : '',
            'canCreatePerubahan' => $this->canCreatePerubahan($record),
            'canCreatePembatalan' => $this->canCreatePembatalan($record),
        ];

        $param['content'] = $this->render('ekspor.batubara.coa.view', $page);

        return $this->render('layout.template', $param);
    }

    private function prepareFormData(array $record): array
    {
            return [
                'idData' => !empty($record['id']) ? encrypt_id($record['id']) : '',
                'jns_penerbitan' => (string) ($record['jns_penerbitan'] ?? '1'),
                'nib' => $record['nib'] ?? '',
                'npwp' => $record['npwp'] ?? '',
            'nitku' => $record['nitku'] ?? '',
            'nama_perusahaan' => $record['nama_perusahaan'] ?? '',
            'no_ls' => $record['no_ls'] ?? '',
            'tgl_ls' => $this->formatDateForInput($record['tgl_ls'] ?? ''),
            'kode_ls' => $record['kode_ls'] ?? '',
            'nomor_coa' => $record['nomor_coa'] ?? '',
            'tgl_coa' => $this->formatDateForInput($record['tgl_coa'] ?? ''),
            'tgl_periksa' => $this->formatDateForInput($record['tgl_periksa'] ?? ''),
            'url_coa' => $record['url_coa'] ?? '',
            'path_file' => $record['pathFile'] ?? '',
            'file_name' => !empty($record['pathFile']) ? basename((string) $record['pathFile']) : '',
        ];
    }

    private function findRecord(int $id): array
    {
        $header = $this->db->table('tx_coa')->where('id', $id)->get()->getRowArray();
        if (empty($header)) {
            return [];
        }

        $komoditasRows = $this->db->table('tx_coa_komoditas')
            ->where('tx_coa_id', $id)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        $komoditasIds = array_column($komoditasRows, 'id');
        $groupMap = $this->loadGroupsByKomoditas($komoditasIds);
        $groupIds = [];
        foreach ($groupMap as $groups) {
            foreach ($groups as $group) {
                $groupIds[] = $group['id'];
            }
        }
        $specMap = $this->loadSpecsByGroup($groupIds);
        $paramMap = $this->loadParamsByGroup($groupIds);

        $komoditas = [];
        foreach ($komoditasRows as $komoditasRow) {
            $groups = [];
            foreach (($groupMap[$komoditasRow['id']] ?? []) as $groupRow) {
                $groups[] = [
                    'spec' => $specMap[$groupRow['id']] ?? [],
                    'param' => $paramMap[$groupRow['id']] ?? [],
                ];
            }

            $komoditas[] = [
                'ur_barang' => $komoditasRow['ur_barang'] ?? '',
                'spesifikasi' => $komoditasRow['spesifikasi_barang'] ?? '',
                'jml_barang' => $this->formatDecimal($komoditasRow['jml_barang'] ?? null),
                'satuan' => $komoditasRow['satuan'] ?? '',
                'satuan_label' => $this->resolveSatuanLabel($komoditasRow['satuan'] ?? ''),
                'coa' => $groups,
            ];
        }

        $header['komoditas'] = $komoditas;
        return $header;
    }

    private function resolveSatuanLabel(string $kodeSatuan): string
    {
        $kodeSatuan = trim($kodeSatuan);
        if ($kodeSatuan === '') {
            return '';
        }

        $row = model('satuan')->where('kodeSatuan', $kodeSatuan)->first();
        if (is_array($row)) {
            return $row['uraiSatuan'] ?? $kodeSatuan;
        }

        if (is_object($row)) {
            return $row->uraiSatuan ?? $kodeSatuan;
        }

        return $kodeSatuan;
    }

    private function loadGroupsByKomoditas(array $komoditasIds): array
    {
        if (empty($komoditasIds)) {
            return [];
        }

        $rows = $this->db->table('tx_coa_group')
            ->whereIn('tx_coa_komoditas_id', $komoditasIds)
            ->orderBy('no_group', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        $map = [];
        foreach ($rows as $row) {
            $map[$row['tx_coa_komoditas_id']][] = $row;
        }

        return $map;
    }

    private function loadSpecsByGroup(array $groupIds): array
    {
        if (empty($groupIds)) {
            return [];
        }

        $rows = $this->db->table('tx_coa_spec')
            ->whereIn('tx_coa_group_id', $groupIds)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        $map = [];
        foreach ($rows as $row) {
            $map[$row['tx_coa_group_id']][] = $this->mapSpecRow($row);
        }

        return $map;
    }

    private function loadParamsByGroup(array $groupIds): array
    {
        if (empty($groupIds)) {
            return [];
        }

        $rows = $this->db->table('tx_coa_param')
            ->whereIn('tx_coa_group_id', $groupIds)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        $map = [];
        foreach ($rows as $row) {
            $map[$row['tx_coa_group_id']][] = $this->mapParamRow($row);
        }

        return $map;
    }

    private function buildHeaderData(array $payload): array
    {
        return [
            'jns_penerbitan' => (int) $payload['jns_penerbitan'],
            'nib' => $payload['nib'],
            'npwp' => $payload['npwp'],
            'nitku' => $payload['nitku'],
            'nama_perusahaan' => $payload['nama_perusahaan'],
            'no_ls' => $payload['no_ls'],
            'tgl_ls' => $this->normalizeDateForStorage($payload['tgl_ls']),
            'kode_ls' => $payload['kode_ls'],
            'nomor_coa' => $payload['nomor_coa'],
            'tgl_coa' => $this->normalizeDateForStorage($payload['tgl_coa']),
            'tgl_periksa' => $this->normalizeDateForStorage($payload['tgl_periksa']),
            'url_coa' => $payload['url_coa'],
            'pathFile' => $payload['pathFile'],
            'username' => session()->get('sess_username') ?: ($payload['username'] ?? ''),
            'isPembatalan' => $payload['isPembatalan'] ?? 'N',
        ];
    }

    private function persistKomoditas(int $coaId, array $komoditasList): void
    {
        foreach ($komoditasList as $komoditas) {
            $this->db->table('tx_coa_komoditas')->insert([
                'tx_coa_id' => $coaId,
                'ur_barang' => $komoditas['ur_barang'],
                'spesifikasi_barang' => $komoditas['spesifikasi'],
                'jml_barang' => $this->normalizeDecimal($komoditas['jml_barang']),
                'satuan' => $komoditas['satuan'],
            ]);

            $komoditasId = (int) $this->db->insertID();

            foreach (array_values($komoditas['coa'] ?? []) as $groupIndex => $coaGroup) {
                $this->db->table('tx_coa_group')->insert([
                    'tx_coa_komoditas_id' => $komoditasId,
                    'no_group' => $groupIndex + 1,
                ]);

                $coaGroupId = (int) $this->db->insertID();

                foreach (($coaGroup['spec'] ?? []) as $spec) {
                    $this->db->table('tx_coa_spec')->insert($this->buildSpecData($coaGroupId, $spec));
                }

                foreach (($coaGroup['param'] ?? []) as $param) {
                    $this->db->table('tx_coa_param')->insert($this->buildParamData($coaGroupId, $param));
                }
            }
        }
    }

    private function buildSpecData(int $groupId, array $spec): array
    {
        $data = [
            'tx_coa_group_id' => $groupId,
        ];

        foreach ($this->specFields as $field) {
            $data[$field] = $this->normalizeDecimal($spec[$field] ?? null);
        }

        return $data;
    }

    private function buildParamData(int $groupId, array $param): array
    {
        $data = [
            'tx_coa_group_id' => $groupId,
        ];

        foreach ($this->paramFields as $field) {
            $data[$field] = $this->normalizeDecimal($param[$field] ?? null);
        }

        return $data;
    }

    private function deleteChildRecords(int $coaId): void
    {
        $komoditasRows = $this->db->table('tx_coa_komoditas')
            ->select('id')
            ->where('tx_coa_id', $coaId)
            ->get()
            ->getResultArray();

        $komoditasIds = array_column($komoditasRows, 'id');

        if (!empty($komoditasIds)) {
            $groupRows = $this->db->table('tx_coa_group')
                ->select('id')
                ->whereIn('tx_coa_komoditas_id', $komoditasIds)
                ->get()
                ->getResultArray();
            $groupIds = array_column($groupRows, 'id');

            if (!empty($groupIds)) {
                $this->db->table('tx_coa_spec')->whereIn('tx_coa_group_id', $groupIds)->delete();
                $this->db->table('tx_coa_param')->whereIn('tx_coa_group_id', $groupIds)->delete();
            }

            $this->db->table('tx_coa_group')->whereIn('tx_coa_komoditas_id', $komoditasIds)->delete();
        }

        $this->db->table('tx_coa_komoditas')->where('tx_coa_id', $coaId)->delete();
    }

    private function sanitizePayload(array $payload): array
    {
        $fields = [
            'idData',
            'jns_penerbitan',
            'nib',
            'npwp',
            'nitku',
            'nama_perusahaan',
            'no_ls',
            'tgl_ls',
            'kode_ls',
            'nomor_coa',
            'tgl_coa',
            'tgl_periksa',
            'url_coa',
            'pathFile',
            'username',
        ];

        $data = [];
        foreach ($fields as $field) {
            $data[$field] = clean_string($payload[$field] ?? '');
        }

        $data['jns_penerbitan'] = (string) ($payload['jns_penerbitan'] ?? '1');
        $data['komoditas'] = [];

        foreach (($payload['komoditas'] ?? []) as $komoditas) {
            $komoditasRow = [
                'ur_barang' => clean_string($komoditas['ur_barang'] ?? ''),
                'spesifikasi' => clean_string($komoditas['spesifikasi'] ?? ''),
                'jml_barang' => clean_string($komoditas['jml_barang'] ?? ''),
                'satuan' => clean_string($komoditas['satuan'] ?? ''),
                'coa' => [],
            ];

            foreach (($komoditas['coa'] ?? []) as $coa) {
                $coaRow = [
                    'spec' => [],
                    'param' => [],
                ];

                foreach (($coa['spec'] ?? []) as $spec) {
                    $specRow = $this->sanitizeFixedGroup($spec, $this->specFields);
                    if ($this->groupHasValue($specRow)) {
                        $coaRow['spec'][] = $specRow;
                    }
                }

                foreach (($coa['param'] ?? []) as $param) {
                    $paramRow = $this->sanitizeFixedGroup($param, $this->paramFields);
                    if ($this->groupHasValue($paramRow)) {
                        $coaRow['param'][] = $paramRow;
                    }
                }

                if (!empty($coaRow['spec']) || !empty($coaRow['param'])) {
                    $komoditasRow['coa'][] = $coaRow;
                }
            }

            if ($this->groupHasValue($komoditasRow, ['coa']) || !empty($komoditasRow['coa'])) {
                $data['komoditas'][] = $komoditasRow;
            }
        }

        return $data;
    }

    private function sanitizeFixedGroup(array $group, array $fields): array
    {
        $row = [];
        foreach ($fields as $field) {
            $row[$field] = clean_string($group[$field] ?? '');
        }

        return $row;
    }

    private function groupHasValue(array $group, array $except = []): bool
    {
        foreach ($group as $key => $value) {
            if (in_array($key, $except, true)) {
                continue;
            }

            if (is_array($value) && !empty($value)) {
                return true;
            }

            if (!is_array($value) && $value !== null && $value !== '') {
                return true;
            }
        }

        return false;
    }

    private function validatePayload(array $payload, ?int $headerId = null)
    {
        $requiredFields = [
            'no_ls' => 'Nomor LS wajib diisi.',
        ];

        foreach ($requiredFields as $field => $message) {
            if (empty($payload[$field])) {
                return $message;
            }
        }

        if (($payload['jns_penerbitan'] ?? '') === '1' && !empty($payload['nomor_coa'])) {
            $builder = $this->db->table('tx_coa')
                ->where('jns_penerbitan', 1)
                ->where('nomor_coa', $payload['nomor_coa']);

            if (!empty($headerId)) {
                $builder->where('id !=', $headerId);
            }

            if ($builder->countAllResults() > 0) {
                return 'Nomor COA untuk jenis penerbitan Baru sudah digunakan.';
            }
        }

        return true;
    }

    private function validatePersistenceSchema()
    {
        $requirements = [
            'tx_coa' => [
                'parent_id',
                'jns_penerbitan',
                'nib',
                'npwp',
                'nitku',
                'nama_perusahaan',
                'no_ls',
                'tgl_ls',
                'kode_ls',
                'nomor_coa',
                'tgl_coa',
                'tgl_periksa',
                'url_coa',
                'pathFile',
                'username',
                'statusKirim',
                'waktuKirim',
                'isPembatalan',
            ],
            'tx_coa_komoditas' => [
                'tx_coa_id',
                'ur_barang',
                'spesifikasi_barang',
                'jml_barang',
                'satuan',
            ],
            'tx_coa_group' => [
                'tx_coa_komoditas_id',
                'no_group',
            ],
            'tx_coa_spec' => array_merge(['tx_coa_group_id'], $this->specFields),
            'tx_coa_param' => array_merge(['tx_coa_group_id'], $this->paramFields),
        ];

        foreach ($requirements as $table => $fields) {
            if (!$this->db->tableExists($table)) {
                return 'Tabel `' . $table . '` belum tersedia di database.';
            }

            foreach ($fields as $field) {
                if (!$this->db->fieldExists($field, $table)) {
                    return 'Kolom `' . $field . '` belum tersedia pada tabel `' . $table . '`.';
                }
            }
        }

        return true;
    }

    private function buildDbErrorMessage(string $fallback): string
    {
        $error = $this->db->error();

        if (!empty($error['message'])) {
            return $fallback . ' Detail DB: ' . $error['message'];
        }

        return $fallback;
    }

    private function normalizeIncomingPayload(array $payload): array
    {
        if (!isset($payload['header']) || !is_array($payload['header'])) {
            return $payload;
        }

        $normalized = $payload['header'];
        $normalized['idData'] = $payload['idData'] ?? ($payload['header']['idData'] ?? '');
        $komoditasInput = $payload['komoditas'] ?? [];
        if (!empty($komoditasInput) && array_keys($komoditasInput) !== range(0, count($komoditasInput) - 1)) {
            $komoditasInput = [$komoditasInput];
        }

        $topLevelCoa = $payload['coa'] ?? null;
        $normalized['komoditas'] = [];

        foreach ($komoditasInput as $komoditas) {
            $komoditasRow = [
                'ur_barang' => $komoditas['ur_barang'] ?? '',
                'spesifikasi' => $komoditas['spesifikasi'] ?? '',
                'jml_barang' => $komoditas['jml_barang'] ?? '',
                'satuan' => $komoditas['satuan'] ?? '',
            ];

            $coaSource = $komoditas['coa'] ?? $topLevelCoa ?? [];
            $komoditasRow['coa'] = $this->normalizeIncomingCoaGroups($coaSource);
            $normalized['komoditas'][] = $komoditasRow;
        }

        return $normalized;
    }

    private function normalizeIncomingCoaGroups($coaSource): array
    {
        if (empty($coaSource)) {
            return [];
        }

        $groups = $coaSource;
        if (isset($coaSource['spec']) || isset($coaSource['param'])) {
            $groups = [$coaSource];
        }

        $normalized = [];
        foreach ($groups as $group) {
            $specList = $group['spec'] ?? [];
            $paramList = $group['param'] ?? [];

            if (!empty($specList) && array_keys($specList) !== range(0, count($specList) - 1)) {
                $specList = [$specList];
            }

            if (!empty($paramList) && array_keys($paramList) !== range(0, count($paramList) - 1)) {
                $paramList = [$paramList];
            }

            $normalized[] = [
                'spec' => $specList,
                'param' => $paramList,
            ];
        }

        return $normalized;
    }

    private function handleUploadedFile(array $existingRecord = []): array
    {
        $fileUpload = $this->request->getFile('file_coa');
        $existingUrlToken = $existingRecord['url_coa'] ?? '';

        if ($fileUpload !== null && $fileUpload->isValid() && !$fileUpload->hasMoved()) {
            $validationRule = [
                'file_coa' => [
                    'rules' => [
                        'uploaded[file_coa]',
                        'mime_in[file_coa,image/jpg,image/jpeg,image/png,image/webp,image/gif,application/pdf]',
                        'max_size[file_coa,5120]',
                    ],
                    'errors' => [
                        'uploaded' => 'File COA belum dipilih.',
                        'mime_in' => 'File COA harus berupa image atau PDF dengan format JPG/JPEG/PNG/WEBP/GIF/PDF.',
                        'max_size' => 'Ukuran file COA maksimal 5 MB.',
                    ],
                ],
            ];

            if (!$this->validate($validationRule)) {
                return [
                    'status' => false,
                    'message' => $this->validator->getError('file_coa'),
                ];
            }

            $pathFile = $fileUpload->store('coa/' . date('Ym'));

            return [
                'status' => true,
                'pathFile' => $pathFile,
                'url_coa' => !empty($existingUrlToken) ? $existingUrlToken : $this->generateFileToken($pathFile),
            ];
        }

        if (!empty($existingRecord['pathFile']) && !empty($existingRecord['url_coa'])) {
            return [
                'status' => true,
                'pathFile' => $existingRecord['pathFile'],
                'url_coa' => $existingRecord['url_coa'],
            ];
        }

        return [
            'status' => true,
            'pathFile' => $existingRecord['pathFile'] ?? '',
            'url_coa' => $existingUrlToken,
        ];
    }

    private function generateFileToken(string $pathFile): string
    {
        return md5($pathFile . microtime(true) . random_int(1000, 9999));
    }

    private function normalizeDateForStorage(?string $date)
    {
        $date = trim((string) $date);
        if ($date === '') {
            return '';
        }

        return $this->normalizeDate($date);
    }

    private function normalizeDate(?string $date): ?string
    {
        $date = trim((string) $date);
        if ($date === '') {
            return null;
        }

        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $date) === 1) {
            [$day, $month, $year] = explode('-', $date);
            return $year . '-' . $month . '-' . $day;
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) === 1) {
            return $date;
        }

        return null;
    }

    private function formatDate(?string $date): string
    {
        $normalized = $this->normalizeDate($date);
        return empty($normalized) ? '-' : reverseDate($normalized);
    }

    private function formatDateTime(?string $datetime): string
    {
        $datetime = trim((string) $datetime);
        if ($datetime === '') {
            return '-';
        }

        $timestamp = strtotime($datetime);

        return $timestamp === false ? $this->escape($datetime) : date('d-m-Y H:i:s', $timestamp);
    }

    private function renderInfoCell(?string $username, ?string $createdAt): string
    {
        return '<div class="small text-muted text-uppercase fw-semibold mb-1">User Input</div>'
            . '<div class="mb-2">' . $this->escape($username ?: '-') . '</div>'
            . '<div class="small text-muted text-uppercase fw-semibold mb-1">Tgl Input</div>'
            . '<div>' . $this->formatDateTime($createdAt) . '</div>';
    }

    private function formatDateForInput(?string $date): string
    {
        $normalized = $this->normalizeDate($date);
        return empty($normalized) ? '' : reverseDate($normalized);
    }

    private function normalizeDecimal($value)
    {
        $value = clean_string((string) $value);
        if ($value === null) {
            return null;
        }

        return str_replace(',', '.', $value);
    }

    private function formatDecimal($value): string
    {
        return $value === null ? '' : rtrim(rtrim((string) $value, '0'), '.');
    }

    private function mapSpecRow(array $row): array
    {
        $data = [];
        foreach ($this->specFields as $field) {
            $data[$field] = $this->formatDecimal($row[$field] ?? null);
        }

        return $data;
    }

    private function mapParamRow(array $row): array
    {
        $data = [];
        foreach ($this->paramFields as $field) {
            $data[$field] = $this->formatDecimal($row[$field] ?? null);
        }

        return $data;
    }

    private function escape(?string $value): string
    {
        return htmlspecialchars((string) ($value ?? '-'), ENT_QUOTES, 'UTF-8');
    }

    private function hasChildDocument(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        return $this->db->table('tx_coa')->where('parent_id', $id)->countAllResults() > 0;
    }

    private function hasPerubahanChild(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        return $this->db->table('tx_coa')
            ->where('parent_id', $id)
            ->where('jns_penerbitan', 2)
            ->countAllResults() > 0;
    }

    private function canCreatePerubahan(array $record): bool
    {
        $id = (int) ($record['id'] ?? 0);
        return $id > 0
            && $this->resolveStatusKirim($record) === 'SENT'
            && (string) ($record['jns_penerbitan'] ?? '') !== '9'
            && strtoupper((string) ($record['isPembatalan'] ?? 'N')) !== 'Y'
            && !$this->hasChildDocument($id);
    }

    private function canCreatePembatalan(array $record): bool
    {
        $id = (int) ($record['id'] ?? 0);
        return $id > 0
            && $this->resolveStatusKirim($record) === 'SENT'
            && (string) ($record['jns_penerbitan'] ?? '') !== '9'
            && strtoupper((string) ($record['isPembatalan'] ?? 'N')) !== 'Y'
            && !$this->hasChildDocument($id);
    }

    private function resolveStatusKirim(array $record): string
    {
        $status = strtoupper(trim((string) ($record['statusKirim'] ?? '')));
        return $status === '' ? 'DRAFT' : $status;
    }

    private function renderStatusBadge(string $status, ?string $waktuKirim = null, bool $isChanged = false, bool $isCancelled = false): string
    {
        $map = [
            'DRAFT' => ['label' => 'DRAFT', 'class' => 'bg-light-primary text-primary'],
            'PROCESS' => ['label' => 'PROSES', 'class' => 'bg-light-warning text-warning'],
            'FAILED' => ['label' => 'GAGAL', 'class' => 'bg-light-danger text-danger'],
            'SENT' => ['label' => 'TERKIRIM', 'class' => 'bg-light-success text-success'],
        ];

        $meta = $map[$status] ?? ['label' => $status, 'class' => 'bg-light-secondary text-secondary'];

        $html = '<div class="d-flex align-items-center justify-content-center gap-1 flex-wrap"><span class="badge ' . $meta['class'] . '">' . $meta['label'] . '</span>';
        if ($isChanged) {
            $html .= '<span class="badge bg-light-warning text-warning">DIUBAH</span>';
        }
        if ($isCancelled) {
            $html .= '<span class="badge bg-light-danger text-danger">DIBATALKAN</span>';
        }
        $html .= '</div>';

        if ($status === 'SENT' && !empty(trim((string) $waktuKirim))) {
            $html .= '<div class="small text-muted text-uppercase fw-semibold mt-2 mb-1">Waktu Kirim</div>';
            $html .= '<div class="small">' . $this->formatDateTime($waktuKirim) . '</div>';
        }

        return $html;
    }
}
