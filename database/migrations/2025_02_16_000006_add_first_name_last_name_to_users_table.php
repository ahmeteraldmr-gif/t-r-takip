<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('id');
            $table->string('last_name')->nullable()->after('first_name');
        });

        foreach (DB::table('users')->get() as $user) {
            $parts = explode(' ', trim($user->name ?? ''), 2);
            $first = $parts[0] ?? 'Kullanıcı';
            $last = $parts[1] ?? '';
            DB::table('users')->where('id', $user->id)->update([
                'first_name' => $first,
                'last_name' => $last,
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
        });
    }
};
