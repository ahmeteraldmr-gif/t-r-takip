<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->unsignedInteger('start_km')->nullable()->after('notes');
            $table->unsignedInteger('end_km')->nullable()->after('start_km');
            $table->string('cargo_type')->nullable()->after('end_km');
            $table->decimal('load_weight', 10, 2)->nullable()->after('cargo_type');
            $table->date('loading_date')->nullable()->after('load_weight');
            $table->date('unloading_date')->nullable()->after('loading_date');
            $table->string('receiver_name')->nullable()->after('unloading_date');
        });
    }

    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn(['start_km', 'end_km', 'cargo_type', 'load_weight', 'loading_date', 'unloading_date', 'receiver_name']);
        });
    }
};
