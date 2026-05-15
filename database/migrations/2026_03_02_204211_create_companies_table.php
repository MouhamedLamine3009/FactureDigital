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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('legal_name')->nullable();
            $table->string('siret', 14)->unique()->nullable();
            $table->string('vat_number')->nullable();
            $table->string('ape_code', 10)->nullable();
            $table->text('address')->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->string('city')->nullable();
            $table->string('country')->default('France');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('signature_path')->nullable();
            $table->string('bank_iban')->nullable();
            $table->string('bank_bic')->nullable();
            $table->string('bank_name')->nullable();
            $table->boolean('vat_applicable')->default(false);
            $table->string('invoice_prefix')->default('FACT-');
            $table->string('quote_prefix')->default('DEV-');
            $table->integer('invoice_next_number')->default(1);
            $table->integer('quote_next_number')->default(1);
            $table->text('terms_conditions')->nullable();
            $table->text('footer_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
