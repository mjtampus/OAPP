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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique()->nullable();
            // $table->string('order_description');
            // $table->integer('quantity');
            $table->integer('amount');
            $table->boolean('is_paid')->default(false);
            $table->string('payment_method')->nullable();
            $table->enum('order_status', ['pending','processing', 'shipped', 'in_transit' , 'delivered', 'cancelled'])->default('pending');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
