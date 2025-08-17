<?php

namespace App\Support;

class PortalAuth
{
    public static function buildHeaders(string $apiKey, string $apiSecret): array
    {
        $timestamp = (string) now()->getTimestamp();
        $payload = $apiKey.'|'.$timestamp;
        $signature = hash_hmac('sha256', $payload, $apiSecret);

        return [
            'X-API-KEY' => $apiKey,
            'X-API-TIMESTAMP' => $timestamp,
            'X-API-SIGNATURE' => $signature,
            'Accept' => 'application/json',
        ];
    }
}


