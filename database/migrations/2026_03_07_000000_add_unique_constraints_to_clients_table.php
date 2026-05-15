<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add unique constraints on email, phone, ninea, and vat_number per company
     */
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Add unique indexes for each field combined with company_id
            // Using unique() which creates a unique index
            // MySQL allows multiple NULL values in unique constraints
            $table->unique(['company_id', 'email']);
            $table->unique(['company_id', 'phone']);
            $table->unique(['company_id', 'ninea']);
            $table->unique(['company_id', 'vat_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropUnique(['company_id', 'email']);
            $table->dropUnique(['company_id', 'phone']);
            $table->dropUnique(['company_id', 'ninea']);
            $table->dropUnique(['company_id', 'vat_number']);
        });
    }
};

