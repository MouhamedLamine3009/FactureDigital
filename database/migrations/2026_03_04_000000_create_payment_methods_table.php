<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['bank_transfer', 'check', 'cash', 'credit_card', 'paypal', 'other']);
            $table->string('name'); // Nom affiché (ex: "Compte Courant", "Chèque à l'ordre de...")
            $table->text('details')->nullable(); // JSON: iban, bic, bank_name, etc.
            $table->text('instructions')->nullable(); // Instructions de paiement pour le client
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['company_id', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};

