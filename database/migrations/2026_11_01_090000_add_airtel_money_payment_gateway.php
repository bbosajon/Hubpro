<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const GATEWAY_ID = 'bd300f14-1a68-4d1a-8f30-74c97f4f4037';

    public function up(): void
    {
        if (! Schema::hasTable('addon_settings')) {
            return;
        }

        $gatewayExists = DB::table('addon_settings')
            ->where('key_name', 'airtel_money')
            ->where('settings_type', 'payment_config')
            ->exists();

        if ($gatewayExists) {
            return;
        }

        $values = [
            'gateway' => 'airtel_money',
            'mode' => 'test',
            'status' => '0',
            'client_id' => '',
            'client_secret' => '',
        ];

        DB::table('addon_settings')->insert([
            'id' => self::GATEWAY_ID,
            'key_name' => 'airtel_money',
            'live_values' => json_encode($values),
            'test_values' => json_encode($values),
            'settings_type' => 'payment_config',
            'mode' => 'test',
            'is_active' => 0,
            'created_at' => now(),
            'updated_at' => now(),
            'additional_data' => json_encode([
                'gateway_title' => 'Airtel Money',
                'gateway_image' => '',
            ]),
        ]);
    }

    public function down(): void
    {
        // Keep the gateway settings and any credentials configured by the administrator.
    }
};
