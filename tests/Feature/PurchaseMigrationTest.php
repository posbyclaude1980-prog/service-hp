<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PurchaseMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_purchase_related_tables_exist(): void
    {
        $this->assertTrue(Schema::hasTable('purchases'));
        $this->assertTrue(Schema::hasTable('purchase_items'));
        $this->assertTrue(Schema::hasTable('stock_mutations'));
    }
}
