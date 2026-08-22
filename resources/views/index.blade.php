<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SyaTech - SPK Kelayakan Upgrade PC & Hardware Shop</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        html { scroll-behavior: smooth; }
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .hero-section { background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: white; padding: 50px 0; border-radius: 0 0 20px 20px; }
        .card-custom { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
        .score-circle { width: 110px; height: 110px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2.2rem; font-weight: bold; margin: 0 auto; }
        .bg-score-danger { background-color: #fee2e2; color: #dc2626; border: 4px solid #ef4444; }
        .bg-score-warning { background-color: #fef3c7; color: #d97706; border: 4px solid #f59e0b; }
        .bg-score-success { background-color: #dcfce7; color: #16a34a; border: 4px solid #22c55e; }
        .product-card { transition: transform 0.2s; }
        .product-card:hover { transform: translateY(-5px); }
        .product-img { height: 180px; object-fit: cover; border-radius: 10px 10px 0 0; }
    </style>
</head>
<body>

    <!-- STICKY NAVBAR NAVIGATION -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-cpu-fill text-primary"></i> SyaTech SPK
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto fw-semibold align-items-center gap-2">
                    <li class="nav-item"><a class="nav-link active" href="#home">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link" href="#spk">Analisis SPK</a></li>
                    <li class="nav-item"><a class="nav-link" href="#shop">Shop & Rekomendasi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">Tentang Kami</a></li>
                    
                    @if(Auth::check())
                        <!-- Jika User/Staff Sedang Login -->
                        @if(in_array(Auth::user()->role, ['owner', 'admin']))
                            <li class="nav-item">
                                <a href="/admin" class="btn btn-warning btn-sm rounded-pill fw-bold text-dark px-3">
                                    <i class="bi bi-speedometer2"></i> Dashboard Admin
                                </a>
                            </li>
                        @endif
                        
                        <!-- Tombol Logout Langsung dari Halaman Depan -->
                        <li class="nav-item">
                            <form action="/logout" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                    <i class="bi bi-box-arrow-right"></i> Logout ({{ Auth::user()->name }})
                                </button>
                            </form>
                        </li>
                    @else
                        <!-- Jika Pengunjung Belum Login -->
                        <li class="nav-item">
                            <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalLogin">
                                <i class="bi bi-box-arrow-in-right"></i> Login Staff
                            </button>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- MODAL LOGIN ADMIN / OWNER -->
    <div class="modal fade" id="modalLogin" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-shield-lock-fill text-warning"></i> Login Staff / Owner</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="/login" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="owner@syatech.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="******" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- SECTION 1: HERO BANNER -->
    <div id="home" class="hero-section text-center mb-4">
        <div class="container">
            <h1 class="fw-bold display-5"><i class="bi bi-cpu-fill text-primary"></i> SyaTech Hardware SPK</h1>
            <p class="lead mb-0">Sistem Pendukung Keputusan Kelayakan Upgrade PC & Marketplace Komponen Kompatibel</p>
        </div>
    </div>

    <div class="container mb-5">
        
        <!-- Banner Auto-Detect -->
        <div class="card card-custom p-3 mb-4 bg-white border-start border-4 border-primary">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div>
                    <h5 class="fw-bold mb-1"><i class="bi bi-lightning-charge-fill text-warning"></i> Malas Isi Spesifikasi Manual?</h5>
                    <p class="text-muted small mb-0">Gunakan <strong>SyaTech Hardware Scanner</strong> untuk membaca komponen komputer Anda secara otomatis & presisi 100%.</p>
                    <small class="text-secondary" style="font-size: 11px;">*Jika muncul Windows SmartScreen: Klik <strong>More Info</strong> &rarr; <strong>Run Anyway</strong>.</small>
                </div>
                <a href="/download-app" class="btn btn-success btn-lg rounded-pill shadow-sm px-4 fw-bold">
                    <i class="bi bi-download me-1"></i> Unduh Auto-Detect App (.exe)
                </a>
            </div>
        </div>

        <!-- SECTION 2: SPK ENGINE (FORM & HASIL ANALISIS) -->
        <div id="spk" class="row g-4 mb-5">
            
            <!-- Form Input -->
            <div class="col-lg-6">
                <div class="card card-custom p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="fw-bold mb-0"><i class="bi bi-sliders text-primary"></i> Input Spesifikasi PC</h4>
                        <button type="button" class="btn btn-outline-info btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#modalPanduan">
                            <i class="bi bi-question-circle-fill"></i> Panduan Manual
                        </button>
                    </div>
                    <hr>

                    <form id="spkForm" action="/hitung" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold"><i class="bi bi-controller"></i> Target Workload (Kebutuhan)</label>
                            <select name="workload" class="form-select form-select-lg" required>
                                <option value="Gaming" {{ (isset($input) && $input['workload'] == 'Gaming') ? 'selected' : '' }}>Gaming AAA / Berat</option>
                                <option value="Multimedia" {{ (isset($input) && $input['workload'] == 'Multimedia') ? 'selected' : '' }}>Multimedia / Editing Video</option>
                                <option value="Office" {{ (isset($input) && $input['workload'] == 'Office') ? 'selected' : '' }}>Office / Komputasi Harian</option>
                            </select>
                        </div>

                        <!-- Autocomplete Motherboard -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold"><i class="bi bi-motherboard"></i> Model Motherboard / Laptop</label>
                            <input type="text" id="motherboard" name="motherboard" list="moboList" class="form-control" placeholder="Ketik atau pilih model motherboard..." value="{{ $input['motherboard'] ?? request('mobo') }}" required autocomplete="off">
                            
                            <datalist id="moboList">
                                @if(isset($moboList))
                                    @foreach($moboList as $m)
                                        <option value="{{ $m->name }}"></option>
                                    @endforeach
                                @endif
                                <option value="MSI Z170M MORTAR"></option>
                                <option value="MSI B460M PRO-VDH"></option>
                                <option value="MSI B460M MORTAR"></option>
                                <option value="MSI B460M PRO"></option>
                                <option value="MSI MAG B550 TOMAHAWK"></option>
                                <option value="MSI PRO B660M-A DDR4"></option>
                                <option value="ASUS PRIME B450M-K"></option>
                                <option value="ASUS TUF GAMING B550M-PLUS"></option>
                                <option value="ASUS ROG STRIX B560-F GAMING"></option>
                                <option value="GIGABYTE B450M DS3H"></option>
                                <option value="GIGABYTE B560M AORUS ELITE"></option>
                                <option value="GIGABYTE H610M S2H DDR4"></option>
                                <option value="ASRock B450M Steel Legend"></option>
                                <option value="ASRock B660M Phantom Gaming 4"></option>
                                <option value="Acer Aspire A3SP14-31PT"></option>
                            </datalist>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><i class="bi bi-memory"></i> RAM (GB)</label>
                                <input type="number" id="ram_gb" name="ram_gb" class="form-control" placeholder="Contoh: 8" value="{{ $input['ram_gb'] ?? request('ram') }}" required min="1">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><i class="bi bi-speedometer2"></i> CPU Clock (GHz)</label>
                                <input type="number" step="0.1" id="cpu_speed" name="cpu_speed" class="form-control" placeholder="Contoh: 1.8" value="{{ $input['cpu_speed'] ?? str_replace(',', '.', request('cpu')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><i class="bi bi-gpu-card"></i> VGA VRAM (GB / MB)</label>
                                <input type="number" step="0.01" id="vga_vram" name="vga_vram" class="form-control" placeholder="Isi 2 (GB) atau 128 (MB)" value="{{ $input['vga_vram'] ?? request('vga') }}" required min="0">
                                <span class="form-text text-muted" style="font-size:11px;">*Isi 128 jika 128 MB (otomatis dikonversi).</span>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold"><i class="bi bi-hdd-fill"></i> Tipe Storage Utama</label>
                                <select name="storage_type" class="form-select" required>
                                    <option value="1" {{ (isset($input) && $input['storage_type'] == 1) || request('storage') == 1 ? 'selected' : '' }}>HDD (Harddisk - Lambat)</option>
                                    <option value="2" {{ (isset($input) && $input['storage_type'] == 2) || request('storage') == 2 ? 'selected' : '' }}>SSD SATA (Sedang)</option>
                                    <option value="3" {{ (isset($input) && $input['storage_type'] == 3) || request('storage') == 3 ? 'selected' : '' }}>SSD NVMe M.2 (Cepat)</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 rounded-3 shadow-sm mt-2">
                            <i class="bi bi-calculator-fill me-1"></i> Analisis Kelayakan Upgrade
                        </button>
                    </form>
                </div>
            </div>

            <!-- Hasil Evaluasi SPK -->
            <div class="col-lg-6">
                <div class="card card-custom p-4 text-center h-100 d-flex flex-column justify-content-center">
                    @if(isset($hasil))
                        <h4 class="fw-bold mb-3"><i class="bi bi-bar-chart-line-fill text-primary"></i> Hasil Evaluasi SPK</h4>
                        
                        @php
                            $badgeClass = 'bg-score-success';
                            if($hasil['skor_kelayakan_akhir'] >= 71) { $badgeClass = 'bg-score-danger'; }
                            elseif($hasil['skor_kelayakan_akhir'] >= 41) { $badgeClass = 'bg-score-warning'; }

                            $vgaInputRaw = $input['vga_vram'];
                            $vgaLabel = ($vgaInputRaw > 32) 
                                ? '( ' . $vgaInputRaw . ' MB / ' . round($vgaInputRaw / 1024, 2) . ' GB )' 
                                : '( ' . $vgaInputRaw . ' GB )';
                        @endphp

                        <div class="score-circle {{ $badgeClass }} mb-3">
                            {{ $hasil['skor_kelayakan_akhir'] }}
                        </div>

                        <h5 class="fw-bold text-uppercase mb-1">{{ $hasil['kategori'] }}</h5>
                        <p class="text-secondary small mb-2"><i class="bi bi-laptop"></i> Perangkat: <strong>{{ $input['motherboard'] }}</strong></p>
                        <p class="text-muted small px-3 mb-4">{{ $hasil['rekomendasi'] }}</p>

                        <div class="text-start bg-light p-3 rounded-3">
                            <h6 class="fw-bold mb-2"><i class="bi bi-exclamation-triangle-fill text-warning"></i> Tingkat Lemah Komponen (Bottleneck):</h6>
                            <p class="text-muted mb-3" style="font-size: 11px;">*0% = Sangat Bagus / Ideal | 100% = Sangat Lemah / Bottleneck Utama</p>
                            
                            <!-- RAM -->
                            <div class="mb-2">
                                <div class="d-flex justify-content-between small fw-semibold">
                                    <span>RAM ({{ $input['ram_gb'] }} GB)</span>
                                    <span>{{ $hasil['fuzzifikasi_urgen']['RAM'] * 100 }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-danger" style="width: {{ $hasil['fuzzifikasi_urgen']['RAM'] * 100 }}%"></div>
                                </div>
                            </div>

                            <!-- CPU -->
                            <div class="mb-2">
                                <div class="d-flex justify-content-between small fw-semibold">
                                    <span>CPU Speed ({{ $input['cpu_speed'] }} GHz)</span>
                                    <span>{{ $hasil['fuzzifikasi_urgen']['CPU'] * 100 }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-warning" style="width: {{ $hasil['fuzzifikasi_urgen']['CPU'] * 100 }}%"></div>
                                </div>
                            </div>

                            <!-- VGA VRAM -->
                            <div class="mb-2">
                                <div class="d-flex justify-content-between small fw-semibold">
                                    <span>VGA VRAM {{ $vgaLabel }}</span>
                                    <span>{{ $hasil['fuzzifikasi_urgen']['VGA'] * 100 }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-danger" style="width: {{ $hasil['fuzzifikasi_urgen']['VGA'] * 100 }}%"></div>
                                </div>
                            </div>

                            <!-- Storage -->
                            <div>
                                <div class="d-flex justify-content-between small fw-semibold">
                                    <span>Tipe Storage Utama</span>
                                    <span>{{ $hasil['fuzzifikasi_urgen']['Storage'] * 100 }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-info" style="width: {{ $hasil['fuzzifikasi_urgen']['Storage'] * 100 }}%"></div>
                                </div>
                            </div>
                        </div>

                    @else
                        <div class="py-5 text-muted">
                            <i class="bi bi-laptop display-1 mb-3 text-secondary"></i>
                            <h5>Belum Ada Data Dianalisis</h5>
                            <p class="small">Isi form di sebelah kiri atau unduh <strong>Auto-Detect App (.exe)</strong> untuk hasil instan.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <hr class="my-5">

        <!-- SECTION 3: SHOP & MARKETPLACE KOMPATIBEL -->
        <div id="shop" class="mb-5">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div>
                    <h3 class="fw-bold mb-1"><i class="bi bi-shop text-success"></i> Rekomendasi Komponen & Marketplace</h3>
                    <p class="text-muted small mb-0">Komponen diurutkan berdasarkan <strong>Tingkat Bottleneck Terbesar</strong> PC Anda.</p>
                </div>

                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <!-- Filter Batas Harga / Budget -->
                    <form action="/#shop" method="GET" class="d-flex gap-2 align-items-center">
                        @if(request('mobo')) <input type="hidden" name="mobo" value="{{ request('mobo') }}"> @endif
                        <input type="number" name="max_price" class="form-control form-control-sm" placeholder="Batas Harga Maks (Rp)" value="{{ request('max_price') }}" style="width: 190px;">
                        <button type="submit" class="btn btn-outline-secondary btn-sm fw-bold">Filter</button>
                    </form>

                    <!-- Tombol + Jual Barang -->
                    <button type="button" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalJual">
                        <i class="bi bi-plus-circle-fill me-1"></i> + Jual Barang PC/Aksesori
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Grid Card Produk Rekomendasi Prioritas -->
            <div class="row g-4">
                @if(isset($products) && count($products) > 0)
                    @foreach($products as $p)
                        <div class="col-md-6 col-lg-3">
                            <div class="card card-custom product-card h-100 p-0 overflow-hidden d-flex flex-column justify-content-between">
                                <div>
                                    <!-- Foto Produk -->
                                    @if($p->image_path)
                                        <img src="{{ asset($p->image_path) }}" class="card-img-top product-img" alt="{{ $p->title }}">
                                    @else
                                        <div class="bg-secondary bg-opacity-10 text-center py-4 text-muted">
                                            <i class="bi bi-card-image display-4"></i>
                                        </div>
                                    @endif

                                    <div class="p-3">
                                        <!-- Badge Sumber & Kategori -->
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            @if($p->source_type == 'local')
                                                <span class="badge bg-warning text-dark"><i class="bi bi-shop"></i> Penjual Lokal</span>
                                            @else
                                                <span class="badge bg-success"><i class="bi bi-bag-check-fill"></i> Shopee / Tokped</span>
                                            @endif
                                            <span class="badge bg-secondary text-uppercase">{{ $p->category }}</span>
                                        </div>

                                        <h6 class="fw-bold mb-1">{{ $p->title }}</h6>
                                        <p class="text-primary fw-bold fs-5 mb-2">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                                        
                                        <p class="text-muted" style="font-size: 11px;" mb-2>
                                            <i class="bi bi-person-fill"></i> {{ $p->seller_name }}<br>
                                            <i class="bi bi-cpu"></i> Soket: {{ $p->socket_compat }} | RAM: {{ $p->ram_type_compat }}
                                        </p>

                                        @if($p->description)
                                            <div class="bg-light p-2 rounded text-muted mb-2" style="font-size: 11px;">
                                                {{ $p->description }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="p-3 pt-0">
                                    @if($p->source_type == 'local')
                                        @php
                                            $waText = rawurlencode("Halo " . $p->seller_name . ", saya tertarik dengan " . $p->title . " seharga Rp " . number_format($p->price, 0, ',', '.') . " yang dijual di SyaTech.");
                                            $waPhone = preg_replace('/^0/', '62', $p->seller_phone);
                                        @endphp
                                        <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}" target="_blank" class="btn btn-outline-success w-100 rounded-pill btn-sm fw-bold">
                                            <i class="bi bi-whatsapp"></i> Chat WA Penjual
                                        </a>
                                    @else
                                        <!-- Tombol Beli di Marketplace Menggunakan URL External -->
                                        <a href="{{ $p->external_link ?? '#' }}" target="_blank" class="btn btn-outline-primary w-100 rounded-pill btn-sm fw-bold">
                                            <i class="bi bi-cart-fill"></i> Beli di Marketplace
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center py-4 text-muted">
                        <i class="bi bi-box-seam display-4"></i>
                        <p class="mt-2">Belum ada komponen rekomendasi yang cocok dengan filter Anda.</p>
                    </div>
                @endif
            </div>
        </div>

        <hr class="my-5">

        <!-- SECTION 4: ABOUT ME / TENTANG APLIKASI -->
        <div id="about" class="mb-5">
            <div class="card card-custom p-4 bg-white">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center mb-3 mb-md-0">
                        <i class="bi bi-person-circle display-1 text-primary"></i>
                    </div>
                    <div class="col-md-9">
                        <h4 class="fw-bold mb-1">SyaTech Hardware SPK Engine</h4>
                        <p class="text-muted mb-2">Sistem Pendukung Keputusan Kelayakan Upgrade Hardware PC Menggunakan Metode Fuzzy Logic (Sugeno)</p>
                        <p class="small text-secondary mb-0">
                            Aplikasi ini dirancang untuk membantu pengguna menentukan kelayakan upgrade komponen komputer berdasarkan beban kerja (Gaming, Editing, Office) serta merekomendasikan hardware & aksesori yang pasti kompatibel dengan motherboard pengguna.
                        </p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- MODAL FORM + JUAL BARANG (MARKETPLACE C2C LENGKAP) -->
    <div class="modal fade" id="modalJual" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-plus-circle-fill me-1"></i> Unggah Barang Jualan (Hardware & Aksesori)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="/jual-produk" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Tipe / Jenis Barang -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Tipe / Jenis Barang Jualan</label>
                                <select name="category" class="form-select" required>
                                    <option value="motherboard">Motherboard / Mainboard</option>
                                    <option value="cpu">Processor / CPU</option>
                                    <option value="ram">RAM Memory</option>
                                    <option value="vga">VGA Card / Graphic Card</option>
                                    <option value="storage">Storage (SSD / Harddisk)</option>
                                    <option value="mouse">Mouse (Gaming / Office)</option>
                                    <option value="keyboard">Keyboard (Mechanical / Membrane)</option>
                                    <option value="monitor">Monitor / Layar Display</option>
                                    <option value="aksesoris">Aksesori PC/Laptop (Headset, Fan, WebCam, dll.)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nama / Judul Barang</label>
                                <input type="text" name="title" class="form-control" placeholder="Contoh: Motherboard MSI B460M PRO-VDH / Mouse Logitech G102" required>
                            </div>
                        </div>

                        <!-- Harga, Nama Penjual, No WA -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Harga Barang (Rp)</label>
                                <input type="number" name="price" class="form-control" placeholder="Contoh: 550000" required min="1000">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">Nama Penjual</label>
                                <input type="text" name="seller_name" class="form-control" placeholder="Nama Anda / Toko" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold">No. WhatsApp</label>
                                <input type="text" name="seller_phone" class="form-control" placeholder="Contoh: 08123456789" required>
                            </div>
                        </div>

                        <!-- Upload Foto Barang -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Foto Barang Jualan</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted" style="font-size: 11px;">*Format yang didukung: JPG, PNG, WEBP (Maksimal 2 MB).</small>
                        </div>

                        <!-- Deskripsi & Informasi Tambahan -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Deskripsi / Informasi Tambahan</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Jelaskan kondisi barang (misal: Second mulus pemakaian 3 bulan, kelengkapan dus ada, garansi aktif, dll.)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Unggah Jualan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL PANDUAN CEK MANUAL (3 ITEM LENGKAP) -->
    <div class="modal fade" id="modalPanduan" tabindex="-1" aria-labelledby="modalPanduanLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title fw-bold" id="modalPanduanLabel"><i class="bi bi-shield-check text-success"></i> Panduan Cek Hardware Manual</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="accordion" id="accordionTutorial">
                        
                        <!-- 1. Cara Cek Motherboard (CMD) -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMobo">
                                    <i class="bi bi-motherboard me-2 text-primary"></i> Cara Cek Model Motherboard / Laptop
                                </button>
                            </h2>
                            <div id="collapseMobo" class="accordion-collapse collapse show" data-bs-parent="#accordionTutorial">
                                <div class="accordion-body small">
                                    <ol class="mb-0">
                                        <li>Tekan <code>Win + R</code> &rarr; ketik <code>cmd</code> &rarr; Enter.</li>
                                        <li>Ketik: <code>wmic baseboard get product,manufacturer</code> lalu Enter.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- 2. Cara Cek Task Manager -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTask">
                                    <i class="bi bi-activity me-2 text-success"></i> Cek CPU, RAM, & Tipe Storage (Task Manager)
                                </button>
                            </h2>
                            <div id="collapseTask" class="accordion-collapse collapse" data-bs-parent="#accordionTutorial">
                                <div class="accordion-body small">
                                    <ol class="mb-0">
                                        <li>Tekan kombinasi tombol <code>Ctrl + Shift + Esc</code>.</li>
                                        <li>Klik tab <strong>Performance</strong> di sebelah kiri.</li>
                                        <li><strong>Storage:</strong> Klik menu <strong>Disk (C:)</strong> &rarr; Lihat tipe di kanan bawah/atas (tertulis <strong>HDD</strong> atau <strong>SSD</strong>).</li>
                                        <li><strong>RAM & CPU:</strong> Klik menu <strong>Memory</strong> (Kapasitas GB) dan <strong>CPU</strong> (Speed GHz).</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Cara Cek dxdiag -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDxdiag">
                                    <i class="bi bi-windows me-2 text-info"></i> Cek Processor & VRAM VGA (dxdiag)
                                </button>
                            </h2>
                            <div id="collapseDxdiag" class="accordion-collapse collapse" data-bs-parent="#accordionTutorial">
                                <div class="accordion-body small">
                                    <ol class="mb-0">
                                        <li>Tekan <code>Win + R</code> &rarr; ketik <code>dxdiag</code> &rarr; Enter.</li>
                                        <li>Di tab <strong>System</strong>: Lihat <em>Processor (GHz)</em>, <em>Memory (RAM)</em>, dan <em>System Model</em>.</li>
                                        <li>Di tab <strong>Display</strong>: Lihat <em>Display Memory (VRAM)</em>.</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Tutup Panduan</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('autodeteck')) {
                document.getElementById('spkForm').submit();
            }
        });
    </script>
</body>
</html>