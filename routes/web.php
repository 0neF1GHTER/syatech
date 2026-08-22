<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FuzzyService;
use App\Models\Motherboard;
use App\Models\Product;
use App\Models\User;
use App\Models\ActivityLog;

// 1. Halaman Utama UI Publik Landing Page
Route::get('/', function (Request $request) {
    $moboList = Motherboard::select('name')->distinct()->get();
    
    // Hanya tampilkan produk yang berstatus DISETUJUI (approved) di halaman publik
    $query = Product::where('status', 'approved');

    // Saring Kompatibilitas Motherboard
    if ($request->has('mobo') && !empty($request->get('mobo'))) {
        $moboName = urldecode($request->get('mobo'));
        $mobo = Motherboard::where('name', 'LIKE', "%{$moboName}%")->first();
        if ($mobo) {
            $query->where(function($q) use ($mobo) {
                $q->where('socket_compat', $mobo->socket)
                  ->orWhere('socket_compat', 'Universal');
            })->where(function($q) use ($mobo) {
                $q->where('ram_type_compat', $mobo->ram_type)
                  ->orWhere('ram_type_compat', 'Universal');
            });
        }
    }

    // Saring Batas Harga (Budget Filter)
    if ($request->has('max_price') && !empty($request->get('max_price'))) {
        $query->where('price', '<=', (int) $request->get('max_price'));
    }

    $products = $query->latest()->get();

    // Jika Request Datang dari Auto-Detect GET Parameters
    if ($request->has('ram') && $request->has('cpu')) {
        $fuzzy = new FuzzyService();
        $inputUser = [
            'motherboard'  => $request->input('mobo', 'Generic Motherboard'),
            'ram_gb'       => (float) $request->input('ram'),
            'cpu_speed'    => (float) str_replace(',', '.', $request->input('cpu')),
            'vga_vram'     => (float) $request->input('vga'),
            'storage_type' => (int) $request->input('storage', 3),
            'workload'     => $request->input('workload', 'Gaming'),
        ];

        $hasil = $fuzzy->hitungKelayakanUpgrade($inputUser, $inputUser['workload']);

        // Urutkan Produk Berdasarkan Persentase Bottleneck
        $urgencyMap = [
            'ram'     => $hasil['fuzzifikasi_urgen']['RAM'] ?? 0,
            'cpu'     => $hasil['fuzzifikasi_urgen']['CPU'] ?? 0,
            'vga'     => $hasil['fuzzifikasi_urgen']['VGA'] ?? 0,
            'storage' => $hasil['fuzzifikasi_urgen']['Storage'] ?? 0,
        ];

        $products = $products->sortByDesc(function ($product) use ($urgencyMap) {
            $cat = strtolower($product->category);
            return $urgencyMap[$cat] ?? -1;
        })->values();

        return view('index', [
            'input'    => $inputUser,
            'hasil'    => $hasil,
            'moboList' => $moboList,
            'products' => $products
        ]);
    }

    return view('index', [
        'moboList' => $moboList,
        'products' => $products
    ]);
});

// 2. Route Penanganan Form Submit SPK
Route::post('/hitung', function (Request $request) {
    $fuzzy = new FuzzyService();

    $inputUser = [
        'motherboard'  => $request->input('motherboard', 'Generic Motherboard'),
        'ram_gb'       => (float) $request->input('ram_gb'),
        'cpu_speed'    => (float) $request->input('cpu_speed'),
        'vga_vram'     => (float) $request->input('vga_vram'),
        'storage_type' => (int) $request->input('storage_type'),
        'workload'     => $request->input('workload'),
    ];

    $hasil = $fuzzy->hitungKelayakanUpgrade($inputUser, $inputUser['workload']);

    $mobo = Motherboard::where('name', 'LIKE', "%{$inputUser['motherboard']}%")->first();
    $query = Product::where('status', 'approved');

    if ($mobo) {
        $query->where(function($q) use ($mobo) {
            $q->where('socket_compat', $mobo->socket)
              ->orWhere('socket_compat', 'Universal');
        })->where(function($q) use ($mobo) {
            $q->where('ram_type_compat', $mobo->ram_type)
              ->orWhere('ram_type_compat', 'Universal');
        });
    }

    if ($request->has('max_price') && !empty($request->get('max_price'))) {
        $query->where('price', '<=', (int) $request->get('max_price'));
    }

    $products = $query->latest()->get();

    // Urutkan Produk Berdasarkan Persentase Bottleneck
    $urgencyMap = [
        'ram'     => $hasil['fuzzifikasi_urgen']['RAM'] ?? 0,
        'cpu'     => $hasil['fuzzifikasi_urgen']['CPU'] ?? 0,
        'vga'     => $hasil['fuzzifikasi_urgen']['VGA'] ?? 0,
        'storage' => $hasil['fuzzifikasi_urgen']['Storage'] ?? 0,
    ];

    $products = $products->sortByDesc(function ($product) use ($urgencyMap) {
        $cat = strtolower($product->category);
        return $urgencyMap[$cat] ?? -1;
    })->values();

    $moboList = Motherboard::select('name')->distinct()->get();

    return view('index', [
        'input'    => $inputUser,
        'hasil'    => $hasil,
        'products' => $products,
        'moboList' => $moboList
    ]);
});

