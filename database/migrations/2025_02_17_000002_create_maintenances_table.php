<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('truck_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // yağ_değişimi, fren, muayene, lastik, diğer
            $table->date('due_date');
            $table->date('last_done_date')->nullable();
            $table->unsignedInteger('last_done_km')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
