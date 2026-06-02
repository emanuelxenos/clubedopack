<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('pix_key')->nullable()->after('remember_token');
            $table->string('pix_key_type')->nullable()->after('pix_key'); // cpf, email, phone, random
            $table->decimal('balance_available', 10, 2)->default(0.00)->after('pix_key_type');
            $table->decimal('balance_pending', 10, 2)->default(0.00)->after('balance_available');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['pix_key', 'pix_key_type', 'balance_available', 'balance_pending']);
        });
    }
};
