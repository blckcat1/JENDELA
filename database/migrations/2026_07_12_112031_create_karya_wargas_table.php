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
        Schema::create('karya_wargas', function (Blueprint $table) {
            $table->id();
            $table->string('judul_karya', 255);
            $table->string('nama_penulis', 150);
            $table->enum('kategori', ['Sejarah', 'Cerpen', 'Puisi', 'Artikel UMKM', 'Lainnya']);
            $table->text('isi_karya')->nullable();
            $table->string('sampul_path')->nullable();
            $table->string('jalur_pdf')->nullable();
            $table->boolean('status_publikasi')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karya_wargas');
    }
};
