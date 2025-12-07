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
            Schema::table('clients', function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->constrained()->cascadeOnDelete();
            });
            Schema::table('projects', function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->constrained()->cascadeOnDelete();
            });
            Schema::table('invoices', function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->constrained()->cascadeOnDelete();
            });
            Schema::table('employees', function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->constrained()->cascadeOnDelete();
            });
            Schema::table('leaves', function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->constrained()->cascadeOnDelete();
            });
            Schema::table('payrolls', function (Blueprint $table) {
                $table->foreignId('team_id')->nullable()->constrained()->cascadeOnDelete();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tables', function (Blueprint $table) {
            //
        });
    }
};
