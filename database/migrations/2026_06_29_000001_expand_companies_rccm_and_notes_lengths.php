<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Fix SQLSTATE[22001]: Data too long for column 'rccm'
            $table->string('rccm', 255)->nullable()->change();

            // These were also causing potential truncation depending on UI values.
            $table->text('terms_conditions')->nullable()->change();
            $table->text('footer_notes')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            // Restore to safer defaults (fallback if previous schema had shorter strings)
            $table->string('rccm', 50)->nullable()->change();
            $table->text('terms_conditions')->nullable()->change();
            $table->text('footer_notes')->nullable()->change();
        });
    }
};

