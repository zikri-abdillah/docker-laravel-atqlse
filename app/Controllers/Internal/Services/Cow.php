<?php

namespace App\Controllers\Internal\Services;

use App\Controllers\BaseController;
use Throwable;

class Cow extends BaseController
{
    #protected string $endpoint = 'https://gwi.kemendag.go.id/surveyor/send_COW'; 
    // protected string $endpoint = 'https://ws.kemendag.go.id/insw_dev/send_COW'; //development
    protected string $endpoint = 'https://ws.kemendag.go.id/surveyor/sendCOW'; //production
    protected string $apiKeyName = 'x-api-key';
    // protected string $apiKey = '3D6C8028F4D75001B5EE368DDF199FDC24FDF412';
    protected string $apiKey = '38ba63e6-a4df-4bec-b256-c6b80aa8dfbd';
    protected string $apiUsername = 'asiatrust';
    protected string $rootFileUrl;

    public function __construct()
    {
        helper('api');
        $this->rootFileUrl = rtrim((string) (env('app.cowFileBaseURL') ?: 'https://appls.atq-lse.co.id'), '/');
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
        $cowId = null;

        try {
            $postId = trim((string) $this->request->getPost('id'));
            if ($postId === '') {
                return $this->response->setJSON(resp_error('Data COW tidak valid.'));
            }

            $cowId = (int) decrypt_id($postId);
            if ($cowId <= 0) {
                return $this->response->setJSON(resp_error('Data COW tidak valid.'));
            }

            if (!$this->db->tableExists('tx_cow')) {
                return $this->response->setJSON(resp_error('Tabel tx_cow belum tersedia.'));
            }

            foreach (['statusKirim', 'waktuKirim', 'isPembatalan', 'parent_id'] as $field) {
                if (!$this->db->fieldExists($field, 'tx_cow')) {
                    return $this->response->setJSON(resp_error('Kolom `' . $field . '` belum tersedia pada tabel tx_cow.'));
                }
            }

            $header = $this->db->table('tx_cow')->where('id', $cowId)->get()->getRowArray();
            if (empty($header)) {
                return $this->response->setJSON(resp_error('Data COW tidak ditemukan.'));
            }

            $statusKirim = strtoupper(trim((string) ($header['statusKirim'] ?? 'DRAFT')));
            $isPembatalan = strtoupper(trim((string) ($header['isPembatalan'] ?? 'N'))) === 'Y';

            if ($isCancellation) {
                if ($statusKirim !== 'SENT') {
                    return $this->response->setJSON(resp_error('Pembatalan hanya dapat dilakukan pada data COW yang sudah terkirim.'));
                }

                if ($isPembatalan) {
                    return $this->response->setJSON(resp_error('Data COW ini sudah dibatalkan sebelumnya.'));
                }

                if ($this->hasChildDocument($cowId)) {
                    return $this->response->setJSON(resp_error('Pembatalan hanya dapat dilakukan pada data COW terkirim terbaru.'));
                }
            } else {
                if ($statusKirim === 'SENT') {
                    return $this->response->setJSON(resp_error('Data COW ini sudah terkirim.'));
                }

                if ($isPembatalan) {
                    return $this->response->setJSON(resp_error('Data COW yang sudah dibatalkan tidak dapat dikirim ulang.'));
                }

                if (!in_array($statusKirim, ['DRAFT', 'FAILED'], true)) {
                    return $this->response->setJSON(resp_error('Hanya data COW draft yang dapat dikirim.'));
                }
            }

            $payload = $this->buildSendPayload($cowId, $header, $isCancellation ? 9 : null);
            if (empty($payload)) {
                return $this->response->setJSON(resp_error('Data COW belum memiliki komoditas yang siap dikirim.'));
            }

            if (!$isCancellation) {
                $this->db->table('tx_cow')->where('id', $cowId)->update([
                    'statusKirim' => 'PROCESS',
                ]);
            }

            $config = new \stdClass();
            $config->traffic = 'OUT';
            $config->method = 'POST';
            $config->endPoint = $this->endpoint;
            $config->apiKeyName = $this->apiKeyName;
            $config->apiKey = $this->apiKey;
            $config->payload = $payload;

            $noAju = $header['nomor_cow'] ?: $header['no_ls'];

            $log = service_log(null, $config, [
                'idData' => $cowId,
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
                    $this->markSendStatus($cowId, 'FAILED');
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
                $this->markPembatalan($cowId);

                return $this->response->setJSON(resp_success(
                    $this->buildUserResponseMessage('Pembatalan berhasil terkirim.', $lastResponseMessage),
                    $this->buildResponseData([
                        'statusKirim' => 'SENT',
                        'isPembatalan' => 'Y',
                    ], $rawResponseBody, $responseCode, $httpStatus)
                ));
            }

            $this->markSendStatus($cowId, 'SENT');

            return $this->response->setJSON(resp_success(
                $this->buildUserResponseMessage('Data berhasil terkirim.', $lastResponseMessage),
                $this->buildResponseData([
                    'statusKirim' => 'SENT',
                ], $rawResponseBody, $responseCode, $httpStatus)
            ));
        } catch (Throwable $e) {
            if (!empty($cowId) && !$isCancellation) {
                $this->markSendStatus($cowId, 'FAILED');
            }

            return $this->response->setJSON(resp_error(($isCancellation ? 'Pembatalan gagal terkirim.' : 'Data gagal terkirim.') . '<br>Exception :' . $e->getMessage()));
        }
    }

    private function buildSendPayload(int $cowId, array $header, ?int $overrideJenisPenerbitan = null): array
    {
        $komoditasRows = $this->db->table('tx_cow_komoditas')
            ->where('tx_cow_id', $cowId)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();

        if (empty($komoditasRows)) {
            return [];
        }

        $komoditasPayloads = [];
        foreach ($komoditasRows as $row) {
            $komoditasPayload = $this->filterPayload([
                'ur_barang' => $row['ur_barang'] ?? '',
                'spesifikasi' => $row['spesifikasi'] ?? '',
                'jml_barang' => $this->normalizeDecimal($row['jml_barang'] ?? null),
                'satuan' => $row['satuan'] ?? '',
            ]);

            if (!empty($komoditasPayload)) {
                $komoditasPayloads[] = $komoditasPayload;
            }
        }

        if (empty($komoditasPayloads)) {
            return [];
        }

        return $this->filterPayload([
            'header' => $this->buildHeaderPayload($header, $overrideJenisPenerbitan),
            'komoditas' => count($komoditasPayloads) === 1 ? $komoditasPayloads[0] : $komoditasPayloads,
            'username' => $this->apiUsername,
        ]);
    }

    private function buildHeaderPayload(array $header, ?int $overrideJenisPenerbitan = null): array
    {
        $urlCow = '';
        if (!empty($header['url_cow']) && !empty($header['pathFile']) && is_file(WRITEPATH . 'uploads/' . $header['pathFile'])) {
            $urlCow = $this->rootFileUrl . '/doc/cow/' . $header['url_cow'];
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
            'nomor_cow' => $header['nomor_cow'] ?? '',
            'tgl_cow' => $this->normalizeDate($header['tgl_cow'] ?? null),
            'tgl_periksa' => $this->normalizeDate($header['tgl_periksa'] ?? null),
            'url_cow' => $urlCow,
        ];
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

    private function markSendStatus(int $cowId, string $status): void
    {
        if ($cowId <= 0) {
            return;
        }

        $data = [
            'statusKirim' => $status,
        ];

        if (in_array($status, ['SENT', 'FAILED'], true)) {
            $data['waktuKirim'] = date('Y-m-d H:i:s');
        }

        $this->db->table('tx_cow')->where('id', $cowId)->update($data);
    }

    private function markPembatalan(int $cowId): void
    {
        if ($cowId <= 0) {
            return;
        }

        $this->db->table('tx_cow')->where('id', $cowId)->update([
            'isPembatalan' => 'Y',
        ]);
    }

    private function hasChildDocument(int $cowId): bool
    {
        if ($cowId <= 0) {
            return false;
        }

        return $this->db->table('tx_cow')
            ->where('parent_id', $cowId)
            ->countAllResults() > 0;
    }
}
