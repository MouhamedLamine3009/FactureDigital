<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This migration fixes the unique constraint on documents.number
     * to be scoped per company instead of globally unique.
     * 
     * Before: number was globally unique across all companies
     * After: number is unique only within each company
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Drop the global unique constraint on 'number'
            $table->dropUnique(['number']);
            
            // Add company-scoped unique constraint
            // Each company can have their own document numbering
            $table->unique(['company_id', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            // Drop the company-scoped unique constraint
            $table->dropUnique(['company_id', 'number']);
            
            // Restore the global unique constraint on 'number'
            $table->string('number')->unique()->change();
        });
    }
};

