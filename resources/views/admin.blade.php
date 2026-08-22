<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Management - SyaTech</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .card-custom { border: none; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
    </style>
</head>
<body>

    <!-- NAVBAR ADMIN -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">
                <i class="bi bi-cpu-fill text-primary"></i> SyaTech Admin Panel
            </a>
            <div class="d-flex align-items-center text-white gap-3">
                <span><i class="bi bi-person-circle"></i> {{ Auth::user()->name }} (<strong class="text-warning">{{ ucfirst(Auth::user()->role) }}</strong>)</span>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm rounded-pill"><i class="bi bi-box-arrow-right"></i> Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container my-4">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0"><i class="bi bi-speedometer2 text-primary"></i> Management Dashboard</h3>
            <a href="/" class="btn btn-outline-primary rounded-pill fw-bold btn-sm"><i class="bi bi-house-door-fill"></i> Ke Halaman Utama</a>
        </div>

        <!-- TABS NAVIGASI MANAGEMENT -->
        <ul class="nav nav-pills mb-4 gap-2" id="adminTabs" role="tablist">
            <li class="nav-item">
                <button class="nav-link active fw-bold rounded-pill" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending">
                    <i class="bi bi-hourglass-split"></i> Persetujuan Barang (<span class="text-warning">{{ count($pendingProducts) }}</span>)
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold rounded-pill" id="active-tab" data-bs-toggle="tab" data-bs-target="#active">
                    <i class="bi bi-shop text-success"></i> Barang Aktif Tayang (<span class="text-success">{{ count($approvedProducts) }}</span>)
                </button>
            </li>
            @if(Auth::user()->role === 'owner')
                <li class="nav-item">
                    <button class="nav-link fw-bold rounded-pill" id="users-tab" data-bs-toggle="tab" data-bs-target="#users">
                        <i class="bi bi-people-fill"></i> Kelola Role User
                    </button>
                </li>
            @endif
            <li class="nav-item">
                <button class="nav-link fw-bold rounded-pill" id="import-tab" data-bs-toggle="tab" data-bs-target="#import">
                    <i class="bi bi-file-earmark-spreadsheet-fill text-success"></i> Import CSV E-Commerce
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link fw-bold rounded-pill" id="logs-tab" data-bs-toggle="tab" data-bs-target="#logs">
                    <i class="bi bi-clock-history"></i> Histori Aktivitas (Audit Log)
                </button>
            </li>
        </ul>

        <div class="tab-content" id="adminTabsContent">
            
            <!-- TAB 1: PERSETUJUAN BARANG (PENDING PRODUCTS) -->
            <div class="tab-pane fade show active" id="pending">
                <div class="card card-custom p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-shield-check text-primary"></i> Daftar Barang Menunggu Persetujuan</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Barang</th>
                                    <th>Kategori</th>
                                    <th>Harga</th>
                                    <th>Penjual & WA</th>
                                    <th>Deskripsi</th>
                                    <th>Aksi Persetujuan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingProducts as $index => $p)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $p->title }}</strong><br>
                                            <small class="text-muted">Soket: {{ $p->socket_compat }} | RAM: {{ $p->ram_type_compat }}</small>
                                        </td>
                                        <td><span class="badge bg-secondary text-uppercase">{{ $p->category }}</span></td>
                                        <td class="fw-bold text-primary">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                                        <td>{{ $p->seller_name }}<br><small class="text-muted">{{ $p->seller_phone }}</small></td>
                                        <td><small class="text-muted">{{ $p->description ?? '-' }}</small></td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <form action="/admin/product/{{ $p->id }}/action" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" class="btn btn-success btn-sm rounded-pill"><i class="bi bi-check-lg"></i> Setujui</button>
                                                </form>
                                                <form action="/admin/product/{{ $p->id }}/action" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill"><i class="bi bi-x-lg"></i> Tolak</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">Tidak ada barang yang menunggu persetujuan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 2: BARANG AKTIF TAYANG (KONTROL STOK & HAPUS BARANG) -->
            <div class="tab-pane fade" id="active">
                <div class="card card-custom p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-shop text-success"></i> Kelola Barang Tayang di Toko Utama</h5>
                    <p class="text-muted small">Di sini Admin/Owner dapat memantau barang yang sedang tampil di publik, menandai barang yang sudah laku/terjual, atau menghapus link mati.</p>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Barang</th>
                                    <th>Harga</th>
                                    <th>Sumber / Penjual</th>
                                    <th>Disetujui Oleh</th>
                                    <th>Aksi Kontrol</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($approvedProducts as $index => $p)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $p->title }}</strong><br>
                                            <span class="badge bg-secondary text-uppercase">{{ $p->category }}</span>
                                        </td>
                                        <td class="fw-bold text-primary">Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                                        <td>
                                            @if($p->source_type == 'local')
                                                <span class="badge bg-warning text-dark">Lokal</span> {{ $p->seller_name }} ({{ $p->seller_phone }})
                                            @else
                                                <span class="badge bg-success">E-Commerce</span> {{ $p->seller_name }}
                                            @endif
                                        </td>
                                        <td><small class="text-muted">{{ $p->approved_by ?? 'Admin' }}</small></td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <!-- Tandai Terjual -->
                                                <form action="/admin/product/{{ $p->id }}/sold" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-warning btn-sm rounded-pill fw-bold" onclick="return confirm('Tandai barang ini sebagai Sudah Terjual?')">
                                                        <i class="bi bi-tag-fill"></i> Terjual
                                                    </button>
                                                </form>
                                                <!-- Hapus Barang -->
                                                <form action="/admin/product/{{ $p->id }}/delete" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill fw-bold" onclick="return confirm('Yakin ingin menghapus barang ini secara permanen?')">
                                                        <i class="bi bi-trash-fill"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">Belum ada barang aktif yang disetujui.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: KELOLA ROLE USER (KHUSUS OWNER) -->
            @if(Auth::user()->role === 'owner')
                <div class="tab-pane fade" id="users">
                    <div class="card card-custom p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-person-gear text-primary"></i> Manajemen Pengguna & Hak Akses (Owner Control)</h5>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Role saat ini</th>
                                        <th>Ubah Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allUsers as $u)
                                        <tr>
                                            <td class="fw-bold">{{ $u->name }}</td>
                                            <td>{{ $u->email }}</td>
                                            <td>
                                                <span class="badge {{ $u->role == 'owner' ? 'bg-danger' : ($u->role == 'admin' ? 'bg-warning text-dark' : 'bg-info text-dark') }}">
                                                    {{ strtoupper($u->role) }}
                                                </span>
                                            </td>
                                            <td>
                                                <form action="/admin/user/{{ $u->id }}/role" method="POST" class="d-flex gap-2">
                                                    @csrf
                                                    <select name="role" class="form-select form-select-sm" style="width: 130px;">
                                                        <option value="user" {{ $u->role == 'user' ? 'selected' : '' }}>User</option>
                                                        <option value="admin" {{ $u->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                        <option value="owner" {{ $u->role == 'owner' ? 'selected' : '' }}>Owner</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-3">Simpan</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- TAB 4: IMPORT CSV E-COMMERCE -->
            <div class="tab-pane fade" id="import">
                <div class="card card-custom p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-file-earmark-spreadsheet-fill text-success"></i> Import Data Produk E-Commerce (CSV)</h5>
                    <p class="text-muted small">Unggah file CSV berisi data barang dari Shopee/Tokopedia untuk dimasukkan sekaligus ke dalam database.</p>
                    
                    <form action="/admin/import-csv" method="POST" enctype="multipart/form-data" class="col-md-6">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pilih File CSV Data Produk</label>
                            <input type="file" name="csv_file" class="form-control" accept=".csv" required>
                            <small class="text-muted" style="font-size:11px;">*Format kolom CSV: <code>Title, Category, Socket, RAMType, Price, SellerName, SellerPhone, SourceType, Description, ExternalLink</code></small>
                        </div>
                        <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold"><i class="bi bi-upload"></i> Unggah Data CSV</button>
                    </form>
                </div>
            </div>

            <!-- TAB 5: AUDIT LOG (HISTORI AKTIVITAS) -->
            <div class="tab-pane fade" id="logs">
                <div class="card card-custom p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-clock-history text-primary"></i> Catatan Transaksi & Audit Log</h5>
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Waktu</th>
                                    <th>Aktor (Admin/Owner)</th>
                                    <th>Aksi</th>
                                    <th>Rincian Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $l)
                                    <tr>
                                        <td><small class="text-muted">{{ $l->created_at->format('d M Y, H:i') }} WITA</small></td>
                                        <td class="fw-bold">{{ $l->user_name }}</td>
                                        <td><span class="badge bg-primary">{{ $l->action }}</span></td>
                                        <td>{{ $l->description }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-3 text-muted">Belum ada catatan aktivitas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>