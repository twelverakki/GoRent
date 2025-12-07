<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Kirim pesan WhatsApp via Fonnte
     * @param string $target Nomor tujuan (08xx atau 62xx)
     * @param string $message Isi pesan
     * @return bool
     */
    public function sendMessage($target, $message)
    {
        // Ambil config dari .env
        $token = env('FONNTEE_API_KEY');
        $url = env('FONNTEE_BASE_URL', 'https://api.fonnte.com/send');

        // Validasi Token
        if (empty($token)) {
            Log::error('WhatsApp Error: FONNTEE_API_KEY belum diisi di .env');
            return false;
        }

        try {
            // Kirim Request ke Fonnte
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post($url, [
                'target' => $target,
                'message' => $message,
                'countryCode' => '62', // Otomatis convert 08 ke 62
            ]);

            // Cek Response
            if ($response->successful()) {
                Log::info("WA Terkirim ke $target");
                return true;
            } else {
                Log::error("Gagal kirim WA ke $target: " . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error("WA Exception: " . $e->getMessage());
            return false;
        }
    }
}