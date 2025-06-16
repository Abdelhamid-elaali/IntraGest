<?php

use Illuminate\Support\Facades\Route;
use App\Models\Criteria;

// Test API endpoint for criteria
Route::get('/test/criteria-api', function () {
    try {
        // Test the API endpoint
        $client = new \GuzzleHttp\Client();
        $response = $client->get(url('/api/criteria'), [
            'query' => ['category' => 'geographical'],
            'headers' => [
                'Accept' => 'application/json',
                'X-Requested-With' => 'XMLHttpRequest'
            ]
        ]);
        
        return [
            'status' => 'success',
            'data' => json_decode($response->getBody()->getContents(), true)
        ];
    } catch (\Exception $e) {
        return [
            'status' => 'error',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ];
    }
});
