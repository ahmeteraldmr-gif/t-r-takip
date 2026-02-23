<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->decimal('revenue_amount', 12, 2)->nullable()->after('commission_amount');
            $table->string('payment_status')->default('bekliyor')->after('revenue_amount'); // bekliyor, tahsil_edildi, kismi
            $table->foreignId('customer_id')->nullable()->after('truck_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn(['revenue_amount', 'payment_status', 'customer_id']);
        });
    }
};
