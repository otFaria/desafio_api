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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            // Colunas do contrato
            $table->foreignId('user_id')->constrained('users'); // Liga o contrato a um usuário
            $table->foreignId('plan_id')->constrained('plans'); // Liga o contrato a um plano
            $table->date('start_date');                         // Data da contratação
            $table->string('status')->default('active');        // Status (ex: 'active', 'cancelled')

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
