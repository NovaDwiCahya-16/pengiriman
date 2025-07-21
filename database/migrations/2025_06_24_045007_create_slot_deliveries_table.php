<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSlotDeliveriesTable extends Migration
{
    public function up()
    {
       Schema::create('slot_deliveries', function (Blueprint $table) {
    $table->id();
    $table->string('tanggalPengiriman'); // contoh: "Juli 2025"
    $table->integer('permintaan_kirim');
    $table->integer('slot_pengiriman');
    $table->string('over_sisa'); // bisa positif atau negatif, bebas string
    $table->timestamps();
});

    }

    public function down()
    {
        Schema::dropIfExists('slot_deliveries');
    }
}

