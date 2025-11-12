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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kamar')->unique();
            $table->string('nama_kamar');
            $table->foreignId('room_type_id')->constrained('room_types')->onDelete('cascade');
            $table->decimal('harga_per_malam', 12, 2);
            $table->enum('status', ['kosong', 'terisi', 'maintenance'])->default('kosong');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
