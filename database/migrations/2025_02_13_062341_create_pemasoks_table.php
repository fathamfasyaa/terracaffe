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
        Schema::create('pemasok', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pemasok', 50);
            $table->text('alamat')->nullable();
            $table->string('nomor_telepon', 15)->nullable();
            $table->string('email', 100)->unique()->nullable();
            $table->text('catatan')->nullable(); // Tambahkan kolom catatan
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemasok');
    }
};
