<?php

namespace Tests\Unit;

use App\Services\PaymentMethods\AirtelMoneyClient;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class AirtelMoneyClientTest extends TestCase
{
    #[DataProvider('ugandaPhoneNumbers')]
    public function test_it_normalizes_uganda_phone_numbers(string $input, string $expected): void
    {
        $this->assertSame($expected, AirtelMoneyClient::normalizeUgandaMsisdn($input));
    }

    public static function ugandaPhoneNumbers(): array
    {
        return [
            'local leading zero' => ['0708 123 456', '708123456'],
            'international code' => ['+256 708 123 456', '708123456'],
            'international code with trunk zero' => ['+256 (0) 708-123-456', '708123456'],
            'digits only' => ['708123456', '708123456'],
        ];
    }

    #[DataProvider('invalidPhoneNumbers')]
    public function test_it_rejects_phone_numbers_that_are_not_uganda_length(string $input): void
    {
        $this->assertNull(AirtelMoneyClient::normalizeUgandaMsisdn($input));
    }

    public static function invalidPhoneNumbers(): array
    {
        return [
            'empty' => [''],
            'too short' => ['07081234'],
            'foreign country' => ['+254 700 123 456'],
            'too long' => ['0708 123 456 7'],
        ];
    }

    #[DataProvider('providerStatuses')]
    public function test_it_classifies_airtel_transaction_statuses(array $response, string $expected): void
    {
        $this->assertSame($expected, AirtelMoneyClient::classifyStatus($response));
    }

    public static function providerStatuses(): array
    {
        return [
            'successful' => [['data' => ['transaction' => ['status' => 'TS']]], 'paid'],
            'in progress' => [['data' => ['transaction' => ['status' => 'TIP']]], 'pending'],
            'ambiguous' => [['data' => ['transaction' => ['status' => 'TA']]], 'pending'],
            'failed' => [['data' => ['transaction' => ['status' => 'TF']]], 'failed'],
            'unknown is not success' => [['data' => ['transaction' => ['status' => 'UNKNOWN']]], 'pending'],
        ];
    }
}
