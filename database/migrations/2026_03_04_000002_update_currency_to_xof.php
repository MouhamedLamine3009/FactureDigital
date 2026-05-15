<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all existing companies to use XOF and CFA
        // This handles EUR, CFA, or any other currency that might be set
        DB::table('companies')
            ->whereNotIn('currency', ['XOF'])
            ->update(['currency' => 'XOF']);

        DB::table('companies')
            ->whereNotIn('currency_symbol', ['FCFA', 'CFA'])
            ->update(['currency_symbol' => 'FCFA']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to EUR
        DB::table('companies')
            ->where('currency', 'XOF')
            ->update(['currency' => 'EUR']);

        DB::table('companies')
            ->where('currency_symbol', 'FCFA')
            ->update(['currency_symbol' => '€']);
    }
};

