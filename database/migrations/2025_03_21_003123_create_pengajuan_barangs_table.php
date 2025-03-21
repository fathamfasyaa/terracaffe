<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pengajuan_barangs', function (Blueprint $table) {
            $table->id();
            $table->string('pengaju'); // Nama Pengaju
            $table->string('nama_barang');
            $table->text('deskripsi')->nullable();
            $table->integer('qty')->default(1); // Quantity
            $table->date('tgl_pengajuan');
            $table->boolean('status')->default(0); // 0 = belum terpenuhi, 1 = terpenuhi
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengajuan_barangs');
    }
};
