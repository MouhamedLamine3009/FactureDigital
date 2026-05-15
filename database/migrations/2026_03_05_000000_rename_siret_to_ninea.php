<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Rename French fields to Senegalese equivalents
     */
    public function up(): void
    {
        // Companies table: rename siret to ninea, ape_code to rccm, update country default
        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('companies', 'siret')) {
                $table->renameColumn('siret', 'ninea');
            }
            if (Schema::hasColumn('companies', 'ape_code')) {
                $table->renameColumn('ape_code', 'rccm');
            }
            // Change default country from France to Senegal
            $table->string('country')->default('Sénégal')->change();
        });

        // Clients table: rename siret to ninea, update country default
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'siret')) {
                $table->renameColumn('siret', 'ninea');
            }
            // Change default country from France to Senegal
            $table->string('country')->default('Sénégal')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Companies table: rename back
        Schema::table('companies', function (Blueprint $table) {
            if (Schema::hasColumn('companies', 'ninea')) {
                $table->renameColumn('ninea', 'siret');
            }
            if (Schema::hasColumn('companies', 'rccm')) {
                $table->renameColumn('rccm', 'ape_code');
            }
            $table->string('country')->default('France')->change();
        });

        // Clients table: rename back
        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'ninea')) {
                $table->renameColumn('ninea', 'siret');
            }
            $table->string('country')->default('France')->change();
        });
    }
};

