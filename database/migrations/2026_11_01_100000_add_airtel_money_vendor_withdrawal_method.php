<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('withdrawal_methods') || ! Schema::hasColumn('withdrawal_methods', 'vendor_status')) {
            throw new RuntimeException('The vendor withdrawal methods schema must be migrated before adding Airtel Money.');
        }

        $methodExists = DB::table('withdrawal_methods')
            ->where('method_name', 'Airtel Money Uganda')
            ->where('vendor_status', 1)
            ->exists();

        if ($methodExists) {
            return;
        }

        DB::table('withdrawal_methods')->insert([
            'method_name' => 'Airtel Money Uganda',
            'method_fields' => json_encode([
                [
                    'input_type' => 'phone',
                    'input_name' => 'phone',
                    'placeholder' => 'Enter your Airtel Money number, e.g. +256 700 123456',
                    'is_required' => 1,
                ],
            ], JSON_THROW_ON_ERROR),
            'is_default' => 0,
            'is_active' => 1,
            'vendor_status' => 1,
            'customer_status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        // Preserve payout-method details and any vendor withdrawal records that reference them.
    }
};
