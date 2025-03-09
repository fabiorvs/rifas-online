<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('raffles', function (Blueprint $table) {
            $table->decimal('price_per_number', 10, 2)->default(0)->after('total_numbers');
        });
    }

    public function down()
    {
        Schema::table('raffles', function (Blueprint $table) {
            $table->dropColumn('price_per_number');
        });
    }
};
