<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pertanyaan_from_user', function (Blueprint $table) {
            $table->id();
            $table->string('nama_user');
            $table->string('email');
            $table->string('jabatan');
            $table->string('bagian');
            $table->text('pertanyaan');
            $table->enum('jawaban_melalui', ['email', 'whatsapp', 'telepon']);
            $table->string('nomor_kontak')->nullable(); // diisi jika via whatsapp/telepon
            $table->boolean('status')->default(false);
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pertanyaan_from_user');
    }
};
