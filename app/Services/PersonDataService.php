<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PersonDataService
{
    /**
     * Busca datos de una persona por DNI en APIs externas.
     * Replica tu lógica de CodeIgniter: Intenta una API, si falla, intenta la otra.
     */
    public function search(string $dni): ?array
    {
        // 1. Intentar API Principal (Reniec / ApisNet)
        $data = $this->consultarApiReniec($dni);
        if ($data) return $data;

        // 2. Intentar API Secundaria (APIsPeru - Fallback)
        $data = $this->consultarApiBackup($dni);
        if ($data) return $data;

        return null;
    }

    /**
     * API 1: ApisNet (Tu 'consultarSegundaAPI' en CI3)
     */
    protected function consultarApiReniec(string $dni): ?array
    {
        try {
            // Token recuperado de tu código anterior
            $token = 'apis-token-10006.1tUMId7aN9QaoM-OlBiwiIB3D-AqcKA8';
            $url = "https://api.apis.net.pe/v2/reniec/dni?numero={$dni}";

            $response = Http::withToken($token)
                ->withHeaders(['Referer' => 'https://apis.net.pe/consulta-dni-api'])
                ->timeout(3) // 3 segundos máximo de espera
                ->get($url);

            if ($response->successful()) {
                $data = $response->json();

                // Validar respuesta
                if (isset($data['numeroDocumento'])) {
                    return [
                        'dni' => $data['numeroDocumento'],
                        'nombres' => $data['nombres'],
                        'apellido_paterno' => $data['apellidoPaterno'],
                        'apellido_materno' => $data['apellidoMaterno'],
                        'source' => 'API 1'
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning("Error API Reniec: " . $e->getMessage());
        }

        return null;
    }

    /**
     * API 2: APIsPeru (Tu 'elAPI' en CI3)
     */
    protected function consultarApiBackup(string $dni): ?array
    {
        try {
            $token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6ImdjYXVuYWg0MDdAZ21haWwuY29tIn0.lGR0VkjP_rwul2lEX1z485sOfArFmEO6xArOG6tSmfk';
            $url = "https://dniruc.apisperu.com/api/v1/dni/{$dni}?token={$token}";

            $response = Http::timeout(3)->get($url);

            if ($response->successful() && $response->json('success')) {
                $data = $response->json();
                return [
                    'dni' => $data['dni'],
                    'nombres' => $data['nombres'],
                    'apellido_paterno' => $data['apellidoPaterno'],
                    'apellido_materno' => $data['apellidoMaterno'],
                    'source' => 'API 2'
                ];
            }
        } catch (\Exception $e) {
            Log::warning("Error API Backup: " . $e->getMessage());
        }

        return null;
    }
}
