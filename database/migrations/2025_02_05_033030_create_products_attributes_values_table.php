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
        Schema::create('products_attributes_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('products_attributes_id')->constrained('products_attributes')->cascadeOnDelete();
            $table->string('value');
            $table->string('code')->nullabe();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_attributes_values');
    }
};
