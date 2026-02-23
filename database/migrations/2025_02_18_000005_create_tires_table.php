<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('truck_id')->constrained()->cascadeOnDelete();
            $table->string('position'); // on_sol, on_sag, arka_1, arka_2, yedek, diger
            $table->unsignedInteger('change_km')->nullable();
            $table->date('change_date')->nullable();
            $table->string('brand')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tires');
    }
};
