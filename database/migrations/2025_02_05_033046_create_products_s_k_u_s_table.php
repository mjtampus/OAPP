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
        Schema::create('products_s_k_u_s', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->foreignId('products_id')->constrained()->cascadeOnDelete();
            $table->json('attributes');
            $table->string('sku_image_dir')->nullable();
            $table->bigInteger('stock');
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_s_k_u_s');
    }
};
