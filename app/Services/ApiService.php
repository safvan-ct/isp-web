<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiService
{
    public function get($url)
    {
        try {
            $cacertPath = env('APP_ENV') == 'local' ? 'C:/wamp64/bin/php/php8.2.18/extras/ssl/cacert.pem' : '';
            $response   = env('APP_ENV') == 'local' ? Http::withOptions(['verify' => $cacertPath])->get($url) : Http::timeout(180)->get($url);

            if ($response->successful()) {
                return ['status' => $response->status(), 'result' => $response->json()];
            }

            return ['status' => $response->status(), 'message' => 'Failed to retrieve data.'];
        } catch (\Exception $e) {
            return ['status' => 500, 'message' => 'Something went wrong: ' . $e->getMessage()];
        }
    }
}
