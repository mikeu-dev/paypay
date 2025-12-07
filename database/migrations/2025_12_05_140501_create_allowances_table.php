<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('allowances', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // contoh: transportasi, makan, jabatan
            $table->enum('type', ['fixed', 'percentage', 'daily'])->default('fixed');
            $table->decimal('value', 15, 2); // jika percentage, simpan 5 -> berarti 5%
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('allowances');
    }
};
