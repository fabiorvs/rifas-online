<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('rifas', function (Blueprint $table) {
            $table->text('payment_details')->nullable()->after('status');
        });
    }

    public function down()
    {
        Schema::table('rifas', function (Blueprint $table) {
            $table->dropColumn('payment_details');
        });
    }
};
