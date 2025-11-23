<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PersonDataService
{
    /**
     * Busca datos de una persona por DNI en múltiples fuentes.
     * Retorna un array estandarizado o null.
     */
    public function search(string $dni): ?array
    {
        // 1. Validación básica
        if (!preg_match('/^\d{8}$/', $dni)) {
            return null;
        }

        // 2. Intentar API Principal (Reniec / ApisNet - Según tu código CI3)
        $data = $this->consultarApiReniec($dni);
        if ($data) return $data;

        // 3. Intentar API Secundaria (APIsPeru - Fallback)
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
            $token = 'apis-token-10006.1tUMId7aN9QaoM-OlBiwiIB3D-AqcKA8'; // Tu token
            $url = "https://api.apis.net.pe/v2/reniec/dni?numero={$dni}";

            $response = Http::withToken($token)
                ->withHeaders(['Referer' => 'https://apis.net.pe/consulta-dni-api'])
                ->timeout(5)
                ->get($url);

            if ($response->successful()) {
                $data = $response->json();
                
                // Validar respuesta válida
                if (isset($data['numeroDocumento'])) {
                    return [
                        'dni' => $data['numeroDocumento'],
                        'nombres' => $data['nombres'],
                        'apellido_paterno' => $data['apellidoPaterno'],
                        'apellido_materno' => $data['apellidoMaterno'],
                        'found' => true
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::error("Error API Reniec: " . $e->getMessage());
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

            $response = Http::timeout(5)->get($url);

            if ($response->successful() && $response->json('success')) {
                $data = $response->json();
                return [
                    'dni' => $data['dni'],
                    'nombres' => $data['nombres'],
                    'apellido_paterno' => $data['apellidoPaterno'],
                    'apellido_materno' => $data['apellidoMaterno'],
                    'found' => true
                ];
            }
        } catch (\Exception $e) {
            Log::error("Error API Backup: " . $e->getMessage());
        }

        return null;
    }
}