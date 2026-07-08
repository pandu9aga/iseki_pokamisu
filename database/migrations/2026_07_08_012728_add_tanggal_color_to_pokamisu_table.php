<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pokamisu', function (Blueprint $table) {
            $table->string('tanggal_color', 9)->nullable()->default('#000000')->after('tanggal');
        });
    }

    public function down(): void
    {
        Schema::table('pokamisu', function (Blueprint $table) {
            $table->dropColumn('tanggal_color');
        });
    }
};
