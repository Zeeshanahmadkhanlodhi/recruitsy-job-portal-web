<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FindworkController extends Controller
{
    public function index(Request $request)
    {
        $apiKey = config('services.findwork.key') ?: env('FINDWORK_API_KEY');
        if (!$apiKey) {
            return response()->json(['message' => 'Findwork API key is not configured'], 500);
        }

        $query = $request->only([
            'search', 'location', 'remote', 'employment_type', 'company', 'page', 'sort_by'
        ]);

        $http = Http::withHeaders([
            'Authorization' => 'Token ' . $apiKey,
            'Accept' => 'application/json',
        ])->timeout(15);

        $response = $http->get('https://findwork.dev/api/jobs/', $query);

        if ($response->failed()) {
            // Retry without SSL verification (helpful for some Windows PHP setups)
            $response = $http->withoutVerifying()->get('https://findwork.dev/api/jobs/', $query);
        }

        if ($response->failed()) {
            return response()->json([
                'message' => 'Failed to fetch jobs from Findwork',
                'status' => $response->status(),
                'error' => $response->json(),
            ], $response->status());
        }

        $payload = $response->json() ?: [];
        $results = $payload['results'] ?? [];

        $mapped = array_map(function (array $item) {
            return [
                'id' => $item['id'] ?? null,
                'title' => $item['role'] ?? ($item['title'] ?? ''),
                'company_name' => $item['company_name'] ?? '',
                'location' => $item['location'] ?? '',
                'employment_type' => $item['employment_type'] ?? '',
                'created_at' => $item['date_posted'] ?? ($item['created'] ?? null),
                'short_description' => $item['text'] ?? ($item['description'] ?? ''),
                'tags' => $item['keywords'] ?? [],
                'apply_url' => $item['url'] ?? ($item['source'] ?? null),
            ];
        }, $results);

        return response()->json([
            'data' => $mapped,
            'total' => $payload['count'] ?? count($mapped),
            'next_page_url' => $payload['next'] ?? null,
            'prev_page_url' => $payload['previous'] ?? null,
            'current_page' => (int) ($request->integer('page') ?: 1),
        ]);
    }
}


