<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JENDELA - Panel Kurasi Admin</title>
    
    <!-- Google Fonts: Playfair Display, Source Serif 4, DM Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:ital,wght@0,300;0,400;0,500;1,400&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,600&family=Source+Serif+4:ital,opsz,wght@0,8..60,400;0,8..60,600;1,8..60,400&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- Custom CSS (Bahasa Indonesia Only) -->
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
</head>
<body class="tubuh-halaman">

    <!-- Bilah Navigasi Premium -->
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

    <!-- Wadah Utama Centered (Mobile-First) -->
    <main class="wadah-utama dasbor-pustakawan">
        
        <!-- Header Sambutan -->
        <header class="text-center my-4">
            <h1 class="fw-bold mb-2">Panel Kurasi Karya Warga</h1>
            <p class="text-muted fs-6">Tinjau, setujui publikasi, atau hapus kiriman naskah kearifan lokal warga desa.</p>
        </header>

        <!-- Bingkai Navigasi Utama Admin (Unified Page Switcher) -->
        <div class="wadah-navigasi-halaman-admin mb-4 text-center">
            <div class="d-inline-flex gap-2 p-1 rounded-pill bg-dinding-admin border-admin shadow-sm">
                <a class="btn tombol-nav-admin aktif" href="/admin/kurasi">
                    <i class="bi bi-hourglass-split me-2"></i>Kurasi Karya Warga
                </a>
                <a class="btn tombol-nav-admin" href="/admin/buku">
                    <i class="bi bi-journal-plus me-2"></i>Unggah Buku Desa (PDF)
                </a>
            </div>
        </div>

        <!-- Bingkai Tab Navigasi Admin -->
        <section class="bingkai-tab">
            <ul class="nav nav-tabs navigasi-tab" id="tabAdmin" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-menunggu" data-bs-toggle="tab" data-bs-target="#panel-menunggu" type="button" role="tab" aria-controls="panel-menunggu" aria-selected="true">
                        <i class="bi bi-clock-history me-2"></i>Menunggu Persetujuan ({{ count($karyaMenunggu) }})
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-terpublikasi" data-bs-toggle="tab" data-bs-target="#panel-terpublikasi" type="button" role="tab" aria-controls="panel-terpublikasi" aria-selected="false">
                        <i class="bi bi-check-circle me-2"></i>Terpublikasi ({{ count($karyaDisetujui) }})
                    </button>
                </li>
            </ul>
        </section>

        <!-- Konten Panel Tab Admin -->
        <div class="tab-content" id="kontenTabAdmin">
            
            <!-- Tab 1: Menunggu Persetujuan -->
            <div class="tab-pane fade show active" id="panel-menunggu" role="tabpanel" aria-labelledby="tab-menunggu">
                @if(count($karyaMenunggu) === 0)
                    <div class="peringatan-kosong">
                        <i class="bi bi-clipboard-check"></i>
                        <h5>Semua karya telah dikurasi</h5>
                        <p class="mb-0">Tidak ada kiriman baru yang menunggu persetujuan saat ini.</p>
                    </div>
                @else
                    @foreach($karyaMenunggu as $karya)
                        <div class="kurator-wadah mb-4" id="karya-item-{{ $karya->id }}">
                            <div class="d-flex gap-3 align-items-start mb-3">
                                @if($karya->sampul_path)
                                    <img src="{{ asset('storage/' . $karya->sampul_path) }}" alt="{{ $karya->judul_karya }}" class="rounded shadow-sm" style="width: 70px; height: 95px; object-fit: cover;">
                                @endif
                                <div>
                                    <span class="lencana-kategori {{ 
                                        $karya->kategori === 'Sejarah' ? 'kategori-sejarah' : (
                                        $karya->kategori === 'Cerpen' ? 'kategori-cerpen' : (
                                        $karya->kategori === 'Puisi' ? 'kategori-puisi' : (
                                        $karya->kategori === 'Artikel UMKM' ? 'kategori-umkm' : 'kategori-lainnya')))
                                    }} mb-2 d-inline-block">{{ $karya->kategori }}</span>
                                    <h4 class="fw-bold text-teal mb-1">{{ $karya->judul_karya }}</h4>
                                    <small class="text-secondary"><i class="bi bi-person me-1"></i>Penulis: <strong>{{ $karya->nama_penulis }}</strong> &bull; Dibuat: {{ $karya->created_at->format('d M Y, H:i') }}</small>
                                </div>
                            </div>
                            @if($karya->isi_karya)
                                <hr class="my-3 border-kaca">
                                <p class="lh-lg serif-bacaan" style="white-space: pre-wrap; font-size: 1rem;">{{ $karya->isi_karya }}</p>
                            @endif
                            @if($karya->jalur_pdf)
                                <div class="mt-2 mb-3">
                                    <a href="{{ asset('storage/' . $karya->jalur_pdf) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Lihat PDF Karya Warga
                                    </a>
                                </div>
                            @endif
                            <hr class="my-3 border-kaca">
                            <div class="d-flex gap-2 justify-content-end">
                                <button onclick="tolakKarya({{ $karya->id }})" class="btn btn-outline-danger tombol-tolak-karya btn-sm rounded-pill px-3">
                                    <i class="bi bi-trash-fill me-1"></i> Hapus / Tolak
                                </button>
                                <button onclick="setujuiKarya({{ $karya->id }})" class="btn tombol-kirim tombol-setujui-karya btn-sm rounded-pill px-4">
                                    <i class="bi bi-check-lg me-1"></i> Setujui & Publikasikan
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Tab 2: Terpublikasi -->
            <div class="tab-pane fade" id="panel-terpublikasi" role="tabpanel" aria-labelledby="tab-terpublikasi">
                @if(count($karyaDisetujui) === 0)
                    <div class="peringatan-kosong">
                        <i class="bi bi-journal-x"></i>
                        <h5>Belum ada karya terpublikasi</h5>
                        <p class="mb-0">Karya yang disetujui di tab sebelah akan muncul di sini.</p>
                    </div>
                @else
                    @foreach($karyaDisetujui as $karya)
                        <div class="kurator-wadah mb-4" id="karya-item-{{ $karya->id }}">
                            <div class="d-flex gap-3 align-items-start mb-3">
                                @if($karya->sampul_path)
                                    <img src="{{ asset('storage/' . $karya->sampul_path) }}" alt="{{ $karya->judul_karya }}" class="rounded shadow-sm" style="width: 70px; height: 95px; object-fit: cover;">
                                @endif
                                <div>
                                    <span class="lencana-kategori {{ 
                                        $karya->kategori === 'Sejarah' ? 'kategori-sejarah' : (
                                        $karya->kategori === 'Cerpen' ? 'kategori-cerpen' : (
                                        $karya->kategori === 'Puisi' ? 'kategori-puisi' : (
                                        $karya->kategori === 'Artikel UMKM' ? 'kategori-umkm' : 'kategori-lainnya')))
                                    }} mb-2 d-inline-block">{{ $karya->kategori }}</span>
                                    <h4 class="fw-bold text-teal mb-1">{{ $karya->judul_karya }}</h4>
                                    <small class="text-secondary"><i class="bi bi-person me-1"></i>Penulis: <strong>{{ $karya->nama_penulis }}</strong> &bull; Dibuat: {{ $karya->created_at->format('d M Y, H:i') }}</small>
                                </div>
                            </div>
                            @if($karya->isi_karya)
                                <hr class="my-3 border-kaca">
                                <p class="lh-lg serif-bacaan" style="white-space: pre-wrap; font-size: 1rem;">{{ $karya->isi_karya }}</p>
                            @endif
                            @if($karya->jalur_pdf)
                                <div class="mt-2 mb-3">
                                    <a href="{{ asset('storage/' . $karya->jalur_pdf) }}" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Lihat PDF Karya Warga
                                    </a>
                                </div>
                            @endif
                            <hr class="my-3 border-kaca">
                            <div class="d-flex gap-2 justify-content-end">
                                <button onclick="tolakKarya({{ $karya->id }})" class="btn btn-outline-danger tombol-tolak-karya btn-sm rounded-pill px-3">
                                    <i class="bi bi-trash-fill me-1"></i> Hapus dari Portal
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            
        </div>

    </main>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Axios JS -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Admin Kurasi Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            const btnToggleTema = document.getElementById('btnToggleTema');
            const ikonTema = document.getElementById('ikonTema');

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

            muatTemaPreferensi();
        });

        // Mengirim request PATCH untuk menyetujui karya
        const setujuiKarya = (id) => {
            Swal.fire({
                title: 'Setujui Karya?',
                text: "Karya ini akan segera dipublikasikan di portal utama.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0f766e',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Publikasikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.patch(`/api/karya/${id}/setujui`)
                        .then(response => {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: response.data.message || 'Karya berhasil dipublikasikan.',
                                icon: 'success',
                                confirmButtonColor: '#0f766e'
                            }).then(() => {
                                // Reload halaman untuk memicu update antarmuka
                                window.location.reload();
                            });
                        })
                        .catch(error => {
                            console.error('Error approving work:', error);
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Gagal menyetujui karya tulis ini.',
                                icon: 'error',
                                confirmButtonColor: '#0f766e'
                            });
                        });
                }
            });
        };

        // Mengirim request DELETE untuk menolak/menghapus karya
        const tolakKarya = (id) => {
            Swal.fire({
                title: 'Hapus Karya?',
                text: "Tindakan ini permanen dan tidak dapat dibatalkan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/karya/${id}`)
                        .then(response => {
                            Swal.fire({
                                title: 'Terhapus!',
                                text: response.data.message || 'Karya berhasil dihapus.',
                                icon: 'success',
                                confirmButtonColor: '#0f766e'
                            }).then(() => {
                                // Reload halaman
                                window.location.reload();
                            });
                        })
                        .catch(error => {
                            console.error('Error deleting work:', error);
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Gagal menghapus karya tulis ini.',
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
