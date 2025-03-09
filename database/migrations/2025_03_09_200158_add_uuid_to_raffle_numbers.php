<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('raffle_numbers', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });
    }

    public function down()
    {
        Schema::table('raffle_numbers', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
