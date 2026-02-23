<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->decimal('amount', 12, 2);
            $table->string('origin')->nullable();
            $table->string('destination')->nullable();
            $table->string('cargo_type')->nullable();
            $table->decimal('load_weight', 10, 2)->nullable();
            $table->date('valid_until')->nullable();
            $table->string('status')->default('taslak'); // taslak, gonderildi, onaylandi, reddedildi
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
