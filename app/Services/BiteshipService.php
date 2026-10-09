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

                // Filter out instant, sameday, and nextday/overnight services for ALL couriers.
                // Biteship returns different fields across couriers:
                // - service_type: 'overnight', 'instant', 'same_day'
                // - type / courier_service_code: 'yes' (JNE YES), 'best' (SiCepat BEST), 'ons' (TIKI ONS), 'instant'
                // - courier_service_name: 'Yakin Esok Sampai (YES)', 'Instant', etc.
                // - duration: 'X hours' / 'X jam'
                $blockedKeywords = [
                    'instant',
                    'same_day',
                    'sameday',
                    'next_day',
                    'nextday',
                    'overnight',
                    'one_day',
                    'oneday'
                ];
                $blockedCodes = array_merge($blockedKeywords, ['yes', 'best', 'ons', 'sds', 'nd', 'sd']);

                $pricing = array_values(array_filter($pricing, function ($rate) use ($blockedKeywords, $blockedCodes) {
                    $serviceType = strtolower(trim((string)($rate['service_type'] ?? '')));
                    $type = strtolower(trim((string)($rate['type'] ?? '')));
                    $code = strtolower(trim((string)($rate['courier_service_code'] ?? '')));
                    $name = strtolower(trim((string)($rate['courier_service_name'] ?? '')));
                    $duration = strtolower(trim((string)($rate['duration'] ?? '')));

                    if (in_array($serviceType, $blockedKeywords, true)) {
                        return false;
                    }
                    if (in_array($type, $blockedCodes, true)) {
                        return false;
                    }
                    if (in_array($code, $blockedCodes, true)) {
                        return false;
                    }

                    // Block by service name keyword (YES, BEST, ONS, Instant, Same Day, Next Day, Besok Sampai)
                    if (preg_match('/\b(yes|best|ons|instant|same[ -]?day|next[ -]?day|overnight|besok sampai)\b/i', $name)) {
                        return false;
                    }

                    // Block by duration measured in hours/jam (instant / sameday)
                    if (preg_match('/\b(hours?|jam)\b/i', $duration)) {
                        return false;
                    }

                    return true;
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
