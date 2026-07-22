<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BukuLokal;
use Illuminate\Support\Facades\Storage;

class BukuLokalController extends Controller
{
    /**
     * Mengambil daftar buku lokal desa (E-Book/PDF) untuk publik.
     * GET /api/buku-lokal
     */
    public function index()
    {
        $bukuList = BukuLokal::orderBy('created_at', 'desc')->get();

        $response = $bukuList->map(function ($buku) {
            $jalur = $buku->jalur_pdf ?? $buku->pdf_path;
            return [
                'id' => $buku->id,
                'judul' => $buku->judul,
                'penulis' => $buku->penulis,
                'sinopsis' => $buku->sinopsis,
                'sampul_url' => $buku->sampul_path ? asset('storage/' . $buku->sampul_path) : 'https://placehold.co/120x180/0f766e/ffffff?text=E-Book+Desa',
                'pdf_url' => asset('storage/' . $jalur),
                'jalur_pdf' => asset('storage/' . $jalur),
                'tanggal' => $buku->created_at->locale('id')->diffForHumans(),
            ];
        });

        return response()->json($response);
    }

    /**
     * Menyimpan unggahan buku lokal desa baru oleh Admin.
     * POST /api/buku-lokal
     */
    public function store(Request $request)
    {
        // Aturan Validasi SANGAT KETAT: PDF dan maksimal 20MB (20480 KB)
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:150',
            'sinopsis' => 'nullable|string',
            'sampul_file' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:3072',
            'pdf_file' => 'required_without:berkas_pdf|file|mimes:pdf|max:20480',
            'berkas_pdf' => 'required_without:pdf_file|file|mimes:pdf|max:20480',
        ], [
            'required' => ':attribute wajib diisi.',
            'required_without' => 'File PDF wajib diunggah.',
            'mimes' => 'File yang diunggah WAJIB berformat PDF.',
            'max' => 'Ukuran file PDF maksimal 20MB.',
        ]);

        $sampulPath = null;
        if ($request->hasFile('sampul_file')) {
            $sampulPath = $request->file('sampul_file')->store('koleksi_desa', 'public');
        }

        // Ambil file PDF dari input 'pdf_file' atau 'berkas_pdf'
        $berkasPdf = $request->file('pdf_file') ?? $request->file('berkas_pdf');
        
        // Simpan file PDF menggunakan Storage Facade ke folder public/koleksi_desa
        $jalurPdf = $berkasPdf->store('koleksi_desa', 'public');

        // Simpan data dan jalur_pdf ke database MySQL (tabel buku_lokals)
        $buku = BukuLokal::create([
            'judul' => $validated['judul'],
            'penulis' => $validated['penulis'],
            'sinopsis' => $validated['sinopsis'] ?? null,
            'sampul_path' => $sampulPath,
            'jalur_pdf' => $jalurPdf,
        ]);

        return response()->json([
            'message' => 'Buku lokal desa berhasil diunggah dan dipublikasikan.',
            'data' => $buku
        ], 201);
    }

    /**
     * Menghapus buku desa lokal dan berkas fisiknya.
     * DELETE /api/buku-lokal/{id}
     */
    public function destroy($id)
    {
        $buku = BukuLokal::findOrFail($id);

        if ($buku->sampul_path && Storage::disk('public')->exists($buku->sampul_path)) {
            Storage::disk('public')->delete($buku->sampul_path);
        }

        $jalur = $buku->jalur_pdf ?? $buku->pdf_path;
        if ($jalur && Storage::disk('public')->exists($jalur)) {
            Storage::disk('public')->delete($jalur);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku lokal desa berhasil dihapus.'
        ]);
    }

    /**
     * Halaman manajemen unggah buku desa admin (Blade).
     */
    public function halamanBuku()
    {
        $bukuDesaList = BukuLokal::orderBy('created_at', 'desc')->get();
        return view('admin.buku', compact('bukuDesaList'));
    }
}