// 3. Route Unggah Barang Jualan Pengguna (Status Otomatis Pending)
Route::post('/jual-produk', function (Request $request) {
    $request->validate([
        'title'        => 'required',
        'category'     => 'required',
        'price'        => 'required|numeric',
        'seller_name'  => 'required',
        'seller_phone' => 'required',
        'image'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    $imagePath = null;
    if ($request->hasFile('image')) {
        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads'), $filename);
        $imagePath = 'uploads/' . $filename;
    }

    Product::create([
        'title'           => $request->title,
        'category'        => strtolower($request->category),
        'socket_compat'   => $request->socket_compat ?? 'Universal',
        'ram_type_compat' => $request->ram_type_compat ?? 'Universal',
        'price'           => $request->price,
        'seller_name'     => $request->seller_name,
        'seller_phone'    => $request->seller_phone,
        'description'     => $request->description,
        'source_type'     => 'local',
        'status'          => 'pending', // Menunggu persetujuan Admin/Owner
        'image_path'      => $imagePath,
    ]);

    return redirect('/#shop')->with('success', 'Barang jualan Anda berhasil diunggah! Barang akan muncul di toko setelah disetujui Admin.');
});

// 4. AUTENTIKASI: LOGIN & LOGOUT
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $user = Auth::user();
        if (in_array($user->role, ['owner', 'admin'])) {
            return redirect('/admin')->with('success', 'Selamat datang ' . $user->name);
        }
        return redirect('/')->with('success', 'Berhasil login!');
    }

    return back()->withErrors(['email' => 'Email atau password salah!']);
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
});

// 5. DASHBOARD ADMIN & OWNER MANAGEMENT
Route::get('/admin', function () {
    if (!Auth::check() || !in_array(Auth::user()->role, ['owner', 'admin'])) {
        abort(403, 'Akses Ditolak! Halaman ini khusus Admin dan Owner.');
    }

    $pendingProducts  = Product::where('status', 'pending')->latest()->get();
    $approvedProducts = Product::where('status', 'approved')->latest()->get();
    $allUsers         = User::latest()->get();
    $logs             = ActivityLog::latest()->take(20)->get();

    return view('admin', [
        'pendingProducts'  => $pendingProducts,
        'approvedProducts' => $approvedProducts,
        'allUsers'         => $allUsers,
        'logs'             => $logs
    ]);
});

// 6. ACTION: SETUJUI / TOLAK BARANG (APPROVE/REJECT)
Route::post('/admin/product/{id}/action', function (Request $request, $id) {
    if (!Auth::check() || !in_array(Auth::user()->role, ['owner', 'admin'])) {
        abort(403);
    }

    $product = Product::findOrFail($id);
    $action  = $request->input('action'); // approve / reject
    $user    = Auth::user();

    if ($action == 'approve') {
        $product->status = 'approved';
        $product->approved_by = $user->name . ' (' . ucfirst($user->role) . ')';
        $product->save();

        ActivityLog::create([
            'user_name'   => $user->name,
            'action'      => 'Menyetujui Produk',
            'description' => "Produk '{$product->title}' telah disetujui untuk tampil di toko.",
        ]);
        return back()->with('success', 'Produk berhasil disetujui!');
    } elseif ($action == 'reject') {
        $product->status = 'rejected';
        $product->approved_by = $user->name . ' (' . ucfirst($user->role) . ')';
        $product->save();

        ActivityLog::create([
            'user_name'   => $user->name,
            'action'      => 'Menolak Produk',
            'description' => "Produk '{$product->title}' ditolak.",
        ]);
        return back()->with('success', 'Produk berhasil ditolak.');
    }

    return back();
});

