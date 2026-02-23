<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->string('origin')->nullable()->after('destination')->comment('Nereden (örn: Ankara)');
            $table->json('stopovers')->nullable()->after('origin')->comment('Duracağı yerler (örn: Konya, Mersin)');
        });
    }

    public function down(): void
    {
        Schema::table('trips', function (Blueprint $table) {
            $table->dropColumn(['origin', 'stopovers']);
        });
    }
};
