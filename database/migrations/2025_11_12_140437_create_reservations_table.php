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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
            $table->string('nama_tamu');
            $table->string('no_identitas', 50);
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedInteger('jumlah_tamu');
            $table->decimal('total_harga', 12, 2)->default(0);
            $table->decimal('denda', 12, 2)->default(0);
            $table->enum('status', ['menginap', 'menunggu_pengecekan', 'selesai'])->default('menginap');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
