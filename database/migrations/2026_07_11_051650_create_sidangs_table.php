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
        Schema::create('sidangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_akhir_id')->constrained('tugas_akhirs')->onDelete('cascade');
            $table->foreignId('dosen_penguji_id')->constrained('dosens')->onDelete('cascade');
            $table->dateTime('tanggal_sidang');
            $table->string('ruangan');
            $table->integer('nilai_angka')->nullable();
            $table->string('nilai_huruf', 2)->nullable();
            $table->enum('status_kelulusan', ['menunggu', 'lulus', 'tidak_lulus'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sidangs');
    }
};
