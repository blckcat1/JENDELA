<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KaryaWarga extends Model
{
    protected $fillable = [
        'judul_karya',
        'nama_penulis',
        'kategori',
        'isi_karya',
        'sampul_path',
        'jalur_pdf',
        'status_publikasi',
    ];

    protected $casts = [
        'status_publikasi' => 'boolean',
    ];
}