// 7. ACTION: TANDAI BARANG TERJUAL (SOLD OUT)
Route::post('/admin/product/{id}/sold', function ($id) {
    if (!Auth::check() || !in_array(Auth::user()->role, ['owner', 'admin'])) {
        abort(403);
    }

    $product = Product::findOrFail($id);
    $product->status = 'sold';
    $product->save();

    ActivityLog::create([
        'user_name'   => Auth::user()->name,
        'action'      => 'Tandai Terjual',
        'description' => "Produk '{$product->title}' telah ditandai sebagai 'Sudah Terjual' dan disembunyikan dari toko publik.",
    ]);

    return back()->with('success', 'Status barang berhasil diubah menjadi Terjual!');
});

// 8. ACTION: HAPUS BARANG
Route::post('/admin/product/{id}/delete', function ($id) {
    if (!Auth::check() || !in_array(Auth::user()->role, ['owner', 'admin'])) {
        abort(403);
    }

    $product = Product::findOrFail($id);
    $title = $product->title;
    $product->delete();

    ActivityLog::create([
        'user_name'   => Auth::user()->name,
        'action'      => 'Menghapus Produk',
        'description' => "Produk '{$title}' telah dihapus dari sistem.",
    ]);

    return back()->with('success', 'Barang berhasil dihapus dari sistem!');
});

// 9. ACTION KHUSUS OWNER: UBAH ROLE USER
Route::post('/admin/user/{id}/role', function (Request $request, $id) {
    if (!Auth::check() || Auth::user()->role !== 'owner') {
        abort(403, 'Hanya Owner yang berhak mengubah role user!');
    }

    $targetUser = User::findOrFail($id);
    $newRole    = $request->input('role');
    $currentUser = Auth::user();

    $oldRole = $targetUser->role;
    $targetUser->role = $newRole;
    $targetUser->save();

    ActivityLog::create([
        'user_name'   => $currentUser->name,
        'action'      => 'Mengubah Role User',
        'description' => "Role '{$targetUser->name}' diubah dari '{$oldRole}' menjadi '{$newRole}'.",
    ]);

    return back()->with('success', 'Role user berhasil diperbarui!');
});

// 10. ACTION: IMPORT DATA E-COMMERCE VIA FILE CSV
Route::post('/admin/import-csv', function (Request $request) {
    if (!Auth::check() || !in_array(Auth::user()->role, ['owner', 'admin'])) {
        abort(403);
    }

    $request->validate([
        'csv_file' => 'required|file|mimes:csv,txt|max:2048'
    ]);

    $file = $request->file('csv_file');
    $handle = fopen($file->getRealPath(), "r");
    $header = fgetcsv($handle, 1000, ",");

    $count = 0;
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if (count($data) >= 8) {
            Product::create([
                'title'           => $data[0],
                'category'        => strtolower($data[1]),
                'socket_compat'   => $data[2] ?? 'Universal',
                'ram_type_compat' => $data[3] ?? 'Universal',
                'price'           => (int) $data[4],
                'seller_name'     => $data[5] ?? 'Shopee/Tokopedia Store',
                'seller_phone'    => $data[6] ?? '081234567890',
                'source_type'     => strtolower($data[7]) == 'ecommerce' ? 'ecommerce' : 'local',
                'description'     => $data[8] ?? null,
                'external_link'   => $data[9] ?? null,
                'status'          => 'approved',
                'approved_by'     => Auth::user()->name . ' (Import CSV)',
            ]);
            $count++;
        }
    }
    fclose($handle);

    ActivityLog::create([
        'user_name'   => Auth::user()->name,
        'action'      => 'Import CSV E-Commerce',
        'description' => "Berhasil mengimpor {$count} produk E-Commerce dari file CSV.",
    ]);

    return back()->with('success', "Berhasil mengimpor {$count} data produk dari file CSV!");
});

// 11. Route Penanganan Download Application Auto-Detect (.exe)
Route::get('/download-app', function () {
    $filePath = public_path('downloads/SyaTech-Detector.exe');

    if (!file_exists($filePath)) {
        abort(404, 'File SyaTech-Detector.exe tidak ditemukan di folder public/downloads/');
    }

    return response()->download($filePath, 'SyaTech-Detector.exe');
});