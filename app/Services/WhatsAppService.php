<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Class WhatsAppService
 * * Service khusus untuk menangani integrasi dengan API Fonnte.
 * Digunakan untuk mengirim notifikasi otomatis ke WA user/admin.
 */
class WhatsAppService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        // Mengambil apiKey Fonnte dari file .env untuk keamanan
        $this->apiKey = env('FONNTE_API_KEY');
        $this->baseUrl = env('FONNTEE_BASE_URL', 'https://api.fonnte.com/send');
    }

    /**
     * Mengirim pesan teks ke nomor WhatsApp target.
     *
     * @param string $target Nomor HP tujuan (contoh: 08123...)
     * @param string $message Isi pesan yang akan dikirim
     * @return bool True jika sukses, False jika gagal
     */
    public function sendMessage(string $target, string $message): array
    {
        if (empty($this->apiKey)) {
            Log::warning('FONNTEE_API_KEY tidak diatur. Notifikasi WA dilewati.');
            return ['status' => 'error', 'message' => 'API Key not set'];
        }

        // Membersihkan dan memformat nomor ke 628...
        $target = preg_replace('/\D/', '', $target);
        if (substr($target, 0, 1) === '0') {
            $target = '62' . substr($target, 1);
        } elseif (substr($target, 0, 2) !== '62') {
             $target = '62' . $target;
        }

        $response = Http::withHeaders([
            'Authorization' => $this->apiKey,
        ])->asForm()->post($this->baseUrl, [
            'target' => $target,
            'message' => $message,
        ]);

        if ($response->failed()) {
            Log::error('Fonntee API Gagal mengirim pesan', ['response' => $response->json(), 'target' => $target]);
        }

        return $response->json();
    }
}