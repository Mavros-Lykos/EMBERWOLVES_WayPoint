<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RoutingService
{
    /**
     * Get distance between two coordinates in meters using Google Maps API.
     * 
     * @param float $originLat
     * @param float $originLng
     * @param float $destLat
     * @param float $destLng
     * @return int Distance in meters (or 0 if failed)
     */
    public function getDistance($originLat, $originLng, $destLat, $destLng)
    {
        $apiKey = config('services.google.maps_api_key');

        if (!$apiKey) {
            Log::warning('Google Maps API key is missing. Routing fell back to 0 meters.');
            return 0;
        }

        // Use the Modern Google Maps Routes API (v2)
        $url = "https://routes.googleapis.com/distanceMatrix/v2:computeRouteMatrix";
        
        $response = Http::withHeaders([
            'X-Goog-Api-Key' => $apiKey,
            'X-Goog-FieldMask' => 'originIndex,destinationIndex,duration,distanceMeters,status',
        ])->post($url, [
            'origins' => [
                [
                    'waypoint' => [
                        'location' => [
                            'latLng' => ['latitude' => $originLat, 'longitude' => $originLng]
                        ]
                    ]
                ]
            ],
            'destinations' => [
                [
                    'waypoint' => [
                        'location' => [
                            'latLng' => ['latitude' => $destLat, 'longitude' => $destLng]
                        ]
                    ]
                ]
            ],
            'travelMode' => 'DRIVE',
            'routingPreference' => 'TRAFFIC_AWARE'
        ]);
        
        if ($response->successful()) {
            $data = $response->json();
            
            if (isset($data[0]['distanceMeters'])) {
                // Returns distance in meters
                return $data[0]['distanceMeters']; 
            }
        }

        Log::error('Google Maps API routing failed', ['response' => $response->body()]);
        
        return 0; // Fallback
    }
}
