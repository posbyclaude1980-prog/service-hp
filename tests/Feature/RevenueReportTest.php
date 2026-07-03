<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RevenueReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_revenue_report_renders_when_payments_use_payment_method_id_schema(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_methods');

        Schema::create('payment_methods', function (Blueprint $table): void {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('service_order_id')->nullable();
            $table->unsignedBigInteger('payment_method_id')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->date('payment_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $response = $this->actingAs($user)->get('/reports/revenue');

        $response->assertOk();
    }
}
