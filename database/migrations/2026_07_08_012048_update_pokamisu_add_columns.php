<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pokamisu', function (Blueprint $table) {
            $table->text('no_instruksi')->nullable()->after('no_color');
            $table->string('no_instruksi_color', 9)->nullable()->default('#000000')->after('no_instruksi');
            $table->date('tanggal')->nullable()->after('pic_color');
        });

        Schema::table('pokamisu', function (Blueprint $table) {
            $table->dropColumn(['instruksi', 'instruksi_color']);
        });
    }

    public function down(): void
    {
        Schema::table('pokamisu', function (Blueprint $table) {
            $table->text('instruksi')->nullable();
            $table->string('instruksi_color', 9)->nullable()->default('#000000');
        });

        Schema::table('pokamisu', function (Blueprint $table) {
            $table->dropColumn(['no_instruksi', 'no_instruksi_color', 'tanggal']);
        });
    }
};
