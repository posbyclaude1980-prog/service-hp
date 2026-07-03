<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CashierReceiptTest extends TestCase
{
    use RefreshDatabase;

    public function test_cashier_receipt_preview_renders_manual_service_data(): void
    {
        $user = new User([
            'id' => 1,
            'name' => 'Kasir Demo',
            'email' => 'kasir@example.com',
            'password' => bcrypt('secret'),
        ]);

        $response = $this->actingAs($user)
            ->post(route('dashboard.cashier.print'), [
                'service_description' => 'Ganti LCD',
                'customer_name' => 'Budi',
                'customer_phone' => '08123456789',
                'service_fee' => 150000,
            ]);

        $response->assertOk();
        $response->assertSee('Ganti LCD');
        $response->assertSee('Budi');
        $response->assertSee('08123456789');
        $response->assertSee('Rp 150.000');
    }
}
