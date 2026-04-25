<?php

namespace App\Controllers\Internal\Lse\Batubara;

use App\Controllers\BaseController;
use Throwable;

class Cow extends BaseController
{
    protected array $jenisPenerbitan = [
        '1' => 'Baru',
        '2' => 'Perubahan',
        '9' => 'Pembatalan',
    ];

    public function index()
    {
        $page = [
            'table_title' => 'Data COW Batubara',
            'breadcrumb_active' => 'COW Batubara',
        ];

        $param['content'] = $this->render('ekspor.batubara.cow.index', $page);
        $param['addJS'] = '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/datatable/js/dataTables.buttons.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/datatable/dataTables.responsive.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/datatable/responsive.bootstrap5.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/js/ekspor/coal/cow.js?v=' . date('YmdHis') . '"></script>';

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

            if (empty($record) || $this->resolveStatusKirim($record) === 'SENT') {
                return redirect()->to(base_url('ekspor/cow'));
            }

            return $this->renderForm($record);
        } catch (Throwable $e) {
            return redirect()->to(base_url('ekspor/cow'));
        }
    }

    public function view()
    {
        try {
            $id = decrypt_id($this->request->getPost('id'));
            $record = $this->findRecord($id);

            if (empty($record)) {
                return redirect()->to(base_url('ekspor/cow'));
            }

            return $this->renderReadonly($record);
        } catch (Throwable $e) {
            return redirect()->to(base_url('ekspor/cow'));
        }
    }

    public function create_perubahan()
    {
        try {
            $id = decrypt_id($this->request->getPost('id'));
            $record = $this->findRecord($id);

            if (empty($record)) {
                return $this->response->setJSON(resp_error('Data COW tidak ditemukan.'));
            }

            if ($this->resolveStatusKirim($record) !== 'SENT') {
                return $this->response->setJSON(resp_error('Perubahan hanya dapat dibuat dari data COW yang sudah terkirim.'));
            }

            if (strtoupper((string) ($record['isPembatalan'] ?? 'N')) === 'Y') {
                return $this->response->setJSON(resp_error('Data COW yang sudah dibatalkan tidak dapat dibuat perubahan.'));
            }

            if ($this->hasChildDocument($id)) {
                return $this->response->setJSON(resp_error('Draft perubahan hanya dapat dibuat dari data COW terkirim terbaru.'));
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
                'nomor_cow' => $record['nomor_cow'] ?? '',
                'tgl_cow' => $record['tgl_cow'] ?? null,
                'tgl_periksa' => $record['tgl_periksa'] ?? null,
                'pathFile' => null,
                'url_cow' => '',
                'username' => session()->get('sess_username') ?: ($record['username'] ?? ''),
                'statusKirim' => 'DRAFT',
                'waktuKirim' => null,
            ];

            $this->db->transStart();
            $this->db->table('tx_cow')->insert($headerData);
            $newCowId = (int) $this->db->insertID();
            $this->persistKomoditas($newCowId, $record['komoditas'] ?? []);
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON(resp_error($this->buildDbErrorMessage('Gagal membuat draft perubahan COW.')));
            }

            return $this->response->setJSON(resp_success('Draft perubahan COW berhasil dibuat.', [
                'id' => encrypt_id($newCowId),
            ]));
        } catch (Throwable $e) {
            return $this->response->setJSON(resp_error('Gagal membuat draft perubahan COW. ' . $e->getMessage()));
        }
    }

    public function create_pembatalan()
    {
        return $this->response->setJSON(resp_error('Aksi pembatalan COW diproses langsung melalui endpoint pengiriman.'));
    }

    public function list()
    {
        $searchParam = $this->request->getPost('searchParam');
        $arrParam = !empty($searchParam) ? post_ajax_toarray($searchParam) : [];

        $builder = $this->db->table('tx_cow c');
        $builder->select('c.*');
        $builder->select('COUNT(DISTINCT k.id) AS komoditas_count', false);
        $builder->select('(SELECT k2.ur_barang FROM tx_cow_komoditas k2 WHERE k2.tx_cow_id = c.id ORDER BY k2.id ASC LIMIT 1) AS first_komoditas', false);
        $builder->select('(SELECT COUNT(*) FROM tx_cow c2 WHERE c2.parent_id = c.id) AS child_count', false);
        $builder->select('(SELECT COUNT(*) FROM tx_cow c2 WHERE c2.parent_id = c.id AND c2.jns_penerbitan = 2) AS perubahan_child_count', false);
        $builder->join('tx_cow_komoditas k', 'k.tx_cow_id = c.id', 'left');

        if (!empty($arrParam['namaPerusahaan'])) {
            $builder->like('c.nama_perusahaan', $arrParam['namaPerusahaan']);
        }

        if (!empty($arrParam['noLs'])) {
            $builder->like('c.no_ls', $arrParam['noLs']);
        }

        if (!empty($arrParam['nomorCow'])) {
            $builder->like('c.nomor_cow', $arrParam['nomorCow']);
        }

        $builder->groupBy('c.id');

        $recordsTotal = $this->db->table('tx_cow')->countAllResults();
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
            $firstKomoditas = $record['first_komoditas'] ?? '-';
            $statusKirim = $this->resolveStatusKirim($record);
            $hasChildDocument = (int) ($record['child_count'] ?? 0) > 0;
            $hasPerubahanChild = (int) ($record['perubahan_child_count'] ?? 0) > 0;
            $btnView = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-info me-1 mb-1" onclick="viewData(\'' . encrypt_id($record['id']) . '\')" title="Lihat"><i class="fa fa-eye"></i></button> ';
            $actionButtons = [$btnView];

            if (in_array($statusKirim, ['DRAFT', 'FAILED'], true)) {
                $sendTitle = $statusKirim === 'FAILED' ? 'Kirim Ulang' : 'Kirim';
                $actionButtons[] = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-primary me-1 mb-1" onclick="sendCow(\'' . encrypt_id($record['id']) . '\')" title="' . $sendTitle . '"><i class="fa fa-send"></i></button> ';
                $actionButtons[] = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning me-1 mb-1" onclick="edit(\'' . encrypt_id($record['id']) . '\')" title="Edit"><i class="fa fa-edit"></i></button> ';
                $actionButtons[] = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger mb-1" onclick="del(\'' . encrypt_id($record['id']) . '\')" title="Hapus"><i class="fa fa-trash"></i></button>';
            } elseif ($statusKirim === 'SENT' && !$hasChildDocument && strtoupper((string) ($record['isPembatalan'] ?? 'N')) !== 'Y' && (string) ($record['jns_penerbitan'] ?? '') !== '9') {
                $actionButtons[] = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger mb-1" onclick="cancelCow(\'' . encrypt_id($record['id']) . '\')" title="Pembatalan"><i class="fa fa-ban"></i></button>';
            }

            $jenisPenerbitan = $this->jenisPenerbitan[(string) ($record['jns_penerbitan'] ?? '')] ?? '-';
            $badgeClass = 'bg-light-secondary text-secondary';
            if (($record['jns_penerbitan'] ?? '') === '1' || ($record['jns_penerbitan'] ?? null) === 1) {
                $badgeClass = 'bg-light-success text-success';
            } elseif (($record['jns_penerbitan'] ?? '') === '2' || ($record['jns_penerbitan'] ?? null) === 2) {
                $badgeClass = 'bg-light-warning text-warning';
            } elseif (($record['jns_penerbitan'] ?? '') === '9' || ($record['jns_penerbitan'] ?? null) === 9) {
                $badgeClass = 'bg-light-danger text-danger';
            }

            $rows[] = [
                $no++,
                '<div class="d-flex align-items-center gap-2 flex-wrap"><span class="fw-semibold">' . $this->escape($record['nomor_cow'] ?? '-') . '</span><span class="badge ' . $badgeClass . '">' . $this->escape($jenisPenerbitan) . '</span></div><span>No. LS: ' . $this->escape($record['no_ls'] ?? '-') . '</span><br><span>Tgl COW: ' . $this->formatDate($record['tgl_cow'] ?? '') . '</span>',
                '<span class="fw-semibold">' . $this->escape($record['nama_perusahaan'] ?? '-') . '</span><br><span>NIB: ' . $this->escape($record['nib'] ?? '-') . '</span><br><span>NPWP: ' . $this->escape($record['npwp'] ?? '-') . '</span>',
                '<span class="fw-semibold">' . $komoditasCount . ' komoditas</span><br><span>' . $this->escape($firstKomoditas) . '</span>',
                $this->renderInfoCell($record['username'] ?? '', $record['created_at'] ?? ''),
                $this->renderStatusBadge($statusKirim, $record['waktuKirim'] ?? '', $hasPerubahanChild, strtoupper((string) ($record['isPembatalan'] ?? 'N')) === 'Y'),
                '<div class="btn-list text-nowrap">' . implode('', $actionButtons) . '</div>',
            ];
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
                return $this->response->setJSON(resp_error('Payload COW tidak valid.'));
            }

            $payload = $this->normalizeIncomingPayload($payload);
            $headerId = !empty($payload['idData']) ? decrypt_id($payload['idData']) : null;
            $existingRecord = !empty($headerId)
                ? $this->db->table('tx_cow')->where('id', $headerId)->get()->getRowArray()
                : [];

            if (!empty($existingRecord) && $this->resolveStatusKirim($existingRecord) === 'SENT') {
                return $this->response->setJSON(resp_error('Data COW yang sudah terkirim tidak dapat diubah.'));
            }

            $uploadResult = $this->handleUploadedFile($existingRecord);
            if ($uploadResult['status'] !== true) {
                return $this->response->setJSON(resp_error($uploadResult['message']));
            }

            $payload['url_cow'] = $uploadResult['url_cow'];
            $payload['pathFile'] = $uploadResult['pathFile'];

            $payload = $this->sanitizePayload($payload);
            if (!empty($existingRecord)) {
                $payload['jns_penerbitan'] = (string) ($existingRecord['jns_penerbitan'] ?? $payload['jns_penerbitan']);

                if ((string) ($existingRecord['jns_penerbitan'] ?? '') === '2') {
                    $payload['nomor_cow'] = (string) ($existingRecord['nomor_cow'] ?? $payload['nomor_cow']);
                    $payload['tgl_cow'] = (string) ($existingRecord['tgl_cow'] ?? $payload['tgl_cow']);
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
                $this->db->table('tx_cow')->where('id', $headerId)->update($headerData);
                $this->deleteChildRecords($headerId);
                $cowId = $headerId;
            } else {
                $headerData['statusKirim'] = 'DRAFT';
                $headerData['waktuKirim'] = null;
                $this->db->table('tx_cow')->insert($headerData);
                $cowId = (int) $this->db->insertID();
            }

            $this->persistKomoditas($cowId, $payload['komoditas']);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON(resp_error($this->buildDbErrorMessage('Gagal menyimpan data COW ke database.')));
            }

            return $this->response->setJSON(resp_success('Data COW berhasil disimpan.', [
                'id' => encrypt_id($cowId),
            ]));
        } catch (Throwable $e) {
            return $this->response->setJSON(resp_error('Gagal menyimpan data COW. ' . $e->getMessage()));
        }
    }

    public function delete()
    {
        try {
            $id = decrypt_id($this->request->getPost('id'));
            $header = $this->db->table('tx_cow')->where('id', $id)->get()->getRowArray();

            if (empty($header)) {
                return $this->response->setJSON(resp_error('Data COW tidak ditemukan.'));
            }

            if ($this->resolveStatusKirim($header) === 'SENT') {
                return $this->response->setJSON(resp_error('Data COW yang sudah terkirim tidak dapat dihapus.'));
            }

            $this->db->transStart();
            $this->deleteChildRecords($id);
            $this->db->table('tx_cow')->where('id', $id)->delete();
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->response->setJSON(resp_error($this->buildDbErrorMessage('Gagal menghapus data COW dari database.')));
            }

            return $this->response->setJSON(resp_success('Data COW berhasil dihapus.'));
        } catch (Throwable $e) {
            return $this->response->setJSON(resp_error('Gagal menghapus data COW. ' . $e->getMessage()));
        }
    }

    public function delete_file()
    {
        try {
            $id = decrypt_id($this->request->getPost('id'));
            $record = $this->db->table('tx_cow')->where('id', $id)->get()->getRowArray();

            if (empty($record)) {
                return $this->response->setJSON(resp_error('Data COW tidak ditemukan.'));
            }

            if ($this->resolveStatusKirim($record) === 'SENT') {
                return $this->response->setJSON(resp_error('File COW pada data yang sudah terkirim tidak dapat dihapus.'));
            }

            if (!empty($record['pathFile']) && is_file(WRITEPATH . 'uploads/' . $record['pathFile'])) {
                unlink(WRITEPATH . 'uploads/' . $record['pathFile']);
            }

            $updated = $this->db->table('tx_cow')
                ->where('id', $id)
                ->set('pathFile', null)
                ->set('url_cow', '')
                ->update();

            if (!$updated) {
                return $this->response->setJSON(resp_error($this->buildDbErrorMessage('File COW gagal dihapus.')));
            }

            return $this->response->setJSON(resp_success('File COW berhasil dihapus.'));
        } catch (Throwable $e) {
            return $this->response->setJSON(resp_error('Gagal menghapus file COW. ' . $e->getMessage()));
        }
    }

    public function lsSuggestions()
    {
        $keyword = trim((string) $this->request->getPost('q'));

        if ($keyword === '') {
            return $this->response->setJSON(['data' => []]);
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
            if (!empty($row['noLs'])) {
                $results[] = [
                    'value' => $row['noLs'],
                    'label' => $row['noLs'],
                ];
            }
        }

        return $this->response->setJSON(['data' => $results]);
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

    private function renderForm(array $record = []): string
    {
        $page = [
            'page_title' => empty($record) ? 'Input COW Batubara' : 'Edit COW Batubara',
            'cowData' => $this->prepareFormData($record),
            'cowNestedDataJson' => json_encode(
                $record['komoditas'] ?? [],
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
            ),
        ];

        $param['addJS'] = '<script>window.cowNestedData = ' . $page['cowNestedDataJson'] . ';</script>';
        $param['addJS'] .= '<script src="' . base_url() . '/js/ekspor/coal/cow-input.js?v=' . date('YmdHis') . '"></script>';
        $param['content'] = $this->render('ekspor.batubara.cow.input', $page);

        return $this->render('layout.template', $param);
    }

    private function renderReadonly(array $record): string
    {
        $cowFileUrl = '';
        if (!empty($record['url_cow']) && !empty($record['pathFile']) && is_file(WRITEPATH . 'uploads/' . $record['pathFile'])) {
            $cowFileUrl = base_url('doc/cow/' . $record['url_cow']);
        }

        $page = [
            'page_title' => 'Lihat COW Batubara',
            'cowRecord' => $record,
            'cowRecordId' => !empty($record['id']) ? encrypt_id($record['id']) : '',
            'jenisPenerbitanLabel' => $this->jenisPenerbitan[(string) ($record['jns_penerbitan'] ?? '')] ?? '-',
            'statusKirim' => $this->resolveStatusKirim($record),
            'statusBadge' => $this->renderStatusBadge(
                $this->resolveStatusKirim($record),
                $record['waktuKirim'] ?? '',
                $this->hasPerubahanChild((int) ($record['id'] ?? 0)),
                strtoupper((string) ($record['isPembatalan'] ?? 'N')) === 'Y'
            ),
            'infoCell' => $this->renderInfoCell($record['username'] ?? '', $record['created_at'] ?? ''),
            'cowFileUrl' => $cowFileUrl,
            'canCreatePerubahan' => $this->canCreatePerubahan($record),
            'canCreatePembatalan' => $this->canCreatePembatalan($record),
        ];

        $param['content'] = $this->render('ekspor.batubara.cow.view', $page);

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
            'nomor_cow' => $record['nomor_cow'] ?? '',
            'tgl_cow' => $this->formatDateForInput($record['tgl_cow'] ?? ''),
            'tgl_periksa' => $this->formatDateForInput($record['tgl_periksa'] ?? ''),
            'url_cow' => $record['url_cow'] ?? '',
            'path_file' => $record['pathFile'] ?? '',
            'file_name' => !empty($record['pathFile']) ? basename((string) $record['pathFile']) : '',
        ];
    }

    private function findRecord(int $id): array
    {
        $header = $this->db->table('tx_cow')->where('id', $id)->get()->getRowArray();
        if (empty($header)) {
            return [];
        }

        $header['komoditas'] = $this->db->table('tx_cow_komoditas')
            ->where('tx_cow_id', $id)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        foreach ($header['komoditas'] as &$komoditas) {
            $komoditas['jml_barang'] = $this->formatDecimal($komoditas['jml_barang'] ?? null);
            $komoditas['satuan_label'] = $this->resolveSatuanLabel($komoditas['satuan'] ?? '');
        }

        return $header;
    }

    private function buildHeaderData(array $payload): array
    {
        return [
            'parent_id' => $payload['parent_id'] ?? null,
            'jns_penerbitan' => $payload['jns_penerbitan'] === '' ? null : (int) $payload['jns_penerbitan'],
            'nib' => $payload['nib'],
            'npwp' => $payload['npwp'],
            'nitku' => $payload['nitku'],
            'nama_perusahaan' => $payload['nama_perusahaan'],
            'no_ls' => $payload['no_ls'],
            'tgl_ls' => $this->normalizeDateForStorage($payload['tgl_ls']),
            'kode_ls' => $payload['kode_ls'],
            'nomor_cow' => $payload['nomor_cow'],
            'tgl_cow' => $this->normalizeDateForStorage($payload['tgl_cow']),
            'tgl_periksa' => $this->normalizeDateForStorage($payload['tgl_periksa']),
            'pathFile' => $payload['pathFile'],
            'url_cow' => $payload['url_cow'],
            'username' => session()->get('sess_username') ?: ($payload['username'] ?? ''),
            'isPembatalan' => $payload['isPembatalan'] ?? 'N',
        ];
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

    private function persistKomoditas(int $cowId, array $komoditasList): void
    {
        foreach ($komoditasList as $komoditas) {
            $this->db->table('tx_cow_komoditas')->insert([
                'tx_cow_id' => $cowId,
                'ur_barang' => $komoditas['ur_barang'],
                'spesifikasi' => $komoditas['spesifikasi'],
                'jml_barang' => $this->normalizeDecimal($komoditas['jml_barang']),
                'satuan' => $komoditas['satuan'],
            ]);
        }
    }

    private function deleteChildRecords(int $cowId): void
    {
        $this->db->table('tx_cow_komoditas')->where('tx_cow_id', $cowId)->delete();
    }

    private function sanitizePayload(array $payload): array
    {
        $fields = [
            'idData',
            'parent_id',
            'jns_penerbitan',
            'nib',
            'npwp',
            'nitku',
            'nama_perusahaan',
            'no_ls',
            'tgl_ls',
            'kode_ls',
            'nomor_cow',
            'tgl_cow',
            'tgl_periksa',
            'url_cow',
            'pathFile',
            'username',
        ];

        $data = [];
        foreach ($fields as $field) {
            $data[$field] = clean_string($payload[$field] ?? '');
        }

        $data['komoditas'] = [];

        foreach (($payload['komoditas'] ?? []) as $komoditas) {
            $row = [
                'ur_barang' => clean_string($komoditas['ur_barang'] ?? ''),
                'spesifikasi' => clean_string($komoditas['spesifikasi'] ?? ''),
                'jml_barang' => clean_string($komoditas['jml_barang'] ?? ''),
                'satuan' => clean_string($komoditas['satuan'] ?? ''),
            ];

            if ($this->groupHasValue($row)) {
                $data['komoditas'][] = $row;
            }
        }

        return $data;
    }

    private function groupHasValue(array $group): bool
    {
        foreach ($group as $value) {
            if ($value !== null && $value !== '') {
                return true;
            }
        }

        return false;
    }

    private function validatePayload(array $payload, ?int $headerId = null)
    {
        if (empty($payload['no_ls'])) {
            return 'Nomor LS wajib diisi.';
        }

        if (($payload['jns_penerbitan'] ?? '') === '1' && !empty($payload['nomor_cow'])) {
            $builder = $this->db->table('tx_cow')
                ->where('jns_penerbitan', 1)
                ->where('nomor_cow', $payload['nomor_cow']);

            if (!empty($headerId)) {
                $builder->where('id !=', $headerId);
            }

            if ($builder->countAllResults() > 0) {
                return 'Nomor COW untuk jenis penerbitan Baru sudah digunakan.';
            }
        }

        return true;
    }

    private function validatePersistenceSchema()
    {
        $requirements = [
            'tx_cow' => [
                'parent_id',
                'jns_penerbitan',
                'nib',
                'npwp',
                'nitku',
                'nama_perusahaan',
                'no_ls',
                'tgl_ls',
                'kode_ls',
                'nomor_cow',
                'tgl_cow',
                'tgl_periksa',
                'pathFile',
                'url_cow',
                'username',
                'statusKirim',
                'waktuKirim',
                'isPembatalan',
            ],
            'tx_cow_komoditas' => [
                'tx_cow_id',
                'ur_barang',
                'spesifikasi',
                'jml_barang',
                'satuan',
            ],
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

        $normalized['komoditas'] = [];
        foreach ($komoditasInput as $komoditas) {
            $normalized['komoditas'][] = [
                'ur_barang' => $komoditas['ur_barang'] ?? '',
                'spesifikasi' => $komoditas['spesifikasi'] ?? '',
                'jml_barang' => $komoditas['jml_barang'] ?? '',
                'satuan' => $komoditas['satuan'] ?? '',
            ];
        }

        return $normalized;
    }

    private function handleUploadedFile(array $existingRecord = []): array
    {
        $fileUpload = $this->request->getFile('file_cow');
        $existingPathFile = $existingRecord['pathFile'] ?? '';
        $existingUrlToken = $existingRecord['url_cow'] ?? '';
        $existingFileAvailable = !empty($existingPathFile) && is_file(WRITEPATH . 'uploads/' . $existingPathFile);

        if ($fileUpload !== null && $fileUpload->isValid() && !$fileUpload->hasMoved()) {
            $validationRule = [
                'file_cow' => [
                    'rules' => [
                        'uploaded[file_cow]',
                        'mime_in[file_cow,image/jpg,image/jpeg,image/png,image/webp,image/gif,application/pdf]',
                        'max_size[file_cow,5120]',
                    ],
                    'errors' => [
                        'uploaded' => 'File COW belum dipilih.',
                        'mime_in' => 'File COW harus berupa image atau PDF dengan format JPG/JPEG/PNG/WEBP/GIF/PDF.',
                        'max_size' => 'Ukuran file COW maksimal 5 MB.',
                    ],
                ],
            ];

            if (!$this->validate($validationRule)) {
                return [
                    'status' => false,
                    'message' => $this->validator->getError('file_cow'),
                ];
            }

            $pathFile = $fileUpload->store('cow/' . date('Ym'));

            if ($existingFileAvailable) {
                @unlink(WRITEPATH . 'uploads/' . $existingPathFile);
            }

            return [
                'status' => true,
                'pathFile' => $pathFile,
                'url_cow' => $this->generateFileToken($pathFile),
            ];
        }

        if ($existingFileAvailable && !empty($existingUrlToken)) {
            return [
                'status' => true,
                'pathFile' => $existingPathFile,
                'url_cow' => $existingUrlToken,
            ];
        }

        return [
            'status' => true,
            'pathFile' => '',
            'url_cow' => '',
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
        if ($value === null || $value === '') {
            return null;
        }

        return str_replace(',', '.', $value);
    }

    private function formatDecimal($value): string
    {
        return $value === null ? '' : rtrim(rtrim((string) $value, '0'), '.');
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

        return $this->db->table('tx_cow')->where('parent_id', $id)->countAllResults() > 0;
    }

    private function hasPerubahanChild(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        return $this->db->table('tx_cow')
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
