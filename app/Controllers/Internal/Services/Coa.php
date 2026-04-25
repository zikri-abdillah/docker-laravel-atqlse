<?php

namespace App\Controllers\Internal\Services;

use App\Controllers\BaseController;
use Throwable;

class Coa extends BaseController
{
    #protected string $endpoint = 'https://gwi.kemendag.go.id/surveyor/send_COA'; 
    // protected string $endpoint = 'https://ws.kemendag.go.id/insw_dev/send_COA'; //development
    protected string $endpoint = 'https://ws.kemendag.go.id/surveyor/sendCOA'; //production
    protected string $apiKeyName = 'x-api-key';
    // protected string $apiKey = '3D6C8028F4D75001B5EE368DDF199FDC24FDF412';
    protected string $apiKey = '38ba63e6-a4df-4bec-b256-c6b80aa8dfbd';
    protected string $apiUsername = 'asiatrust';
    protected string $rootFileUrl;

    public function __construct()
    {
        helper('api');
        $this->rootFileUrl = rtrim((string) (env('app.coaFileBaseURL') ?: 'https://appls.atq-lse.co.id'), '/');
    }

    public function send()
    {
        return $this->processSend(false);
    }

    public function cancel()
    {
        return $this->processSend(true);
    }

    private function processSend(bool $isCancellation)
    {
        $coaId = null;


        try {
            $postId = trim((string) $this->request->getPost('id'));
            if ($postId === '') {
                return $this->response->setJSON(resp_error('Data COA tidak valid.'));
            }

            $coaId = (int) decrypt_id($postId);
            if ($coaId <= 0) {
                return $this->response->setJSON(resp_error('Data COA tidak valid.'));
            }

            if (!$this->db->tableExists('tx_coa')) {
                return $this->response->setJSON(resp_error('Tabel tx_coa belum tersedia.'));
            }

            foreach (['statusKirim', 'waktuKirim', 'isPembatalan', 'parent_id'] as $field) {
                if (!$this->db->fieldExists($field, 'tx_coa')) {
                    return $this->response->setJSON(resp_error('Kolom `' . $field . '` belum tersedia pada tabel tx_coa.'));
                }
            }

            $header = $this->db->table('tx_coa')->where('id', $coaId)->get()->getRowArray();
            if (empty($header)) {
                return $this->response->setJSON(resp_error('Data COA tidak ditemukan.'));
            }

            $statusKirim = strtoupper(trim((string) ($header['statusKirim'] ?? 'DRAFT')));
            $isPembatalan = strtoupper(trim((string) ($header['isPembatalan'] ?? 'N'))) === 'Y';

            if ($isCancellation) {
                if ($statusKirim !== 'SENT') {
                    return $this->response->setJSON(resp_error('Pembatalan hanya dapat dilakukan pada data COA yang sudah terkirim.'));
                }

                if ($isPembatalan) {
                    return $this->response->setJSON(resp_error('Data COA ini sudah dibatalkan sebelumnya.'));
                }

                if ($this->hasChildDocument($coaId)) {
                    return $this->response->setJSON(resp_error('Pembatalan hanya dapat dilakukan pada data COA terkirim terbaru.'));
                }
            } else {
                if ($statusKirim === 'SENT') {
                    return $this->response->setJSON(resp_error('Data COA ini sudah terkirim.'));
                }

                if ($isPembatalan) {
                    return $this->response->setJSON(resp_error('Data COA yang sudah dibatalkan tidak dapat dikirim ulang.'));
                }

                if (!in_array($statusKirim, ['DRAFT', 'FAILED'], true)) {
                    return $this->response->setJSON(resp_error('Hanya data COA draft yang dapat dikirim.'));
                }
            }

            $payload = $this->buildSendPayload($coaId, $header, $isCancellation ? 9 : null);
            // var_dump($isCancellation);exit();
            if (empty($payload)) {
                return $this->response->setJSON(resp_error('Data COA belum memiliki komoditas dan grup COA yang siap dikirim.'));
            }

            if (!$isCancellation) {
                $this->db->table('tx_coa')->where('id', $coaId)->update([
                    'statusKirim' => 'PROCESS',
                ]);
            }

            $config = new \stdClass();
            $config->traffic = 'OUT';
            $config->method = 'POST';
            $config->endPoint = $this->endpoint;
            $config->apiKeyName = $this->apiKeyName;
            $config->apiKey = $this->apiKey;

            $noAju = $header['nomor_coa'] ?: $header['no_ls'];
            $config->payload = $payload;

            $log = service_log(null, $config, [
                'idData' => $coaId,
            ], $noAju);

            $response = curlClient($config);
            $rawResponseBody = (string) $response->getBody();
            $decodedResponse = $this->decodeServiceResponse($rawResponseBody);
            $lastResponseMessage = $this->extractResponseMessage($decodedResponse, $rawResponseBody);
            $responseCode = $this->extractResponseCode($decodedResponse);
            $httpStatus = method_exists($response, 'getStatusCode') ? (int) $response->getStatusCode() : null;

            if (!empty($log) && !empty($log->id)) {
                service_log($log->id, $config, [
                    'response' => $response,
                    'responseCode' => $responseCode,
                    'responseMsg' => $lastResponseMessage,
                ], $noAju);
            }

            if (!$this->isSuccessResponse($decodedResponse)) {
                if (!$isCancellation) {
                    $this->markSendStatus($coaId, 'FAILED');
                }

                return $this->response->setJSON(resp_error(
                    $this->buildUserResponseMessage(
                        $isCancellation ? 'Pembatalan gagal terkirim.' : 'Data gagal terkirim.',
                        $lastResponseMessage
                    ),
                    $this->buildResponseData([], $rawResponseBody, $responseCode, $httpStatus)
                ));
            }

            if ($isCancellation) {
                $this->markPembatalan($coaId);

                return $this->response->setJSON(resp_success(
                    $this->buildUserResponseMessage('Pembatalan berhasil terkirim.', $lastResponseMessage),
                    $this->buildResponseData([
                        'statusKirim' => 'SENT',
                        'isPembatalan' => 'Y',
                    ], $rawResponseBody, $responseCode, $httpStatus)
                ));
            }

            $this->markSendStatus($coaId, 'SENT');

            return $this->response->setJSON(resp_success(
                $this->buildUserResponseMessage('Data berhasil terkirim.', $lastResponseMessage),
                $this->buildResponseData([
                    'statusKirim' => 'SENT',
                ], $rawResponseBody, $responseCode, $httpStatus)
            ));
        } catch (Throwable $e) {
            if (!empty($coaId) && !$isCancellation) {
                $this->markSendStatus($coaId, 'FAILED');
            }

            return $this->response->setJSON(resp_error(($isCancellation ? 'Pembatalan gagal terkirim.' : 'Data gagal terkirim.') . '<br>Exception :' . $e->getMessage()));
        }
    }

