<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trip_stops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trip_id')->constrained()->cascadeOnDelete();
            $table->string('location'); // Duraklama yeri (şehir/yer adı)
            $table->dateTime('stopped_at'); // Ne zaman durdu
            $table->dateTime('left_at')->nullable(); // Ne zaman yola çıktı (boşsa hâlâ duruyor)
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_stops');
    }
};
