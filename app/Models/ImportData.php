<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportData extends Model
{
    protected $table = 'pokamisu';

    protected $fillable = [
        'no', 'no_color',
        'instruksi', 'instruksi_color',
        'tipe_traktor', 'tipe_traktor_color',
        'no_produksi', 'no_produksi_color',
        'sign', 'sign_color',
        'permasalahan', 'permasalahan_color',
        'keterangan', 'keterangan_color',
        'jenis_penanganan', 'jenis_penanganan_color',
        'pic_repair', 'pic_repair_color',
        'kategori', 'kategori_color',
        'team', 'team_color',
        'pic', 'pic_color',
    ];
}
