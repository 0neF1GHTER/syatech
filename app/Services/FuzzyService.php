<?php

namespace App\Services;

class FuzzyService
{
    /**
     * 1. Fungsi Keanggotaan Kurva Trapesium Bahu Kanan (μ[x])
     */
    public function trapezoid(float $x, float $a, float $b, float $c, float $d): float
    {
        if ($x <= $a) {
            return 0.0;
        }

        if ($x > $a && $x < $b) {
            return round(($x - $a) / ($b - $a), 4);
        }

        if ($x >= $b) {
            return 1.0;
        }

        return 0.0;
    }

    /**
     * 2. Parameter Kurva Trapesium Ideal Berdasarkan Workload
     * (RAM Ideal Gaming & Multimedia diset ke 16 GB)
     */
    private function getWorkloadParams(string $workload): array
    {
        switch (strtolower($workload)) {
            case 'gaming':
            case 'gaming aaa':
                return [
                    'ram'     => ['a' => 4, 'b' => 16, 'c' => 32, 'd' => 64],    // Min 4GB, Ideal 16GB
                    'cpu'     => ['a' => 2.0, 'b' => 3.5, 'c' => 5.0, 'd' => 6.0],  // GHz
                    'vga'     => ['a' => 2, 'b' => 6, 'c' => 12, 'd' => 24],       // GB VRAM
                    'storage' => ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4],       // 1=HDD, 2=SATA, 3=NVMe
                ];
            case 'multimedia':
            case 'editing':
                return [
                    'ram'     => ['a' => 4, 'b' => 16, 'c' => 32, 'd' => 64],    // Min 4GB, Ideal 16GB
                    'cpu'     => ['a' => 1.8, 'b' => 3.0, 'c' => 4.5, 'd' => 6.0],
                    'vga'     => ['a' => 2, 'b' => 4, 'c' => 8, 'd' => 16],
                    'storage' => ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4],
                ];
            case 'office':
            default:
                return [
                    'ram'     => ['a' => 2, 'b' => 8, 'c' => 16, 'd' => 32],     // Ideal Office 8GB
                    'cpu'     => ['a' => 1.2, 'b' => 2.0, 'c' => 3.5, 'd' => 5.0],
                    'vga'     => ['a' => 0, 'b' => 1, 'c' => 2, 'd' => 4],
                    'storage' => ['a' => 1, 'b' => 2, 'c' => 3, 'd' => 4],
                ];
        }
    }

    /**
     * 3. Pemrosesan Utama SPK Fuzzy Logic
     */
    public function hitungKelayakanUpgrade(array $spec, string $workload): array
    {
        $vgaVramGb = $spec['vga_vram'];

        if ($vgaVramGb > 32) {
            $vgaVramGb = round($vgaVramGb / 1024, 3);
        }

        $params = $this->getWorkloadParams($workload);

        $muRamIdeal     = $this->trapezoid($spec['ram_gb'], $params['ram']['a'], $params['ram']['b'], $params['ram']['c'], $params['ram']['d']);
        $muCpuIdeal     = $this->trapezoid($spec['cpu_speed'], $params['cpu']['a'], $params['cpu']['b'], $params['cpu']['c'], $params['cpu']['d']);
        $muVgaIdeal     = $this->trapezoid($vgaVramGb, $params['vga']['a'], $params['vga']['b'], $params['vga']['c'], $params['vga']['d']);
        $muStorageIdeal = $this->trapezoid($spec['storage_type'], $params['storage']['a'], $params['storage']['b'], $params['storage']['c'], $params['storage']['d']);

        $muRamUrgen     = round(1.0 - $muRamIdeal, 4);
        $muCpuUrgen     = round(1.0 - $muCpuIdeal, 4);
        $muVgaUrgen     = round(1.0 - $muVgaIdeal, 4);
        $muStorageUrgen = round(1.0 - $muStorageIdeal, 4);

        $rules = [];
        $rules[] = ['alpha' => min($muRamUrgen, $muVgaUrgen, $muCpuUrgen), 'z' => 85];
        $rules[] = ['alpha' => min($muRamUrgen, max($muCpuUrgen, $muVgaUrgen)), 'z' => 85];
        $rules[] = ['alpha' => min($muStorageUrgen, $muRamUrgen), 'z' => 55];
        $rules[] = ['alpha' => max($muRamUrgen, $muCpuUrgen, $muVgaUrgen, $muStorageUrgen), 'z' => 55];
        $rules[] = ['alpha' => min($muRamIdeal, $muCpuIdeal, $muVgaIdeal, $muStorageIdeal), 'z' => 20];

        $numerator = 0.0;
        $denominator = 0.0;

        foreach ($rules as $rule) {
            $numerator += ($rule['alpha'] * $rule['z']);
            $denominator += $rule['alpha'];
        }

        $skorAkhir = $denominator > 0 ? round($numerator / $denominator, 2) : 0.0;

        if ($skorAkhir >= 71) {
            $kategori = 'Sangat Layak Upgrade';
            $rekomendasiTindakan = 'Sistem sangat merekomendasikan melakukan upgrade pada komponen bottleneck agar performa maksimal.';
        } elseif ($skorAkhir >= 41) {
            $kategori = 'Dipertimbangkan';
            $rekomendasiTindakan = 'Performa PC masih bisa berjalan, namun melakukan upgrade pada komponen tertentu akan meningkatkan performa.';
        } else {
            $kategori = 'Tidak Perlu Upgrade';
            $rekomendasiTindakan = 'Spesifikasi PC saat ini masih sangat ideal dan mencukupi untuk beban kerja tersebut.';
        }

        return [
            'workload' => $workload,
            'input_spesifikasi' => $spec,
            'fuzzifikasi_ideal' => [
                'RAM' => $muRamIdeal,
                'CPU' => $muCpuIdeal,
                'VGA' => $muVgaIdeal,
                'Storage' => $muStorageIdeal,
            ],
            'fuzzifikasi_urgen' => [
                'RAM' => $muRamUrgen,
                'CPU' => $muCpuUrgen,
                'VGA' => $muVgaUrgen,
                'Storage' => $muStorageUrgen,
            ],
            'skor_kelayakan_akhir' => $skorAkhir,
            'kategori' => $kategori,
            'rekomendasi' => $rekomendasiTindakan
        ];
    }
}