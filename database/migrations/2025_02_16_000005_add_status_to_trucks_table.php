<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->string('status')->default('aktif')->after('driver_name')->comment('Aktif, Bakımda, Satıldı, Kiralık, Devre Dışı');
        });
    }

    public function down(): void
    {
        Schema::table('trucks', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
