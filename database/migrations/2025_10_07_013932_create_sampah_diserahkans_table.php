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
        Schema::create('sampah_diserahkans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users');
            $table->foreignId('id_lokasi')->constrained('lokasi_asals');
            $table->foreignId('id_jenis')->constrained('jenis');
            $table->integer('jumlah_berat');
            $table->date('tgl');
            $table->foreignId('id_diserahkan')->constrained('tujuan_sampahs');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sampah_diserahkans');
    }
};