    private function buildSendPayload(int $coaId, array $header, ?int $overrideJenisPenerbitan = null): array
    {
        $komoditasRows = $this->db->table('tx_coa_komoditas')
            ->where('tx_coa_id', $coaId)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        if (empty($komoditasRows)) {
            return [];
        }

        $komoditasPayloads = [];
        $headerPayload = $this->buildHeaderPayload($header, $overrideJenisPenerbitan);

        foreach ($komoditasRows as $komoditasRow) {
            $coaGroups = [];
            $groupRows = $this->db->table('tx_coa_group')
                ->where('tx_coa_komoditas_id', $komoditasRow['id'])
                ->orderBy('no_group', 'ASC')
                ->orderBy('id', 'ASC')
                ->get()
                ->getResultArray();

            foreach ($groupRows as $groupRow) {
                $specRows = $this->db->table('tx_coa_spec')
                    ->where('tx_coa_group_id', $groupRow['id'])
                    ->orderBy('id', 'ASC')
                    ->get()
                    ->getResultArray();

                $paramRows = $this->db->table('tx_coa_param')
                    ->where('tx_coa_group_id', $groupRow['id'])
                    ->orderBy('id', 'ASC')
                    ->get()
                    ->getResultArray();

                $coaPayload = $this->buildCoaPayload($specRows, $paramRows);
                if (!empty($coaPayload)) {
                    $coaGroups[] = $coaPayload;
                }
            }

            $komoditasPayload = $this->buildKomoditasPayload($komoditasRow);

            if (!empty($coaGroups)) {
                $komoditasPayload['coa'] = $this->collapseSingleItem($coaGroups);
            }

            $komoditasPayload = $this->filterPayload($komoditasPayload);
            if (!empty($komoditasPayload)) {
                $komoditasPayloads[] = $komoditasPayload;
            }
        }

        if (empty($komoditasPayloads)) {
            return [];
        }

        if (count($komoditasPayloads) === 1) {
            $singleKomoditas = $komoditasPayloads[0];
            $coaPayload = $singleKomoditas['coa'] ?? [];
            unset($singleKomoditas['coa']);

            return $this->filterPayload([
                'header' => $headerPayload,
                'komoditas' => $singleKomoditas,
                'coa' => $coaPayload,
                'username' => $this->apiUsername,
            ]);
        }

        return $this->filterPayload([
            'header' => $headerPayload,
            'komoditas' => $komoditasPayloads,
            'username' => $this->apiUsername,
        ]);
    }

