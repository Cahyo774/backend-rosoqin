<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('category', [
                'Kardus',
                'Plastik',
                'Kertas',
                'Logam',
                'Elektronik',
                'Kaca',
                'Tekstil',
                'Lainnya'
            ]);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('photo')->nullable();
            $table->string('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Index untuk pencarian dan filter
            $table->index('category');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
