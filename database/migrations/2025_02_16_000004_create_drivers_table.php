<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->timestamps();
        });

        Schema::table('trucks', function (Blueprint $table) {
            $table->foreignId('driver_id')->nullable()->after('model')->constrained('drivers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
        });
        Schema::dropIfExists('drivers');
    }
};
