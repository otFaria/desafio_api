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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Colunas do pagamento
            $table->foreignId('subscription_id')->constrained('subscriptions'); // Liga o pagamento a um contrato
            $table->decimal('amount', 8, 2);                                  // O valor que foi pago
            $table->date('payment_date');                                     // Data que o pagamento ocorreu
            $table->string('payment_method')->default('pix');                 // Forma de pagamento (PIX simulado)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
