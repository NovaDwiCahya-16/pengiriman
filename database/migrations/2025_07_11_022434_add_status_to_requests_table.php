<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            // Pastikan nama kolom sebelum 'status' sesuai dengan database-mu.
            // Misalnya: 'unit' atau 'jumlah_unit' – sesuaikan di sini:
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak', 'Diproses'])
                  ->default('Menunggu')
                  ->after('unit'); // Ganti 'unit' jika nama kolom sebelumnya berbeda
        });
    }

    /**
     * Undo perubahan (rollback).
     */
    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
