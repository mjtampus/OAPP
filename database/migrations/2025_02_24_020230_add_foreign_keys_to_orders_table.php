<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // $table->foreignId('cart_id')->constrained()->onDelete('cascade')->nullable();
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // $table->dropForeign(['cart_id']);
            // $table->dropColumn(['cart_id']);
        });
    }
};
