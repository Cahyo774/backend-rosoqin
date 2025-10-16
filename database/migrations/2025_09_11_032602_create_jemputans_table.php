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
        Schema::create('jemputans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('penggunas')->onDelete('cascade');
            $table->string('alamat');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->enum('status', ['pending', 'proses', 'selesai'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jemputans');
    }
};
