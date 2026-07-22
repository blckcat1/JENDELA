<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuLokal extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul',
        'penulis',
        'sinopsis',
        'sampul_path',
        'jalur_pdf',
    ];

    /**
     * Accessor untuk kompatibilitas jika dipanggil sebagai pdf_path.
     */
    public function getPdfPathAttribute()
    {
        return $this->attributes['jalur_pdf'] ?? null;
    }
}
