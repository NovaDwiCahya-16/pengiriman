<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('datarekaps', function (Blueprint $table) {
            $table->id();
            $table->string('no_faktur')->nullable();
            $table->date('tgl_faktur')->nullable();
            $table->string('no_sj_mutasi')->nullable();
            $table->date('tgl_sj_mutasi')->nullable();
            $table->string('nama_konsumen')->nullable();
            $table->string('kecamatan_kirim')->nullable();
            $table->string('kota_kirim')->nullable();
            $table->string('leasing')->nullable();
            $table->string('nama_type')->nullable();
            $table->string('warna')->nullable();
            $table->string('cabang')->nullable();
            $table->string('supir')->nullable();
            $table->date('tgl_kirim')->nullable();
            $table->string('stock')->nullable();
            $table->string('harga')->nullable();
            $table->string('kwitansi')->nullable();
            $table->string('konsumen_bayar')->nullable();
            $table->string('keterangan_tambahan')->nullable();
            $table->date('tgl_serah_terima_unit')->nullable();
            $table->string('pengiriman_leadtime')->nullable();
            $table->string('performance_pengiriman_hari')->nullable();
            $table->string('status_pengiriman')->nullable();
            $table->string('keterangan_pending')->nullable();
            $table->string('keterangan_lainnya')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datarekaps');
    }
};
