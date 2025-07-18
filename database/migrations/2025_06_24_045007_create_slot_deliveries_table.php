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
        Schema::create('slot_deliveries', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_pengiriman');
            $table->integer('permintaan_kirim');
            $table->integer('slot_pengiriman');
            $table->integer('over_sisa')->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slot_deliveries');
    }
};
