<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pack_id')->constrained()->onDelete('cascade');
            $table->decimal('amount_paid', 10, 2);
            $table->string('gateway_transaction_id')->nullable();
            $table->enum('status', ['pending', 'confirmed', 'refunded', 'failed'])->default('pending');
            $table->timestamps();

            $table->unique(['user_id', 'pack_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