    private function buildHeaderPayload(array $header, ?int $overrideJenisPenerbitan = null): array
    {
        $urlCoa = '';
        if (!empty($header['url_coa'])) {
            $urlCoa = $this->rootFileUrl . '/doc/coa/' . $header['url_coa'];
        }

        return [
            'jns_penerbitan' => $overrideJenisPenerbitan ?? $this->normalizeInteger($header['jns_penerbitan'] ?? null),
            'nib' => $header['nib'] ?? '',
            'npwp' => $header['npwp'] ?? '',
            'nitku' => $header['nitku'] ?? '',
            'nama_perusahaan' => $header['nama_perusahaan'] ?? '',
            'no_ls' => $header['no_ls'] ?? '',
            'tgl_ls' => $this->normalizeDate($header['tgl_ls'] ?? null),
            'kode_ls' => $header['kode_ls'] ?? '',
            'nomor_coa' => $header['nomor_coa'] ?? '',
            'tgl_coa' => $this->normalizeDate($header['tgl_coa'] ?? null),
            'tgl_periksa' => $this->normalizeDate($header['tgl_periksa'] ?? null),
            'url_coa' => $urlCoa,
        ];
    }

    private function buildKomoditasPayload(array $komoditasRow): array
    {
        return [
            'ur_barang' => $komoditasRow['ur_barang'] ?? '',
            'spesifikasi' => $komoditasRow['spesifikasi_barang'] ?? '',
            'jml_barang' => $this->normalizeDecimal($komoditasRow['jml_barang'] ?? null),
            'satuan' => $komoditasRow['satuan'] ?? '',
        ];
    }

    private function buildCoaPayload(array $specRows, array $paramRows): array
    {
        $specPayload = [];
        foreach ($specRows as $row) {
            $item = $this->filterPayload([
                'ash_arb' => $this->normalizeDecimal($row['ash_arb'] ?? null),
                'ash_adb' => $this->normalizeDecimal($row['ash_adb'] ?? null),
                'tm_arb' => $this->normalizeDecimal($row['tm_arb'] ?? null),
                'inh_adb' => $this->normalizeDecimal($row['inh_adb'] ?? null),
                'tsulf_arb' => $this->normalizeDecimal($row['tsulf_arb'] ?? null),
                'tsulf_adb' => $this->normalizeDecimal($row['tsulf_adb'] ?? null),
                'vol_matter' => $this->normalizeDecimal($row['vol_matter'] ?? null),
                'fix_carb' => $this->normalizeDecimal($row['fix_carb'] ?? null),
                'size_0' => $this->normalizeDecimal($row['size_0'] ?? null),
                'size_50' => $this->normalizeDecimal($row['size_50'] ?? null),
                'hgi' => $this->normalizeDecimal($row['hgi'] ?? null),
            ]);

            if (!empty($item)) {
                $specPayload[] = $item;
            }
        }

        $paramPayload = [];
        foreach ($paramRows as $row) {
            $item = $this->filterPayload([
                'gcv_arb' => $this->normalizeDecimal($row['gcv_arb'] ?? null),
                'gcv_adb' => $this->normalizeDecimal($row['gcv_adb'] ?? null),
                'ncv_arb' => $this->normalizeDecimal($row['ncv_arb'] ?? null),
            ]);

            if (!empty($item)) {
                $paramPayload[] = $item;
            }
        }

        return $this->filterPayload([
            'spec' => $this->collapseSingleItem($specPayload),
            'param' => $this->collapseSingleItem($paramPayload),
        ]);
    }

