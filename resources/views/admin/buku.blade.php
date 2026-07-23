<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JENDELA - Panel Unggah Buku Desa Doko</title>
    
    <!-- Google Fonts: Playfair Display, Source Serif 4, DM Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:ital,wght@0,300;0,400;0,500;1,400&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Source+Serif+4:ital,opsz,wght@0,8..60,400;0,8..60,600;1,8..60,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
</head>
<body class="tubuh-halaman">

    <!-- Bilah Navigasi Admin -->
    <nav class="navbar papan-navigasi py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center text-decoration-none" href="/">
                <span class="logo-pustaka">JENDELA<span class="logo-titik-kayu">.</span></span>
            </a>
            <div class="d-flex align-items-center gap-2">
                <button id="btnToggleTema" class="tombol-tema" title="Ubah Tema Visual">
                    <i class="bi bi-moon-stars" id="ikonTema"></i>
                </button>
                <form action="/admin/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                        <i class="bi bi-box-arrow-right me-1"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Wadah Utama Centered (Halaman Dasbor Buku) -->
    <main class="wadah-utama halaman-dasbor-buku dasbor-pustakawan">
        
        <!-- Header Sambutan -->
        <header class="text-center my-4">
            <h1 class="fw-bold mb-2">Panel Unggah Buku & Dokumen Desa Doko</h1>
            <p class="text-muted fs-6">Kelola modul edukasi, laporan APBDes, atau buku saku UMKM warga Desa Doko, Kec. Ngasem, Kab. Kediri.</p>
        </header>

        <!-- Bingkai Navigasi Utama Admin (Unified Page Switcher) -->
        <div class="wadah-navigasi-halaman-admin mb-4 text-center">
            <div class="d-inline-flex gap-2 p-1 rounded-pill bg-dinding-admin border-admin shadow-sm">
                <a class="btn tombol-nav-admin" href="/admin/kurasi">
                    <i class="bi bi-hourglass-split me-2"></i>Kurasi Karya Warga
                </a>
                <a class="btn tombol-nav-admin aktif" href="/admin/buku">
                    <i class="bi bi-journal-plus me-2"></i>Unggah Buku Desa (PDF)
                </a>
            </div>
        </div>

        <!-- Tombol Aksi Tambah Buku -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0 text-teal"><i class="bi bi-collection-play me-2"></i>Daftar Buku Desa ({{ count($bukuDesaList) }})</h4>
            <button class="btn tombol-kirim btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalTambahBuku">
                <i class="bi bi-plus-circle me-1"></i> Unggah Buku Desa Baru
            </button>
        </div>

        <!-- Konten Daftar Buku Desa -->
        <div id="kontenBukuDesa">
            @if(count($bukuDesaList) === 0)
                <div class="peringatan-kosong">
                    <i class="bi bi-journal-arrow-up"></i>
                    <h5>Belum ada buku desa yang diunggah</h5>
                    <p class="mb-0">Klik tombol "Unggah Buku Desa Baru" untuk membagikan e-book/PDF desa.</p>
                </div>
            @else
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    @foreach($bukuDesaList as $buku)
                        <div class="col" id="buku-item-{{ $buku->id }}">
                            <div class="kurator-wadah h-100 d-flex flex-column justify-content-between">
                                <div>
                                    <div class="d-flex gap-3 align-items-start mb-3">
                                        <img src="{{ $buku->sampul_path ? asset('storage/' . $buku->sampul_path) : 'https://placehold.co/120x180/0f766e/ffffff?text=E-Book' }}" 
                                             alt="{{ $buku->judul }}" 
                                             class="rounded shadow-sm" 
                                             style="width: 70px; height: 95px; object-fit: cover;">
                                        <div>
                                            <h5 class="fw-bold text-teal mb-1">{{ $buku->judul }}</h5>
                                            <p class="small text-secondary mb-1"><i class="bi bi-person me-1"></i>Penulis: {{ $buku->penulis }}</p>
                                            <small class="text-muted"><i class="bi bi-clock me-1"></i>{{ $buku->created_at->format('d M Y, H:i') }}</small>
                                        </div>
                                    </div>
                                    @if($buku->sinopsis)
                                        <p class="small text-muted mb-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ $buku->sinopsis }}
                                        </p>
                                    @endif
                                </div>
                                <div class="d-flex gap-2 justify-content-between align-items-center pt-2 border-top border-kaca">
                                    <a href="{{ asset('storage/' . $buku->pdf_path) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Buka PDF
                                    </a>
                                    <button onclick="hapusBuku({{ $buku->id }})" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    </main>

    <!-- Modal Unggah Buku Desa -->
    <div class="modal fade" id="modalTambahBuku" tabindex="-1" aria-labelledby="modalTambahBukuLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered dialog-unggah">
            <div class="modal-content">
                <form id="formUnggahBuku" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="modalTambahBukuLabel">
                            <i class="bi bi-journal-plus me-2 text-teal"></i>Unggah Buku Desa (PDF)
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small mb-4">Unggah modul, panduan, laporan desa, atau e-book karya perangkat desa berbentuk PDF.</p>
                        
                        <div class="mb-3">
                            <label for="judul" class="form-label fw-semibold text-secondary">Judul Buku / Dokumen</label>
                            <input type="text" class="form-control" id="judul" name="judul" required placeholder="Masukkan judul buku..." maxlength="255">
                        </div>
                        
                        <div class="mb-3">
                            <label for="penulis" class="form-label fw-semibold text-secondary">Penulis / Penerbit Desa</label>
                            <input type="text" class="form-control" id="penulis" name="penulis" required placeholder="Contoh: Pemdes Desa Digital / Karang Taruna" maxlength="150">
                        </div>
                        
                        <div class="mb-3">
                            <label for="sinopsis" class="form-label fw-semibold text-secondary">Sinopsis / Deskripsi Ringkas</label>
                            <textarea class="form-control" id="sinopsis" name="sinopsis" rows="3" placeholder="Tuliskan gambaran isi buku ini..." style="resize: none;"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="sampul_file" class="form-label fw-semibold text-secondary">Gambar Sampul (Opsional - JPG/PNG/WebP)</label>
                            <input type="file" class="form-control input-sampul" id="sampul_file" name="sampul_file" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label for="pdf_file" class="form-label fw-semibold text-secondary label-berkas-pdf">Berkas PDF Buku (Wajib berformat PDF - Maksimal 20MB)</label>
                            <input type="file" class="form-control input-berkas-pdf" id="pdf_file" name="pdf_file" accept=".pdf" required>
                            <div class="form-text small text-muted">Format file WAJIB .pdf dan ukuran maksimal 20MB.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link text-secondary text-decoration-none me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn tombol-kirim tombol-simpan-buku" id="tombolSubmitBuku">
                            <i class="bi bi-upload me-2"></i>Unggah & Publikasikan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS, Axios, SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            const btnToggleTema = document.getElementById('btnToggleTema');
            const ikonTema = document.getElementById('ikonTema');
            const formUnggahBuku = document.getElementById('formUnggahBuku');
            const modalTambahBuku = new bootstrap.Modal(document.getElementById('modalTambahBuku'));

            // Pengaturan Tema
            const muatTemaPreferensi = () => {
                const temaTersimpan = localStorage.getItem('theme');
                const preferensiGelapSystem = window.matchMedia('(prefers-color-scheme: dark)').matches;
                
                if (temaTersimpan === 'dark' || (!temaTersimpan && preferensiGelapSystem)) {
                    body.classList.add('theme-dark');
                    ikonTema.className = 'bi bi-sun';
                } else {
                    body.classList.remove('theme-dark');
                    ikonTema.className = 'bi bi-moon-stars';
                }
            };

            btnToggleTema.addEventListener('click', () => {
                if (body.classList.contains('theme-dark')) {
                    body.classList.remove('theme-dark');
                    ikonTema.className = 'bi bi-moon-stars';
                    localStorage.setItem('theme', 'light');
                } else {
                    body.classList.add('theme-dark');
                    ikonTema.className = 'bi bi-sun';
                    localStorage.setItem('theme', 'dark');
                }
            });

            formUnggahBuku.addEventListener('submit', (e) => {
                e.preventDefault();
                const formData = new FormData(formUnggahBuku);
                const tombolSubmit = document.getElementById('tombolSubmitBuku');
                const teksAsli = tombolSubmit.innerHTML;

                tombolSubmit.disabled = true;
                tombolSubmit.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span>Mengunggah...`;

                axios.post('/api/buku-lokal', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                })
                .then(response => {
                    tombolSubmit.disabled = false;
                    tombolSubmit.innerHTML = teksAsli;
                    modalTambahBuku.hide();
                    formUnggahBuku.reset();

                    Swal.fire({
                        title: 'Berhasil!',
                        text: response.data.message || 'Buku desa berhasil dipublikasikan.',
                        icon: 'success',
                        confirmButtonColor: '#0f766e'
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(error => {
                    tombolSubmit.disabled = false;
                    tombolSubmit.innerHTML = teksAsli;
                    let pesanError = 'Gagal mengunggah buku desa.';
                    if (error.response && error.response.data && error.response.data.errors) {
                        pesanError = Object.values(error.response.data.errors).flat().join('\n');
                    }
                    Swal.fire({
                        title: 'Gagal!',
                        text: pesanError,
                        icon: 'error',
                        confirmButtonColor: '#0f766e'
                    });
                });
            });

            muatTemaPreferensi();
        });

        const hapusBuku = (id) => {
            Swal.fire({
                title: 'Hapus Buku Desa?',
                text: "Berkas PDF & data buku ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/buku-lokal/${id}`)
                        .then(response => {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: response.data.message || 'Buku berhasil dihapus.',
                                icon: 'success',
                                confirmButtonColor: '#0f766e'
                            }).then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(error => {
                            console.error('Error deleting book:', error);
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Gagal menghapus buku desa ini.',
                                icon: 'error',
                                confirmButtonColor: '#0f766e'
                            });
                        });
                }
            });
        };
    </script>
</body>
</html>
