<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BiteshipService
{
    protected $apiKey;
    protected $baseUrl;
    protected $originAreaId;

    public function __construct()
    {
        $this->apiKey = config('biteship.api_key');
        $this->baseUrl = config('biteship.base_url');
        $this->originAreaId = config('biteship.origin_area_id');
    }

    /**
     * Search Area by name/postal code
     * Endpoint: GET /v1/maps/areas
     */
    public function searchArea($query)
    {
        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json'
            ])->get($this->baseUrl . '/maps/areas', [
                'countries' => 'ID',
                'input' => $query,
                'type' => 'single'
            ]);

            if ($response->successful()) {
                return $response->json()['areas'] ?? [];
            } else {
                Log::warning('Biteship Search Area failed: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Biteship Search Area Error: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Calculate Shipping Cost
     * Endpoint: POST /v1/rates/couriers
     */
    public function getRates($destinationAreaId, $weight, $items = [])
    {
        try {
            $couriers = implode(',', config('biteship.couriers', ['jne', 'pos', 'tiki']));
            
            // Weight in Biteship is usually in Grams based on documentation snippet "weight": 200
            // But let's verify. The snippet says "weight": 200 (200 grams).
            // RajaOngkir used grams.
            
            $payload = [
                'origin_area_id' => $this->originAreaId,
                'destination_area_id' => $destinationAreaId,
                'couriers' => $couriers,
                'items' => count($items) > 0 ? $items : [
                    [
                        'name' => 'Package',
                        'value' => 100000, // Default value if not provided
                        'weight' => (int)$weight, // Grams
                        'quantity' => 1
                    ]
                ]
            ];

            // Fallback if origin_area_id is missing but postal code exists
            if (empty($this->originAreaId) && config('biteship.origin_postal_code')) {
                unset($payload['origin_area_id']);
                $payload['origin_postal_code'] = (int)config('biteship.origin_postal_code');
            }

            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json'
            ])->post($this->baseUrl . '/rates/couriers', $payload);

            if ($response->successful()) {
                $pricing = $response->json()['pricing'] ?? [];

                // Filter out sameday and nextday services for ALL couriers.
                // Biteship normalizes the 'type' field across all carriers,
                // so this works universally (JNE, SiCepat, Anteraja, etc.).
                $pricing = array_values(array_filter($pricing, function ($rate) {
                    $type = strtolower($rate['type'] ?? '');
                    return !in_array($type, ['same_day', 'sameday', 'next_day', 'nextday']);
                }));

                return $pricing;
            } else {
                Log::warning('Biteship Rates API failed: ' . $response->body());
                return ['error' => $response->json()['error'] ?? 'Unknown error'];
            }
        } catch (\Exception $e) {
            Log::error('Biteship Rates API Error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }
}
