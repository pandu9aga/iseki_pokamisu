<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pokamisu', function (Blueprint $table) {
            $table->id();

            $table->string('no')->nullable();
            $table->string('no_color', 9)->nullable()->default('#000000');
            $table->text('instruksi')->nullable();
            $table->string('instruksi_color', 9)->nullable()->default('#000000');
            $table->string('tipe_traktor')->nullable();
            $table->string('tipe_traktor_color', 9)->nullable()->default('#000000');
            $table->string('no_produksi')->nullable();
            $table->string('no_produksi_color', 9)->nullable()->default('#000000');
            $table->string('sign')->nullable();
            $table->string('sign_color', 9)->nullable()->default('#000000');
            $table->text('permasalahan')->nullable();
            $table->string('permasalahan_color', 9)->nullable()->default('#000000');
            $table->text('keterangan')->nullable();
            $table->string('keterangan_color', 9)->nullable()->default('#000000');
            $table->text('jenis_penanganan')->nullable();
            $table->string('jenis_penanganan_color', 9)->nullable()->default('#000000');
            $table->string('pic_repair')->nullable();
            $table->string('pic_repair_color', 9)->nullable()->default('#000000');
            $table->string('kategori')->nullable();
            $table->string('kategori_color', 9)->nullable()->default('#000000');
            $table->string('team')->nullable();
            $table->string('team_color', 9)->nullable()->default('#000000');
            $table->string('pic')->nullable();
            $table->string('pic_color', 9)->nullable()->default('#000000');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pokamisu');
    }
};
