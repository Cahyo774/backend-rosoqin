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
        Schema::create('komens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('penggunas')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('produks')->onDelete('cascade');
            $table->text('content');
            $table->string('sentiment')->nullable(); // positif / negatif / netral
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komens');
    }
};
