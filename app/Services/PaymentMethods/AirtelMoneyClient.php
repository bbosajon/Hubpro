<?php

namespace App\Services\PaymentMethods;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use RuntimeException;

class AirtelMoneyClient
{
    private const COUNTRY = 'UG';

    private const CURRENCY = 'UGX';

    public function __construct(
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $mode,
    ) {}

    public static function normalizeUgandaMsisdn(string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (str_starts_with($digits, '00')) {
            $digits = substr($digits, 2);
        }

        if (str_starts_with($digits, '256')) {
            $digits = substr($digits, 3);
            if (str_starts_with($digits, '0')) {
                $digits = substr($digits, 1);
            }
        } elseif (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        return preg_match('/^\d{9}$/', $digits) === 1 ? $digits : null;
    }

    public static function transactionData(array $response): array
    {
        $data = $response['data'] ?? [];

        if (is_array($data) && is_array($data['transaction'] ?? null)) {
            return $data['transaction'];
        }

        if (is_array($response['transaction'] ?? null)) {
            return $response['transaction'];
        }

        return [];
    }

    public static function classifyStatus(array $response): string
    {
        $transaction = self::transactionData($response);
        $status = strtoupper(trim((string) ($transaction['status'] ?? '')));

        return match ($status) {
            'TS', 'SUCCESS', 'SUCCESSFUL', 'COMPLETED' => 'paid',
            'TF', 'FAILED', 'FAILURE', 'CANCELLED', 'CANCELED' => 'failed',
            default => 'pending',
        };
    }

    public function initiateCollection(string $msisdn, int $amount, string $transactionId, string $reference): array
    {
        $response = $this->requestWithFreshToken(fn (string $token): Response => Http::acceptJson()
            ->asJson()
            ->timeout(20)
            ->connectTimeout(5)
            ->withToken($token)
            ->withHeaders([
                'X-Country' => self::COUNTRY,
                'X-Currency' => self::CURRENCY,
            ])
            ->post($this->baseUrl().'/merchant/v1/payments/', [
                'reference' => $reference,
                'subscriber' => [
                    'country' => self::COUNTRY,
                    'currency' => self::CURRENCY,
                    'msisdn' => (int) $msisdn,
                ],
                'transaction' => [
                    'amount' => $amount,
                    'country' => self::COUNTRY,
                    'currency' => self::CURRENCY,
                    'id' => $transactionId,
                ],
            ]));

        if (! $response->successful()) {
            throw new RuntimeException('Airtel collection request failed.', $response->status());
        }

        $body = $response->json();

        return is_array($body) ? $body : [];
    }

    public function checkPaymentStatus(string $transactionId): array
    {
        $response = $this->requestWithFreshToken(fn (string $token): Response => Http::acceptJson()
            ->timeout(20)
            ->connectTimeout(5)
            ->withToken($token)
            ->withHeaders([
                'X-Country' => self::COUNTRY,
                'X-Currency' => self::CURRENCY,
            ])
            ->get($this->baseUrl().'/standard/v1/payments/'.rawurlencode($transactionId)));

        if (! $response->successful()) {
            throw new RuntimeException('Airtel payment status request failed.', $response->status());
        }

        $body = $response->json();

        return is_array($body) ? $body : [];
    }

    private function accessToken(): string
    {
        $cacheKey = $this->tokenCacheKey();
        $cachedToken = Cache::get($cacheKey);

        if (is_string($cachedToken) && $cachedToken !== '') {
            return $cachedToken;
        }

        $response = Http::acceptJson()
            ->asJson()
            ->timeout(20)
            ->connectTimeout(5)
            ->post($this->baseUrl().'/auth/oauth2/token', [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'client_credentials',
            ]);

        if (! $response->successful()) {
            throw new RuntimeException('Airtel authorization failed.', $response->status());
        }

        $token = $response->json('access_token');
        if (! is_string($token) || $token === '') {
            throw new RuntimeException('Airtel authorization returned no access token.');
        }

        $expiresIn = max(1, (int) $response->json('expires_in', 300) - 60);
        Cache::put($cacheKey, $token, now()->addSeconds($expiresIn));

        return $token;
    }

    private function requestWithFreshToken(callable $request): Response
    {
        $response = $request($this->accessToken());

        if ($response->status() === 401) {
            Cache::forget($this->tokenCacheKey());
            $response = $request($this->accessToken());
        }

        return $response;
    }

    private function tokenCacheKey(): string
    {
        return 'airtel_money.access_token.'.hash(
            'sha256',
            $this->clientId.'|'.$this->clientSecret.'|'.$this->mode,
        );
    }

    private function baseUrl(): string
    {
        return $this->mode === 'live'
            ? 'https://openapi.airtel.africa'
            : 'https://openapiuat.airtel.africa';
    }
}
