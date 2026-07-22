<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>JENDELA - Jejaring Edukasi & Literasi Digital Desa</title>
    
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

    <!-- Bilah Navigasi Utama -->
    <nav class="navbar papan-navigasi py-3">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand d-flex align-items-center text-decoration-none" href="/">
                <span class="logo-pustaka">JENDELA<span class="logo-titik-kayu">.</span></span>
            </a>
            <div class="d-flex align-items-center gap-3">
                <button id="btnToggleTema" class="tombol-tema" title="Ubah Tema Visual">
                    <i class="bi bi-moon-stars" id="ikonTema"></i>
                </button>
                <a href="/admin/kurasi" class="btn tombol-admin-nav">
                    <i class="bi bi-shield-lock me-1"></i> Admin Kurasi
                </a>
            </div>
        </div>
    </nav>

    <!-- Wadah Utama -->
    <main class="wadah-utama">
        
        <!-- Header Sambutan Utama -->
        <header class="hero-jendela">
            <h1 class="judul-jendela">Jejaring Literasi & Karya Warga</h1>
            <p class="subjudul-jendela">Ruang baca digital desa — membaca pengetahuan dunia dan mengabadikan tulisan lokal.</p>
        </header>

        <!-- SIGNATURE ELEMENT: Bingkai Jendela (Window Pane Navigator) -->
        <section class="bingkai-jendela-container">
            <div class="bingkai-jendela-frame">
                <!-- Panel Kiri: Karya Warga Desa -->
                <button type="button" class="panel-jendela panel-karya-warga aktif" id="tombol-panel-karya" onclick="pilihPanel('karya')">
                    <span class="jendela-label-fungsi">KOLEKSI LOKAL</span>
                    <h3 class="jendela-panel-judul">Karya Warga Desa</h3>
                    <p class="jendela-panel-ket">Cerita, sejarah, puisi, dan wirausaha tulisan tangan warga.</p>
                </button>

                <!-- Panel Tengah: Pustaka Umum (Google Books API) -->
                <button type="button" class="panel-jendela panel-pustaka-umum" id="tombol-panel-pustaka" onclick="pilihPanel('pustaka')">
                    <span class="jendela-label-fungsi">GOOGLE BOOKS</span>
                    <h3 class="jendela-panel-judul">Pustaka Umum</h3>
                    <p class="jendela-panel-ket">Koleksi buku terkurasi literasi Indonesia & dunia dari Google Books API.</p>
                </button>

                <!-- Panel Kanan: Sains & Teknologi -->
                <button type="button" class="panel-jendela panel-sains-teknologi" id="tombol-panel-sains" onclick="pilihPanel('sains')">
                    <span class="jendela-label-fungsi">SAINS & KOMPUTER</span>
                    <h3 class="jendela-panel-judul">Sains & Teknologi</h3>
                    <p class="jendela-panel-ket">Koleksi e-book teknologi, koding, & sains dari dBooks API.</p>
                </button>
            </div>
        </section>

        <!-- Area Pencarian "Rel Jendela" -->
        <section class="area-pencarian-jendela">
            <div class="wadah-rel-pencarian">
                <i class="bi bi-search ikon-cari-jendela"></i>
                <input type="text" id="inputCariBuku" class="input-pencarian-jendela" placeholder="Cari karya warga, pustaka umum, buku sains & teknologi, atau dokumen desa...">
            </div>
        </section>

        <!-- AREA PANEL KONTEN UTAMA -->
        
        <!-- Panel 1: Karya Warga Desa -->
        <div id="wadah-panel-karya">
            <!-- Baris Filter Kategori -->
            <div class="baris-filter-kategori">
                <button class="tombol-filter aktif" data-kategori="semua">Semua Karya</button>
                <button class="tombol-filter" data-kategori="Sejarah">Sejarah Desa</button>
                <button class="tombol-filter" data-kategori="Cerpen">Cerpen</button>
                <button class="tombol-filter" data-kategori="Puisi">Puisi</button>
                <button class="tombol-filter" data-kategori="Artikel UMKM">Artikel UMKM</button>
                <button class="tombol-filter" data-kategori="Lainnya">Lainnya</button>
            </div>

            <div id="loading-karya" class="putar-loading d-none"></div>
            <div id="daftar-karya" class="tata-letak-grid">
                <!-- Diisi via REST API /api/karya -->
            </div>
            
            <!-- Empty State Berpengumuman Desa -->
            <div id="kosong-karya" class="papan-pengumuman-kosong d-none">
                <p class="teks-papan-desa">
                    "Papan karya desa ini masih kosong. Kalau Pak RT Anda punya cerita masa lalu kampung ini, atau Ibu warung menulis resep leluhur — inilah tempatnya. Klik tombol 'Tulis Karya' di bawah."
                </p>
                <button class="btn tombol-baca" data-bs-toggle="modal" data-bs-target="#modalTambahKarya">
                    <i class="bi bi-pencil me-1"></i> Tulis Karya Pertama
                </button>
            </div>
        </div>

        <!-- Panel 2: Pustaka Umum (Google Books API) -->
        <div id="wadah-panel-pustaka" class="wadah-buku d-none">
            <!-- Indikator Memuat (Spinner Bootstrap) -->
            <div id="indikator-memuat" class="indikator-memuat text-center my-4 d-none">
                <div class="spinner-border text-success" role="status" style="width: 2.5rem; height: 2.5rem;">
                    <span class="visually-hidden">Memuat data Google Books...</span>
                </div>
                <p class="mt-2 teks-papan-desa">Mencari buku di Google Books API...</p>
            </div>

            <!-- Wadah Grid Hasil Buku -->
            <div id="baris-hasil-buku" class="baris-hasil-buku tata-letak-grid">
                <!-- Diisi via Google Books API -->
            </div>
            
            <!-- Empty State Pustaka Umum -->
            <div id="kosong-pustaka" class="papan-pengumuman-kosong d-none">
                <p class="teks-papan-desa">
                    "Koleksi umum belum menyimpan itu — coba kata kunci lain."
                </p>
            </div>
        </div>

        <!-- Panel 3: Koleksi Sains & Teknologi (dBooks API) -->
        <div id="wadah-panel-sains" class="wadah-koleksi d-none">
            <!-- Indikator Loading -->
            <div class="text-center d-none indikator-memuat my-4" id="indikator-memuat-sains">
                <div class="spinner-border text-warning" role="status" style="width: 2.5rem; height: 2.5rem;">
                    <span class="visually-hidden">Mencari buku...</span>
                </div>
                <p class="mt-2 teks-papan-desa">Mencari koleksi sains & teknologi di dBooks API...</p>
            </div>

            <!-- Wadah Grid Hasil Pencarian -->
            <div id="wadah-hasil-sains" class="tata-letak-grid baris-hasil-sains">
                <!-- Kartu buku akan di-inject ke sini oleh JavaScript -->
            </div>
            
            <!-- Empty State Koleksi Sains -->
            <div id="kosong-sains" class="papan-pengumuman-kosong d-none">
                <p class="teks-papan-desa">
                    "Buku sains & teknologi tidak ditemukan."
                </p>
            </div>
        </div>

        <!-- SEKSI 3: RAK BUKU DESA (DOCUMENT / PDF COLLECTION) -->
        <section class="seksi-buku-desa">
            <h2 class="judul-seksi-desa">Dokumen & Buku Desa (PDF)</h2>
            <p class="sub-seksi-desa">Modul pelatihan, laporan tahunan desa, dan buku panduan resmi karangan warga.</p>
            
            <div id="loading-buku-desa" class="putar-loading d-none"></div>
            <div id="daftar-buku-desa" class="tata-letak-grid">
                <!-- Diisi via REST API /api/buku-lokal -->
            </div>
            
            <!-- Empty State Buku Desa PDF -->
            <div id="kosong-buku-desa" class="papan-pengumuman-kosong d-none bg-transparent border-secondary text-light">
                <p class="teks-papan-desa text-light">
                    "Rak dokumen desa masih kosong. Berkas panduan, modul UMKM, atau laporan tahunan desa dari admin akan muncul di sini."
                </p>
            </div>
        </section>

    </main>

    <!-- Signature Floating Action Button (Persegi Panjang Kayu) -->
    <button class="tombol-tulis-karya" data-bs-toggle="modal" data-bs-target="#modalTambahKarya" title="Tulis Karya Baru">
        <i class="bi bi-pencil-square me-2"></i> Tulis Karya
    </button>

    <!-- Modal Unggah Karya Warga -->
    <div class="modal fade" id="modalTambahKarya" tabindex="-1" aria-labelledby="modalTambahKaryaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered dialog-unggah">
            <div class="modal-content">
                <form id="formKaryaWarga" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="modalTambahKaryaLabel">
                            <i class="bi bi-feather me-2 text-teal"></i>Kirim Karya Warga
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small mb-4">Bagikan kearifan lokal, sejarah desa, puisi, cerpen, artikel UMKM, atau unggah buku/dokumen karya Anda dalam bentuk PDF.</p>
                        
                        <div class="mb-3">
                            <label for="nama_penulis" class="form-label fw-semibold text-secondary">Nama Penulis <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="nama_penulis" name="nama_penulis" required placeholder="Masukkan nama lengkap Anda..." maxlength="150">
                        </div>
                        
                        <div class="mb-3">
                            <label for="judul_karya" class="form-label fw-semibold text-secondary">Judul Karya / Buku <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="judul_karya" name="judul_karya" required placeholder="Masukkan judul karya..." maxlength="255">
                        </div>
                        
                        <div class="mb-3">
                            <label for="kategori" class="form-label fw-semibold text-secondary">Kategori Karya <span class="text-danger">*</span></label>
                            <select class="form-select" id="kategori" name="kategori" required>
                                <option value="" disabled selected>Pilih Kategori...</option>
                                <option value="Sejarah">Sejarah Desa</option>
                                <option value="Cerpen">Cerita Pendek (Cerpen)</option>
                                <option value="Puisi">Puisi</option>
                                <option value="Artikel UMKM">Artikel UMKM / Usaha Desa</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="sampul_file" class="form-label fw-semibold text-secondary label-sampul-karya">Gambar Sampul Buku / Karya (Opsional - JPG/PNG/WebP)</label>
                            <input type="file" class="form-control input-sampul-karya" id="sampul_file" name="sampul_file" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label for="berkas_pdf" class="form-label fw-semibold text-secondary label-berkas-pdf">Berkas PDF Karya (Opsional - Maksimal 20MB)</label>
                            <input type="file" class="form-control input-berkas-pdf" id="berkas_pdf" name="berkas_pdf" accept=".pdf">
                        </div>
                        
                        <div class="mb-3">
                            <label for="isi_karya" class="form-label fw-semibold text-secondary">Isi Teks Karya / Sinopsis Singkat</label>
                            <textarea class="form-control" id="isi_karya" name="isi_karya" rows="5" placeholder="Tuliskan karya tulis atau ringkasan dokumen Anda di sini..." style="resize: none;" maxlength="2000"></textarea>
                            <div class="penanda-karakter" id="hitungKarakter">0 / 2000 karakter</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-link text-secondary text-decoration-none me-3" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn tombol-kirim" id="tombolSubmitKarya">
                            <i class="bi bi-send-fill me-2"></i>Kirim Karya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Detail Karya (Untuk Membaca Selengkapnya) -->
    <div class="modal fade" id="modalBacaKarya" tabindex="-1" aria-labelledby="modalBacaKaryaLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg dialog-unggah">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <span class="lencana-kategori mb-2 d-inline-block" id="baca-kategori">Kategori</span>
                        <h5 class="modal-title fw-bold text-teal" id="modalBacaKaryaLabel">Judul Karya</h5>
                        <small class="text-muted" id="baca-meta">Ditulis oleh Penulis</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 60vh; overflow-y: auto;">
                    <div class="lh-lg serif-bacaan" id="baca-isi" style="white-space: pre-wrap;">
                        Isi karya...
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Baca PDF Buku Desa -->
    <div class="modal fade" id="modal-baca-pdf" tabindex="-1" aria-labelledby="label-modal-baca-pdf" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-xl modal-dialog-centered dialog-baca-pdf">
            <div class="modal-content bingkai-modal-baca border-0 shadow-lg rounded-4">
                <div class="modal-header kepala-modal-baca bg-dark text-white py-3 border-bottom border-secondary">
                    <h5 class="modal-title fw-bold d-flex align-items-center mb-0" id="label-modal-baca-pdf">
                        <i class="bi bi-file-earmark-pdf me-2"></i>
                        <span id="judul-buku-modal">Membaca Buku Desa</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white tombol-tutup-x" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body badan-modal-baca p-0 bg-dark position-relative">
                    <iframe id="elemen-penampil-pdf" class="penampil-pdf w-100 d-block" style="height: 80vh; border: none;" src="" title="Penampil Berkas PDF Buku Desa"></iframe>
                </div>
                <div class="modal-footer kaki-modal-baca d-flex justify-content-between align-items-center bg-dark text-light border-top border-secondary">
                    <span class="small text-muted petunjuk-baca"><i class="bi bi-info-circle me-1"></i>Dokumen PDF Desa Resmi</span>
                    <div class="d-flex gap-2">
                        <a href="#" id="tombol-unduh-buku" class="btn tombol-baca text-white border-white rounded-pill px-4 tombol-unduh-buku" download target="_blank">
                            <i class="bi bi-download me-1"></i> Unduh PDF
                        </a>
                        <button type="button" class="btn btn-secondary rounded-pill px-4 tombol-tutup-modal" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Axios JS -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Client-side Logic (Vanilla JavaScript / Axios) -->
    <script>
        // Global Handler Navigasi Panel Jendela (Karya Warga, Pustaka Umum, Sains & Teknologi)
        window.pilihPanel = (jenis) => {
            const btnKarya = document.getElementById('tombol-panel-karya');
            const btnPustaka = document.getElementById('tombol-panel-pustaka');
            const btnSains = document.getElementById('tombol-panel-sains');

            const panelKarya = document.getElementById('wadah-panel-karya');
            const panelPustaka = document.getElementById('wadah-panel-pustaka');
            const panelSains = document.getElementById('wadah-panel-sains');

            if (btnKarya) btnKarya.classList.remove('aktif');
            if (btnPustaka) btnPustaka.classList.remove('aktif');
            if (btnSains) btnSains.classList.remove('aktif');

            if (panelKarya) panelKarya.classList.add('d-none');
            if (panelPustaka) panelPustaka.classList.add('d-none');
            if (panelSains) panelSains.classList.add('d-none');

            if (jenis === 'karya') {
                if (btnKarya) btnKarya.classList.add('aktif');
                if (panelKarya) panelKarya.classList.remove('d-none');
            } else if (jenis === 'sains') {
                if (btnSains) btnSains.classList.add('aktif');
                if (panelSains) panelSains.classList.remove('d-none');
            } else {
                if (btnPustaka) btnPustaka.classList.add('aktif');
                if (panelPustaka) panelPustaka.classList.remove('d-none');
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            const body = document.body;
            const btnToggleTema = document.getElementById('btnToggleTema');
            const ikonTema = document.getElementById('ikonTema');
            
            const masukanPencarian = document.getElementById('masukan-pencarian');
            const tombolCari = document.getElementById('tombol-cari');
            const indikatorMemuat = document.getElementById('indikator-memuat');
            const barisHasilBuku = document.getElementById('baris-hasil-buku');
            const kosongPustaka = document.getElementById('kosong-pustaka');

            const daftarBuku = barisHasilBuku || document.getElementById('daftar-buku');
            const loadingPustaka = indikatorMemuat || document.getElementById('loading-pustaka');
            
            const daftarKarya = document.getElementById('daftar-karya');
            const loadingKarya = document.getElementById('loading-karya');
            const kosongKarya = document.getElementById('kosong-karya');
            
            const formKaryaWarga = document.getElementById('formKaryaWarga');
            const isiKaryaInput = document.getElementById('isi_karya');
            const hitungKarakter = document.getElementById('hitungKarakter');
            
            const modalTambahKarya = document.getElementById('modalTambahKarya') ? new bootstrap.Modal(document.getElementById('modalTambahKarya')) : null;
            const modalBacaKarya = document.getElementById('modalBacaKarya') ? new bootstrap.Modal(document.getElementById('modalBacaKarya')) : null;

            // State management
            let localKaryaCache = [];
            let kategoriFilterAktif = 'semua';
            let kueriPencarian = '';

            // Pengaturan Tema
            const muatTemaPreferensi = () => {
                const temaTersimpan = localStorage.getItem('theme');
                const preferensiGelapSystem = window.matchMedia('(prefers-color-scheme: dark)').matches;
                
                if (temaTersimpan === 'dark' || (!temaTersimpan && preferensiGelapSystem)) {
                    body.classList.add('theme-dark');
                    if (ikonTema) ikonTema.className = 'bi bi-sun';
                } else {
                    body.classList.remove('theme-dark');
                    if (ikonTema) ikonTema.className = 'bi bi-moon-stars';
                }
            };

            if (btnToggleTema) {
                btnToggleTema.addEventListener('click', () => {
                    if (body.classList.contains('theme-dark')) {
                        body.classList.remove('theme-dark');
                        if (ikonTema) ikonTema.className = 'bi bi-moon-stars';
                        localStorage.setItem('theme', 'light');
                    } else {
                        body.classList.add('theme-dark');
                        if (ikonTema) ikonTema.className = 'bi bi-sun';
                        localStorage.setItem('theme', 'dark');
                    }
                });
            }

            // Pembaca Estimasi Waktu (Read Time) & Kategori CSS
            const hitungEstimasiWaktuBaca = (teks) => {
                if (!teks) return 1;
                const jumlahKata = teks.trim().split(/\s+/).filter(word => word.length > 0).length;
                return Math.max(1, Math.ceil(jumlahKata / 200));
            };

            const dapatkanKelasKategori = (kategori) => {
                const mapKelas = {
                    'Sejarah': 'kategori-sejarah',
                    'Cerpen': 'kategori-cerpen',
                    'Puisi': 'kategori-puisi',
                    'Artikel UMKM': 'kategori-umkm',
                    'Lainnya': 'kategori-lainnya'
                };
                return mapKelas[kategori] || 'kategori-lainnya';
            };
            // Koleksi Kurasi Fallback Google Books API
            const bukuFallbackGoogle = [
                {
                    id: "d6y2DwAAQBAJ",
                    volumeInfo: {
                        title: "Laskar Pelangi",
                        authors: ["Andrea Hirata"],
                        imageLinks: { thumbnail: "https://books.google.com/books/content?id=d6y2DwAAQBAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api" },
                        previewLink: "https://www.google.co.id/books/edition/_/d6y2DwAAQBAJ?hl=id&gbpv=1"
                    }
                },
                {
                    id: "pD1xAwAAQBAJ",
                    volumeInfo: {
                        title: "Bumi",
                        authors: ["Tere Liye"],
                        imageLinks: { thumbnail: "https://books.google.com/books/content?id=pD1xAwAAQBAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api" },
                        previewLink: "https://www.google.co.id/books/edition/_/pD1xAwAAQBAJ?hl=id&gbpv=1"
                    }
                },
                {
                    id: "_Y8ZEAAAQBAJ",
                    volumeInfo: {
                        title: "Smart Village: Transformasi Digital Desa",
                        authors: ["Kementerian Kominfo"],
                        imageLinks: { thumbnail: "https://books.google.com/books/content?id=_Y8ZEAAAQBAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api" },
                        previewLink: "https://www.google.co.id/books/edition/_/_Y8ZEAAAQBAJ?hl=id&gbpv=1"
                    }
                },
                {
                    id: "y96BDwAAQBAJ",
                    volumeInfo: {
                        title: "Filosofi Teras",
                        authors: ["Henry Manampiring"],
                        imageLinks: { thumbnail: "https://books.google.com/books/content?id=y96BDwAAQBAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api" },
                        previewLink: "https://www.google.co.id/books/edition/_/y96BDwAAQBAJ?hl=id&gbpv=1"
                    }
                }
            ];

            const dapatkanTautanBukuGoogle = (item) => {
                const infoVolume = item.volumeInfo || {};
                if (infoVolume.previewLink) return infoVolume.previewLink;
                if (infoVolume.infoLink) return infoVolume.infoLink;
                if (item.id) return `https://www.google.co.id/books/edition/_/${item.id}?hl=id&gbpv=1`;
                return 'https://books.google.co.id';
            };

            const tampilkanDaftarBukuGoogle = (dataBuku) => {
                const targetWadah = barisHasilBuku || daftarBuku;
                if (!targetWadah) return;
                targetWadah.innerHTML = '';
                if (kosongPustaka) kosongPustaka.classList.add('d-none');

                dataBuku.forEach(item => {
                    const infoVolume = item.volumeInfo || {};
                    const judul = infoVolume.title || 'Tanpa Judul';
                    const penulis = infoVolume.authors ? infoVolume.authors.join(', ') : 'Penulis Tidak Diketahui';
                    
                    let sampul = 'https://placehold.co/300x450/8B5E3C/ffffff?text=Google+Books';
                    if (infoVolume.imageLinks) {
                        sampul = infoVolume.imageLinks.thumbnail || infoVolume.imageLinks.smallThumbnail || sampul;
                        sampul = sampul.replace('http://', 'https://').replace('&edge=curl', '');
                    }
                    
                    const infoTautan = dapatkanTautanBukuGoogle(item);

                    const htmlKartu = `
                        <div class="kartu-buku">
                            <div class="wadah-sampul text-center">
                                <img src="${sampul}" alt="${judul}" class="gambar-sampul sampul-buku" onerror="this.src='https://placehold.co/300x450/8B5E3C/ffffff?text=Google+Books';">
                            </div>
                            <h5 class="judul-buku teks-judul" title="${judul}">${judul}</h5>
                            <p class="penulis-buku teks-penulis"><i class="bi bi-person me-1"></i>${penulis}</p>
                            <a href="${infoTautan}" class="tombol-baca btn btn-success mt-3 w-100 d-flex align-items-center justify-content-center gap-2" target="_blank" rel="noopener noreferrer">
                                <i class="bi bi-book-open"></i>
                                <span>Baca Preview di Google Books</span>
                            </a>
                        </div>
                    `;
                    targetWadah.insertAdjacentHTML('beforeend', htmlKartu);
                });
                targetWadah.classList.remove('d-none');
            };

            // Integrasi Google Books API
            const cariBukuPustaka = async (kataKunci = 'desa indonesia') => {
                const targetWadah = barisHasilBuku || daftarBuku;
                const targetLoading = indikatorMemuat || loadingPustaka;
                const kueri = (kataKunci && kataKunci.trim() !== '') ? kataKunci.trim() : 'desa indonesia';

                tampilkanDaftarBukuGoogle(bukuFallbackGoogle);

                try {
                    const URL_API = `https://www.googleapis.com/books/v1/volumes?q=${encodeURIComponent(kueri)}&maxResults=12`;
                    const response = await axios.get(URL_API, { timeout: 3500 });
                    const dataBuku = response.data.items || [];
                    if (dataBuku.length > 0) {
                        tampilkanDaftarBukuGoogle(dataBuku);
                    }
                } catch (error) {
                    console.warn('Google Books API terhambat, menggunakan fallback:', error);
                } finally {
                    if (targetLoading) targetLoading.classList.add('d-none');
                }
            };

            // Integrasi dBooks API (Sains & Komputer)
            const inputSains = document.getElementById('input-sains') || document.getElementById('masukan-pencarian-sains');
            const tombolCariSains = document.getElementById('tombol-cari-sains');
            const wadahHasilSains = document.getElementById('wadah-hasil-sains') || document.getElementById('baris-hasil-buku-sains');
            const indikatorMemuatSains = document.getElementById('indikator-memuat-sains');

            const bukuFallbackSains = [
                {
                    title: "Python Basics: A Practical Introduction to Python 3",
                    authors: "Real Python",
                    image: "https://www.dbooks.org/img/books/1607593649s.jpg",
                    url: "https://www.dbooks.org/python-basics-1607593649/"
                },
                {
                    title: "Automate the Boring Stuff with Python",
                    authors: "Al Sweigart",
                    image: "https://www.dbooks.org/img/books/591410189s.jpg",
                    url: "https://www.dbooks.org/automate-the-boring-stuff-with-python-591410189/"
                },
                {
                    title: "JavaScript for Impatient Programmers",
                    authors: "Axel Rauschmayer",
                    image: "https://www.dbooks.org/img/books/1792683979s.jpg",
                    url: "https://www.dbooks.org/javascript-for-impatient-programmers-1792683979/"
                },
                {
                    title: "Pro Git: Everything you need to know about Git",
                    authors: "Scott Chacon & Ben Straub",
                    image: "https://www.dbooks.org/img/books/1484200772s.jpg",
                    url: "https://www.dbooks.org/pro-git-1484200772/"
                }
            ];

            const tampilkanBukuSains = (daftarBuku) => {
                if (!wadahHasilSains) return;
                let strukturHtml = '';
                daftarBuku.forEach(buku => {
                    const judul = buku.title || 'Judul Tidak Diketahui';
                    const penulis = buku.authors || 'Penulis Tidak Diketahui';
                    const sampul = buku.image || 'https://placehold.co/300x420/0288d1/ffffff?text=dBooks+Sains';
                    const urlTautan = buku.url || '#';

                    strukturHtml += `
                        <div class="kartu-buku text-light">
                            <div class="wadah-sampul text-center">
                                <img src="${sampul}" class="gambar-sampul sampul-buku" alt="${judul}" onerror="this.src='https://placehold.co/300x420/0288d1/ffffff?text=dBooks+Sains';">
                            </div>
                            <h5 class="judul-buku teks-judul mt-2" title="${judul}">${judul}</h5>
                            <p class="penulis-buku teks-penulis mb-3"><i class="bi bi-person me-1"></i>${penulis}</p>
                            <a href="${urlTautan}" target="_blank" rel="noopener noreferrer" class="tombol-unduh btn btn-primary mt-auto w-100 d-flex align-items-center justify-content-center gap-2">
                                <i class="bi bi-download"></i>
                                <span>Unduh PDF</span>
                            </a>
                        </div>
                    `;
                });
                wadahHasilSains.innerHTML = strukturHtml;
            };

            const cariBukuSains = async (kataKunci = 'python') => {
                if (!wadahHasilSains) return;
                const kueri = (kataKunci && kataKunci.trim() !== '') ? kataKunci.trim() : 'python';

                tampilkanBukuSains(bukuFallbackSains);

                if (indikatorMemuatSains) indikatorMemuatSains.classList.remove('d-none');

                try {
                    const respon = await axios.get(`https://www.dbooks.org/api/search/${encodeURIComponent(kueri)}`);
                    const data = respon.data;
                    if (data && data.status === 'ok' && data.books && data.books.length > 0) {
                        tampilkanBukuSains(data.books.slice(0, 12));
                    }
                } catch (error) {
                    console.warn('API dBooks terhambat, menggunakan fallback sains:', error);
                } finally {
                    if (indikatorMemuatSains) indikatorMemuatSains.classList.add('d-none');
                }
            };

            if (tombolCariSains) {
                tombolCariSains.addEventListener('click', (e) => {
                    e.preventDefault();
                    const kueri = inputSains ? inputSains.value.trim() : '';
                    if (kueri) cariBukuSains(kueri);
                });
            }

            if (inputSains) {
                inputSains.addEventListener('keypress', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const kueri = inputSains.value.trim();
                        if (kueri) cariBukuSains(kueri);
                    }
                });
            }

            // Integrasi REST API Karya Warga Desa
            const karyaFallbackLokal = [];

            const muatKaryaWarga = () => {
                if (loadingKarya) loadingKarya.classList.remove('d-none');
                
                axios.get('/api/karya')
                    .then(response => {
                        const dataRespon = response.data || [];
                        localKaryaCache = dataRespon.length > 0 ? dataRespon : karyaFallbackLokal;
                        tampilkanKaryaFiltered();
                    })
                    .catch(error => {
                        console.warn('Gagal memuat karya warga dari server, menggunakan kurasi lokal:', error);
                        localKaryaCache = karyaFallbackLokal;
                        tampilkanKaryaFiltered();
                    })
                    .finally(() => {
                        if (loadingKarya) loadingKarya.classList.add('d-none');
                    });
            };

            const tampilkanKaryaFiltered = () => {
                if (!daftarKarya) return;
                daftarKarya.innerHTML = '';
                const searchLower = (kueriPencarian || '').toLowerCase().trim();

                const filteredList = localKaryaCache.filter(karya => {
                    const kategoriKarya = karya.kategori || '';
                    const judulKarya = karya.judul_karya || karya.judul || '';
                    const penulisKarya = karya.nama_penulis || karya.penulis || '';
                    const isiKarya = karya.isi_karya || karya.isi || '';

                    const matchesCategory = (kategoriFilterAktif === 'semua') || (kategoriKarya === kategoriFilterAktif);
                    const matchesSearch = !searchLower || 
                        judulKarya.toLowerCase().includes(searchLower) ||
                        penulisKarya.toLowerCase().includes(searchLower) ||
                        isiKarya.toLowerCase().includes(searchLower) ||
                        kategoriKarya.toLowerCase().includes(searchLower);
                    
                    return matchesCategory && matchesSearch;
                });

                if (filteredList.length === 0) {
                    if (kosongKarya) kosongKarya.classList.remove('d-none');
                    if (daftarKarya) daftarKarya.classList.add('d-none');
                    return;
                }

                if (kosongKarya) kosongKarya.classList.add('d-none');
                filteredList.forEach(karya => {
                    const judulTampil = karya.judul_karya || karya.judul || 'Tanpa Judul';
                    const penulisTampil = karya.nama_penulis || karya.penulis || 'Penulis Desa';
                    const isiTampil = karya.isi_karya || karya.isi || '';
                    const ringkasTampil = karya.isi_ringkas || (isiTampil.length > 100 ? isiTampil.substring(0, 100) + '...' : isiTampil);
                    const kategoriTampil = karya.kategori || 'Umum';

                    const kelasKat = dapatkanKelasKategori(kategoriTampil);
                    const estimasiMenit = hitungEstimasiWaktuBaca(isiTampil);
                    const urlPdf = karya.pdf_url || (karya.jalur_pdf ? `/storage/${karya.jalur_pdf}` : null);
                    const judulEscaped = judulTampil.toString().replace(/'/g, "\\'");
                    
                    const wadahSampulHtml = karya.sampul_url ? `
                        <div class="wadah-sampul">
                            <img src="${karya.sampul_url}" alt="${judulTampil}" class="sampul-buku" onerror="this.style.display='none';">
                        </div>
                    ` : '';

                    const tombolAksiHtml = urlPdf ? `
                        <button type="button" class="btn tombol-baca mt-auto w-100" onclick="event.stopPropagation(); bacaBukuPdf('${urlPdf}', '${judulEscaped}')">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Baca PDF Karya
                        </button>
                    ` : `
                        <button type="button" class="tombol-baca btn btn-outline-secondary w-100 mt-3" onclick="bacaKaryaLengkap(${karya.id})">
                            <i class="bi bi-eye me-1"></i> Baca Selengkapnya
                        </button>
                    `;

                    const htmlKartu = `
                        <div class="kartu-karya" ${urlPdf ? `onclick="bacaBukuPdf('${urlPdf}', '${judulEscaped}')" style="cursor: pointer;"` : ''}>
                            ${wadahSampulHtml}
                            <span class="lencana-kategori ${kelasKat}">${kategoriTampil}</span>
                            <h5 class="teks-judul mt-2">${judulTampil}</h5>
                            <p class="teks-penulis"><i class="bi bi-pen me-1"></i>${penulisTampil}</p>
                            <p class="teks-karya-preview">${ringkasTampil}</p>
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-2">
                                ${tombolAksiHtml}
                                <span class="karya-meta-detik ms-2 text-nowrap">
                                    <i class="bi bi-clock me-1"></i>${estimasiMenit} mnt
                                </span>
                            </div>
                        </div>
                    `;
                    daftarKarya.insertAdjacentHTML('beforeend', htmlKartu);
                });
                daftarKarya.classList.remove('d-none');
            };

            window.bacaKaryaLengkap = (id) => {
                const karya = localKaryaCache.find(item => item.id === id);
                if (karya) {
                    const judulTampil = karya.judul_karya || karya.judul || 'Tanpa Judul';
                    const penulisTampil = karya.nama_penulis || karya.penulis || 'Penulis Desa';
                    const isiTampil = karya.isi_karya || karya.isi || '';
                    const kategoriTampil = karya.kategori || 'Umum';

                    const kelasKat = dapatkanKelasKategori(kategoriTampil);
                    const estimasiMenit = hitungEstimasiWaktuBaca(isiTampil);

                    const lencanaSpan = document.getElementById('baca-kategori');
                    if (lencanaSpan) {
                        lencanaSpan.innerText = kategoriTampil;
                        lencanaSpan.className = `lencana-kategori ${kelasKat} mb-2 d-inline-block`;
                    }
                    
                    const judulEl = document.getElementById('modalBacaKaryaLabel');
                    if (judulEl) judulEl.innerText = judulTampil;

                    const metaEl = document.getElementById('baca-meta');
                    if (metaEl) {
                        metaEl.innerHTML = `
                            Ditulis oleh <strong>${penulisTampil}</strong> &bull; 
                            ${karya.tanggal || 'Baru Saja'} &bull; 
                            ${estimasiMenit} menit membaca
                        `;
                    }

                    const isiEl = document.getElementById('baca-isi');
                    if (isiEl) isiEl.innerText = isiTampil;

                    if (modalBacaKarya) modalBacaKarya.show();
                }
            };

            // Integrasi REST API Buku Lokal Desa (Dokumen PDF Desa)
            const bukuDesaFallbackLokal = [
                {
                    id: 201,
                    judul: "Peraturan Desa No. 04 Tahun 2025 tentang Ketahanan Pangan",
                    penulis: "Pemerintah Desa",
                    sinopsis: "Panduan resmi pengelolaan alokasi dana desa untuk program ketahanan pangan dan UMKM.",
                    pdf_url: "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
                    sampul_url: "https://placehold.co/300x420/224C63/ffffff?text=PERDES+NO.+04%0AKetahanan+Pangan"
                },
                {
                    id: 202,
                    judul: "Laporan Pertanggungjawaban APBDes 2025",
                    penulis: "Bendahara Desa",
                    sinopsis: "Rincian transparansi anggaran belanja desa, pembangunan infrastruktur, dan pemberdayaan warga.",
                    pdf_url: "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
                    sampul_url: "https://placehold.co/300x420/3D2918/ffffff?text=LAPORAN+APBDES%0ATahun+2025"
                },
                {
                    id: 203,
                    judul: "Modul Pelatihan UMKM & Kewirausahaan Desa",
                    penulis: "Tim Pendamping Desa",
                    sinopsis: "Panduan strategi pemasaran digital, manajemen keuangan mikro, dan pendaftaran ijin usaha warga.",
                    pdf_url: "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
                    sampul_url: "https://placehold.co/300x420/2B5E33/ffffff?text=MODUL+PELATIHAN%0AUMKM+Desa"
                },
                {
                    id: 204,
                    judul: "Buku Panduan Karang Taruna & Kegiatan Desa",
                    penulis: "Pengurus Karang Taruna",
                    sinopsis: "Struktur organisasi, kalender acara tahunan, serta panduan kegiatan pemuda karang taruna desa.",
                    pdf_url: "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf",
                    sampul_url: "https://placehold.co/300x420/622B69/ffffff?text=PANDUAN%0AKarang+Taruna"
                }
            ];

            let localBukuDesaCache = [];
            const loadingBukuDesa = document.getElementById('loading-buku-desa');
            const daftarBukuDesa = document.getElementById('daftar-buku-desa');
            const kosongBukuDesa = document.getElementById('kosong-buku-desa');

            const muatBukuDesa = () => {
                if (loadingBukuDesa) loadingBukuDesa.classList.remove('d-none');
                
                axios.get('/api/buku-lokal')
                    .then(response => {
                        const dataRespon = response.data || [];
                        localBukuDesaCache = dataRespon.length > 0 ? dataRespon : bukuDesaFallbackLokal;
                        tampilkanBukuDesaFiltered();
                    })
                    .catch(error => {
                        console.warn('Gagal memuat buku lokal desa:', error);
                        localBukuDesaCache = bukuDesaFallbackLokal;
                        tampilkanBukuDesaFiltered();
                    })
                    .finally(() => {
                        if (loadingBukuDesa) loadingBukuDesa.classList.add('d-none');
                    });
            };

            window.bacaBukuPdf = (urlPdf, judul) => {
                const penampilPdf = document.getElementById('elemen-penampil-pdf');
                const judulModal = document.getElementById('judul-buku-modal');
                const tombolUnduh = document.getElementById('tombol-unduh-buku');

                if (penampilPdf) penampilPdf.src = urlPdf;
                if (judulModal) judulModal.innerText = judul;
                if (tombolUnduh) {
                    tombolUnduh.href = urlPdf;
                    tombolUnduh.setAttribute('download', judul + '.pdf');
                }

                const modalPdfEl = document.getElementById('modal-baca-pdf');
                if (modalPdfEl) {
                    const modalPdf = bootstrap.Modal.getOrCreateInstance(modalPdfEl);
                    modalPdf.show();
                }
            };

            const modalPdfEl = document.getElementById('modal-baca-pdf');
            if (modalPdfEl) {
                modalPdfEl.addEventListener('hidden.bs.modal', () => {
                    const penampilPdf = document.getElementById('elemen-penampil-pdf');
                    if (penampilPdf) penampilPdf.src = '';
                });
            }

            const tampilkanBukuDesaFiltered = () => {
                if (!daftarBukuDesa) return;
                daftarBukuDesa.innerHTML = '';
                const searchLower = (kueriPencarian || '').toLowerCase().trim();

                const filteredList = localBukuDesaCache.filter(buku => {
                    const judul = buku.judul || '';
                    const penulis = buku.penulis || '';
                    const sinopsis = buku.sinopsis || '';
                    return !searchLower || 
                        judul.toLowerCase().includes(searchLower) ||
                        penulis.toLowerCase().includes(searchLower) ||
                        sinopsis.toLowerCase().includes(searchLower);
                });

                if (filteredList.length === 0) {
                    if (kosongBukuDesa) kosongBukuDesa.classList.remove('d-none');
                    if (daftarBukuDesa) daftarBukuDesa.classList.add('d-none');
                    return;
                }

                if (kosongBukuDesa) kosongBukuDesa.classList.add('d-none');
                filteredList.forEach(buku => {
                    const judulTampil = buku.judul || 'Dokumen Desa';
                    const penulisTampil = buku.penulis || 'Admin Desa';
                    const urlPdf = buku.pdf_url || (buku.jalur_pdf ? `/storage/${buku.jalur_pdf}` : '#');
                    const judulEscaped = judulTampil.toString().replace(/'/g, "\\'");
                    
                    const urlSampul = buku.sampul_url || (buku.jalur_sampul ? `/storage/${buku.jalur_sampul}` : `https://placehold.co/300x420/224C63/ffffff?text=${encodeURIComponent(judulTampil)}`);

                    const htmlKartu = `
                        <div class="kartu-buku" onclick="bacaBukuPdf('${urlPdf}', '${judulEscaped}')" style="cursor: pointer;">
                            <div class="wadah-sampul text-center">
                                <img src="${urlSampul}" alt="${judulTampil}" class="sampul-buku gambar-sampul" onerror="this.src='https://placehold.co/300x420/224C63/ffffff?text=Dokumen+PDF+Desa';">
                            </div>
                            <span class="lencana-kategori kategori-umkm mb-2 d-inline-block">Dokumen PDF Desa</span>
                            <h5 class="teks-judul judul-buku mt-1" title="${judulTampil}">${judulTampil}</h5>
                            <p class="teks-penulis penulis-buku"><i class="bi bi-person me-1"></i>${penulisTampil}</p>
                            <p class="teks-karya-preview">${buku.sinopsis || 'Dokumen / Berkas Panduan Resmi Desa'}</p>
                            <button type="button" class="btn tombol-baca mt-auto w-100 d-flex align-items-center justify-content-center gap-2" onclick="event.stopPropagation(); bacaBukuPdf('${urlPdf}', '${judulEscaped}')">
                                <i class="bi bi-file-earmark-pdf me-1"></i>
                                <span>Baca PDF Desa</span>
                            </button>
                        </div>
                    `;
                    daftarBukuDesa.insertAdjacentHTML('beforeend', htmlKartu);
                });
                daftarBukuDesa.classList.remove('d-none');
            };

            // Pencarian Universal & Filter Kategori
            let penundaCari;
            const inputCari = document.getElementById('inputCariBuku');
            if (inputCari) {
                inputCari.addEventListener('input', (e) => {
                    kueriPencarian = e.target.value.trim();
                    
                    tampilkanKaryaFiltered();
                    tampilkanBukuDesaFiltered();

                    clearTimeout(penundaCari);
                    penundaCari = setTimeout(() => {
                        if (kueriPencarian.length >= 2) {
                            cariBukuPustaka(kueriPencarian);
                            cariBukuSains(kueriPencarian);
                        } else if (kueriPencarian.length === 0) {
                            cariBukuPustaka('desa indonesia');
                            cariBukuSains('python');
                        }
                    }, 500);
                });
            }

            document.querySelectorAll('.tombol-filter').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    document.querySelectorAll('.tombol-filter').forEach(b => b.classList.remove('aktif'));
                    e.currentTarget.classList.add('aktif');
                    
                    kategoriFilterAktif = e.currentTarget.dataset.kategori;
                    tampilkanKaryaFiltered();
                });
            });

            // Form Submit Karya
            if (isiKaryaInput) {
                isiKaryaInput.addEventListener('input', () => {
                    const panjang = isiKaryaInput.value.length;
                    if (hitungKarakter) {
                        hitungKarakter.innerText = `${panjang} / 2000 karakter`;
                        if (panjang >= 2000) {
                            hitungKarakter.classList.add('text-danger');
                        } else {
                            hitungKarakter.classList.remove('text-danger');
                        }
                    }
                });
            }

            if (formKaryaWarga) {
                formKaryaWarga.addEventListener('submit', (e) => {
                    e.preventDefault();

                    const formData = new FormData(formKaryaWarga);

                    const tombolSubmit = document.getElementById('tombolSubmitKarya');
                    const teksAsli = tombolSubmit ? tombolSubmit.innerHTML : '';
                    if (tombolSubmit) {
                        tombolSubmit.disabled = true;
                        tombolSubmit.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status"></span>Mengirim...`;
                    }

                    axios.post('/api/karya', formData, {
                        headers: { 'Content-Type': 'multipart/form-data' }
                    })
                        .then(response => {
                            if (tombolSubmit) {
                                tombolSubmit.disabled = false;
                                tombolSubmit.innerHTML = teksAsli;
                            }
                            if (modalTambahKarya) modalTambahKarya.hide();
                            formKaryaWarga.reset();
                            if (hitungKarakter) hitungKarakter.innerText = `0 / 2000 karakter`;

                            Swal.fire({
                                title: 'Berhasil Dikirim!',
                                text: response.data.message || 'Karya berhasil dikirim dan menunggu kurasi admin.',
                                icon: 'success',
                                confirmButtonColor: '#8B5E3C'
                            });
                            
                            muatKaryaWarga();
                        })
                        .catch(error => {
                            if (tombolSubmit) {
                                tombolSubmit.disabled = false;
                                tombolSubmit.innerHTML = teksAsli;
                            }

                            let pesanError = 'Terjadi kesalahan saat mengirim karya tulis Anda.';
                            if (error.response && error.response.data && error.response.data.errors) {
                                pesanError = Object.values(error.response.data.errors).flat().join('\n');
                            }

                            Swal.fire({
                                title: 'Gagal Mengirim',
                                text: pesanError,
                                icon: 'error',
                                confirmButtonColor: '#8B5E3C'
                            });
                        });
                });
            }

            // Initial startup
            muatTemaPreferensi();
            cariBukuPustaka('desa indonesia');
            cariBukuSains('python');
            muatKaryaWarga();
            muatBukuDesa();
        });
    </script>
</body>
</html>
