<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_work_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('service_order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->string('action', 50);
            $table->string('title');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('logged_at');
            $table->timestamps();

            $table->index(['service_order_id', 'logged_at']);
            $table->index('action');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_work_logs');
    }
};
