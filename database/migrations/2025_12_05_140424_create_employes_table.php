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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique(); // NIP / ID Karyawan
            $table->string('name');
            $table->string('position'); // jabatan
            $table->string('department')->nullable();
            $table->date('hire_date')->nullable();
            $table->decimal('base_salary', 15, 2); // gaji pokok
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
