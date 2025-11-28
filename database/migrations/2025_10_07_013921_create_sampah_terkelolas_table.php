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
        Schema::create('sampah_terkelolas', function (Blueprint $table) {
            $table->id('id_sampah');
            $table->foreignId('id_user')->constrained('users');
            $table->foreignId('id_lokasi')->constrained('lokasi_asals');
            $table->foreignId('id_jenis')->constrained('jenis');
            $table->decimal('jumlah_berat', 10, 2);
            $table->date('tgl');
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sampah_terkelolas');
    }
};
