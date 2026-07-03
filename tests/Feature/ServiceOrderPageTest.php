<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceOrderPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_order_index_does_not_show_create_button(): void
    {
        $response = $this->get('/service-orders');

        $response->assertStatus(200);
        $response->assertDontSee('Penerimaan Baru');
    }
}
