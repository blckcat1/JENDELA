<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\KaryaWarga;
use Illuminate\Support\Str;

class KaryaWargaController extends Controller
{
    /**
     * Mengambil daftar karya warga yang sudah disetujui.
     * GET /api/karya
     */
    public function index()
    {
        $karyaList = KaryaWarga::where('status_publikasi', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $response = $karyaList->map(function ($karya) {
            $isiRingkas = $karya->isi_karya 
                ? Str::limit($karya->isi_karya, 120, '...') 
                : ($karya->jalur_pdf ? 'Dokumen Karya Warga (PDF)' : 'Tanpa deskripsi');

            return [
                'id' => $karya->id,
                'judul' => $karya->judul_karya,
                'judul_karya' => $karya->judul_karya,
                'penulis' => $karya->nama_penulis,
                'nama_penulis' => $karya->nama_penulis,
                'kategori' => $karya->kategori,
                'isi_karya' => $karya->isi_karya,
                'isi_ringkas' => $isiRingkas,
                'sampul_url' => $karya->sampul_path ? asset('storage/' . $karya->sampul_path) : null,
                'pdf_url' => $karya->jalur_pdf ? asset('storage/' . $karya->jalur_pdf) : null,
                'jalur_pdf' => $karya->jalur_pdf,
                'tanggal' => $karya->created_at->locale('id')->diffForHumans(),
            ];
        });

        return response()->json($response);
    }

    /**
     * Menyimpan kiriman karya tulis baru dari warga.
     * POST /api/karya
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_karya' => 'required|string|max:255',
            'nama_penulis' => 'required|string|max:150',
            'kategori' => 'required|in:Sejarah,Cerpen,Puisi,Artikel UMKM,Lainnya',
            'isi_karya' => 'nullable|string',
            'sampul_file' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
            'berkas_pdf' => 'nullable|file|mimes:pdf|max:20480',
        ], [
            'required' => ':attribute wajib diisi.',
            'in' => 'Kategori :attribute tidak valid.',
            'max' => ':attribute maksimal :max.',
            'mimes' => 'Format file :attribute tidak sesuai.',
        ]);

        $sampulPath = null;
        if ($request->hasFile('sampul_file')) {
            $sampulPath = $request->file('sampul_file')->store('karya_warga/sampul', 'public');
        }

        $jalurPdf = null;
        if ($request->hasFile('berkas_pdf')) {
            $jalurPdf = $request->file('berkas_pdf')->store('karya_warga/pdf', 'public');
        }

        $karya = KaryaWarga::create([
            'judul_karya' => $validated['judul_karya'],
            'nama_penulis' => $validated['nama_penulis'],
            'kategori' => $validated['kategori'],
            'isi_karya' => $validated['isi_karya'] ?? null,
            'sampul_path' => $sampulPath,
            'jalur_pdf' => $jalurPdf,
            'status_publikasi' => false, // Default tidak langsung tampil (perlu kurasi admin)
        ]);

        return response()->json([
            'message' => 'Karya warga berhasil dikirim dan menunggu kurasi admin.',
            'data' => $karya
        ], 201);
    }

    /**
     * Menyetujui karya tulis warga (kurasi admin).
     * PATCH /api/karya/{id}/setujui
     */
    public function setujui($id)
    {
        $karya = KaryaWarga::findOrFail($id);
        $karya->status_publikasi = true;
        $karya->save();

        return response()->json([
            'message' => 'Karya berhasil disetujui untuk dipublikasikan.'
        ]);
    }

    /**
     * Menolak/menghapus karya tulis warga.
     * DELETE /api/karya/{id}
     */
    public function destroy($id)
    {
        $karya = KaryaWarga::findOrFail($id);

        if ($karya->sampul_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($karya->sampul_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($karya->sampul_path);
        }

        if ($karya->jalur_pdf && \Illuminate\Support\Facades\Storage::disk('public')->exists($karya->jalur_pdf)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($karya->jalur_pdf);
        }

        $karya->delete();

        return response()->json([
            'message' => 'Karya berhasil dihapus.'
        ]);
    }

    /**
     * Halaman panel kurasi admin (Blade).
     */
    public function halamanKurasi()
    {
        $karyaMenunggu = KaryaWarga::where('status_publikasi', false)
            ->orderBy('created_at', 'asc')
            ->get();

        $karyaDisetujui = KaryaWarga::where('status_publikasi', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.kurasi', compact('karyaMenunggu', 'karyaDisetujui'));
    }
}
