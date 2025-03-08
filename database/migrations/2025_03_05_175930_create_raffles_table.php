<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('raffles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Dono da rifa
            $table->string('title');                                          // Título da rifa
            $table->longText('description')->nullable();                      // Descrição detalhada
            $table->string('image')->nullable();                              // Imagem da rifa
            $table->integer('total_numbers');                                 // Quantidade total de números
            $table->enum('status', ['Aberta', 'Finalizada', 'Sorteada'])->default('Aberta');
            $table->integer('winning_number')->nullable(); // Número vencedor
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('raffles');
    }
};
