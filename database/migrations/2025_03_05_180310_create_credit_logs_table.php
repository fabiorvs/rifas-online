<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('credit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2); // Valor da transação
            $table->enum('type', ['Entrada', 'Saída']); // Tipo da transação
            $table->text('observation')->nullable(); // Detalhes sobre a transação
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('credit_logs');
    }
};
