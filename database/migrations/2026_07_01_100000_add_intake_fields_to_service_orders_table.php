<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('service_orders', function (Blueprint $table): void {
            $table->string('contact_phone', 20)->nullable()->after('customer_id');
            $table->string('device_brand')->nullable()->after('device_id');
            $table->string('device_model')->nullable()->after('device_brand');
            $table->string('imei', 50)->nullable()->after('device_model');
            $table->string('color', 50)->nullable()->after('imei');
            $table->string('device_password')->nullable()->after('color');
            $table->text('completeness')->nullable()->after('complaint');
            $table->decimal('estimated_cost', 15, 2)->default(0)->after('service_fee');
        });
    }

    public function down(): void
    {
        Schema::table('service_orders', function (Blueprint $table): void {
            $table->dropColumn([
                'contact_phone',
                'device_brand',
                'device_model',
                'imei',
                'color',
                'device_password',
                'completeness',
                'estimated_cost',
            ]);
        });
    }
};
