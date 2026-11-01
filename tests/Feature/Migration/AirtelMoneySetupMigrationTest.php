<?php

namespace Tests\Feature\Migration;

use App\Enums\GlobalConstant;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AirtelMoneySetupMigrationTest extends TestCase
{
    use DatabaseTransactions;

    #[Test]
    public function migrations_register_the_customer_gateway_and_vendor_withdrawal_method(): void
    {
        $this->skipWhenNotOnTheContractDatabase();

        DB::table('addon_settings')
            ->where('key_name', 'airtel_money')
            ->where('settings_type', 'payment_config')
            ->delete();

        DB::table('withdrawal_methods')
            ->where('method_name', 'Airtel Money Uganda')
            ->where('vendor_status', 1)
            ->delete();

        $this->gatewayMigration()->up();
        $this->withdrawalMethodMigration()->up();
        $this->gatewayMigration()->up();
        $this->withdrawalMethodMigration()->up();

        $gateway = DB::table('addon_settings')
            ->where('key_name', 'airtel_money')
            ->where('settings_type', 'payment_config')
            ->first();

        $this->assertNotNull($gateway);
        $this->assertSame(0, (int) $gateway->is_active);
        $this->assertContains('airtel_money', GlobalConstant::DEFAULT_PAYMENT_GATEWAYS);

        $withdrawalMethod = DB::table('withdrawal_methods')
            ->where('method_name', 'Airtel Money Uganda')
            ->where('vendor_status', 1)
            ->first();

        $this->assertNotNull($withdrawalMethod);
        $this->assertSame(1, (int) $withdrawalMethod->is_active);
        $this->assertSame(0, (int) $withdrawalMethod->customer_status);
        $this->assertSame(
            [[
                'input_type' => 'phone',
                'input_name' => 'phone',
                'placeholder' => 'Enter your Airtel Money number, e.g. +256 700 123456',
                'is_required' => 1,
            ]],
            json_decode($withdrawalMethod->method_fields, true, 512, JSON_THROW_ON_ERROR),
        );

        $this->assertSame(
            1,
            DB::table('withdrawal_methods')
                ->where('method_name', 'Airtel Money Uganda')
                ->where('vendor_status', 1)
                ->count(),
        );
    }

    #[Test]
    public function gateway_migration_preserves_admin_credentials(): void
    {
        $this->skipWhenNotOnTheContractDatabase();

        $settings = [
            'gateway' => 'airtel_money',
            'mode' => 'test',
            'status' => '0',
            'client_id' => 'configured-client-id',
            'client_secret' => 'configured-client-secret',
        ];

        DB::table('addon_settings')->updateOrInsert(
            ['key_name' => 'airtel_money', 'settings_type' => 'payment_config'],
            [
                'id' => 'bd300f14-1a68-4d1a-8f30-74c97f4f4037',
                'live_values' => json_encode($settings, JSON_THROW_ON_ERROR),
                'test_values' => json_encode($settings, JSON_THROW_ON_ERROR),
                'mode' => 'test',
                'is_active' => 0,
                'additional_data' => json_encode(['gateway_title' => 'Airtel Money', 'gateway_image' => ''], JSON_THROW_ON_ERROR),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        $this->gatewayMigration()->up();

        $gateway = DB::table('addon_settings')
            ->where('key_name', 'airtel_money')
            ->where('settings_type', 'payment_config')
            ->first();

        $this->assertNotNull($gateway);
        $this->assertSame($settings, json_decode($gateway->live_values, true, 512, JSON_THROW_ON_ERROR));
        $this->assertSame($settings, json_decode($gateway->test_values, true, 512, JSON_THROW_ON_ERROR));
    }

    private function gatewayMigration(): object
    {
        return require base_path('database/migrations/2026_11_01_090000_add_airtel_money_payment_gateway.php');
    }

    private function withdrawalMethodMigration(): object
    {
        return require base_path('database/migrations/2026_11_01_100000_add_airtel_money_vendor_withdrawal_method.php');
    }

    private function skipWhenNotOnTheContractDatabase(): void
    {
        if (str_ends_with(DB::connection()->getDatabaseName(), '_snapshot_test')) {
            $this->markTestSkipped('Refusing to write fixtures into the API snapshot clone.');
        }
    }
}
