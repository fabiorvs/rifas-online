<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('raffle_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('raffle_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('number');
            $table->enum('status', ['Disponível', 'Reservado', 'Confirmado'])->default('Disponível');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('raffle_numbers');
    }
};
