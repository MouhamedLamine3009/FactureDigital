<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->index(['company_id', 'type', 'status', 'paid_at'], 'doc_company_type_status_paid_idx');
            $table->index(['company_id', 'type', 'issue_date'], 'doc_company_type_issue_idx');
            $table->index(['company_id', 'type', 'created_at'], 'doc_company_type_created_idx');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->index(['company_id', 'created_at'], 'clients_company_created_idx');
        });
    }

    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex('doc_company_type_status_paid_idx');
            $table->dropIndex('doc_company_type_issue_idx');
            $table->dropIndex('doc_company_type_created_idx');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropIndex('clients_company_created_idx');
        });
    }
};