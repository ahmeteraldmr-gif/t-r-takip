<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fuel_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->decimal('liters', 10, 2);
            $table->decimal('price_per_liter', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('liters_used', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fuel_expenses');
    }
};