    private function collapseSingleItem(array $items)
    {
        if (count($items) === 1) {
            return $items[0];
        }

        return $items;
    }

    private function filterPayload($value)
    {
        if (is_array($value)) {
            $filtered = [];

            foreach ($value as $key => $item) {
                $item = $this->filterPayload($item);

                if ($item === null) {
                    continue;
                }

                if (is_array($item) && empty($item)) {
                    continue;
                }

                $filtered[$key] = $item;
            }

            return $filtered;
        }

        if ($value === '') {
            return null;
        }

        return $value;
    }

    private function normalizeDate($value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) === 1) {
            return $value;
        }

        if (preg_match('/^\d{2}-\d{2}-\d{4}$/', $value) === 1) {
            [$day, $month, $year] = explode('-', $value);
            return $year . '-' . $month . '-' . $day;
        }

        return null;
    }

    private function normalizeDecimal($value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (float) $value;
    }

    private function normalizeInteger($value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }

    private function decodeServiceResponse(string $rawResponseBody): ?object
    {
        $decodedResponse = json_decode($rawResponseBody);
        if (json_last_error() !== JSON_ERROR_NONE || !is_object($decodedResponse)) {
            return null;
        }

        return $decodedResponse;
    }

    private function isSuccessResponse($response): bool
    {
        if (!is_object($response)) {
            return false;
        }

        $responseData = $response->data ?? null;

        return (int) ($response->kode ?? 0) === 200
            && is_object($responseData)
            && (string) ($responseData->kode ?? '') === 'A01';
    }

    private function extractResponseCode($response): string
    {
        if (!is_object($response)) {
            return '';
        }

        $responseData = $response->data ?? null;
        if (is_object($responseData) && isset($responseData->kode)) {
            return (string) $responseData->kode;
        }

        return (string) ($response->kode ?? '');
    }

    private function extractResponseMessage($response, string $fallback): string
    {
        if (is_object($response)) {
            $responseData = $response->data ?? null;
            if (is_object($responseData) && !empty($responseData->keterangan)) {
                return (string) $responseData->keterangan;
            }

            if (!empty($response->keterangan)) {
                return (string) $response->keterangan;
            }
        }

        $fallback = trim($fallback);
        if ($fallback === '') {
            return 'Response body kosong';
        }

        return $fallback;
    }

    private function buildUserResponseMessage(string $prefix, string $responseMessage): string
    {
        return $prefix . '<br>Respon inatrade = ' . htmlspecialchars($responseMessage, ENT_QUOTES, 'UTF-8');
    }

    private function buildResponseData(array $data, string $rawResponseBody, string $responseCode = '', ?int $httpStatus = null): array
    {
        $data['raw_response'] = trim($rawResponseBody) !== '' ? $rawResponseBody : 'Response body kosong';

        if ($responseCode !== '') {
            $data['response_code'] = $responseCode;
        }

        if ($httpStatus !== null) {
            $data['http_status'] = $httpStatus;
        }

        return $data;
    }

    private function markSendStatus(int $coaId, string $status): void
    {
        if ($coaId <= 0) {
            return;
        }

        $data = [
            'statusKirim' => $status,
        ];

        if (in_array($status, ['SENT', 'FAILED'], true)) {
            $data['waktuKirim'] = date('Y-m-d H:i:s');
        }

        $this->db->table('tx_coa')->where('id', $coaId)->update($data);
    }

    private function markPembatalan(int $coaId): void
    {
        if ($coaId <= 0) {
            return;
        }

        $this->db->table('tx_coa')->where('id', $coaId)->update([
            'isPembatalan' => 'Y',
        ]);
    }

    private function hasChildDocument(int $coaId): bool
    {
        if ($coaId <= 0) {
            return false;
        }

        return $this->db->table('tx_coa')
            ->where('parent_id', $coaId)
            ->countAllResults() > 0;
    }
}
