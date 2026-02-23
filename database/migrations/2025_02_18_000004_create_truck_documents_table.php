<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('truck_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('truck_id')->constrained()->cascadeOnDelete();
            $table->string('type'); // muayene, sigorta, kasko, ruhsat, diger
            $table->string('path');
            $table->string('original_name')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('truck_documents');
    }
};
