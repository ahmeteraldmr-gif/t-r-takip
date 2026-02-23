<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('truck_id')->constrained()->cascadeOnDelete();
            $table->date('departure_date');
            $table->string('destination');
            $table->enum('status', ['planned', 'in_progress', 'completed', 'cancelled'])->default('planned');
            $table->decimal('commission_amount', 10, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
