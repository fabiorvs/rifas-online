<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('raffles', function (Blueprint $table) {
            $table->index('user_id', 'idx_raffles_user'); // Índice para melhorar consultas por usuário
        });

        Schema::table('raffle_numbers', function (Blueprint $table) {
            $table->index('raffle_id', 'idx_raffle_numbers'); // Índice para melhorar consultas por rifa
        });
    }

    public function down()
    {
        Schema::table('raffles', function (Blueprint $table) {
            $table->dropIndex('idx_raffles_user'); // Remove o índice se precisar reverter
        });

        Schema::table('raffle_numbers', function (Blueprint $table) {
            $table->dropIndex('idx_raffle_numbers'); // Remove o índice se precisar reverter
        });
    }
};
