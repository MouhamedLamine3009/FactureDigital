<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Increase ninea column size to accommodate longer values (up to 20 chars with spaces)
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // NINEA in Senegal can be up to 14 digits, but user is entering formatted values with spaces
            // Increase to 25 to accommodate formatting like "123 456 789 00000"
            $table->string('ninea', 25)->nullable()->change();
        });

        Schema::table('companies', function (Blueprint $table) {
            // Same for companies table
            if (Schema::hasColumn('companies', 'ninea')) {
                $table->string('ninea', 25)->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('ninea', 14)->nullable()->change();
        });

        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('companies', 'ninea')) {
                $table->string('ninea', 14)->nullable()->change();
            }
        });
    }
};

