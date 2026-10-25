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
        if (Schema::hasColumn('mahasiswas', 'kelas')) {
            Schema::table('mahasiswas', function (Blueprint $table) {
                $table->renameColumn('kelas', 'program_studi');
            });
        }

        // Dan pastikan panjangnya 100 karakter
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('program_studi', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('mahasiswas', 'program_studi')) {
            Schema::table('mahasiswas', function (Blueprint $table) {
                $table->renameColumn('program_studi', 'kelas');
            });
        }

        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('kelas', 10)->change();
        });
    }
};
