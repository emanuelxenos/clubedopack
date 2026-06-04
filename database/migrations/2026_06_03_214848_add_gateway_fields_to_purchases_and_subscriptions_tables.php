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
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('gateway_id')->nullable()->after('status');
            $table->string('payment_method')->nullable()->after('gateway_id');
            $table->text('pix_qr_code')->nullable()->after('payment_method');
            $table->text('pix_qr_code_base64')->nullable()->after('pix_qr_code');
            $table->string('payment_status')->nullable()->after('pix_qr_code_base64');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('gateway_id')->nullable()->after('status');
            $table->string('payment_method')->nullable()->after('gateway_id');
            $table->text('pix_qr_code')->nullable()->after('payment_method');
            $table->text('pix_qr_code_base64')->nullable()->after('pix_qr_code');
            $table->string('payment_status')->nullable()->after('pix_qr_code_base64');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn(['gateway_id', 'payment_method', 'pix_qr_code', 'pix_qr_code_base64', 'payment_status']);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['gateway_id', 'payment_method', 'pix_qr_code', 'pix_qr_code_base64', 'payment_status']);
        });
    }
};
