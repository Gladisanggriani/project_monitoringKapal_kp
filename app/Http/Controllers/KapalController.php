<?php

namespace App\Http\Controllers;

use App\Models\Kapal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KapalController extends Controller
{
    /**
     * Tampilan Utama Dashboard
     */
    public function index()
    {
        // Kapal aktif (Menunggu Sandar & Sedang Muat)
        $kapals = Kapal::where('status', '!=', 'Selesai')->get();

        // Data untuk Modal "Kapal Selesai" (5 terbaru)
        $kapalsSelesai = Kapal::where('status', 'Selesai')
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();

        // Hitung statistik
        $totalKapal     = Kapal::count();
        $menungguSandar = Kapal::where('status', 'Menunggu Sandar')->count();
        $sedangMuat     = Kapal::where('status', 'Sedang Muat')->count();
        $selesai        = Kapal::where('status', 'Selesai')->count();

        return view('dashboard', compact('kapals', 'kapalsSelesai', 'totalKapal', 'menungguSandar', 'sedangMuat', 'selesai'));
    }

    /**
     * Halaman Laporan Kapal Selesai (Arsip)
     */
    public function laporan()
    {
        $laporan = Kapal::where('status', 'Selesai')
            ->orderBy('tanggal_selesai', 'desc')
            ->get();

        return view('laporan', compact('laporan'));
    }

    public function create()
    {
        return view('kapal.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateRequest($request);

        // Otomatis set is_archived jika status dipilih Selesai saat input baru
        $data['is_archived'] = ($request->status == 'Selesai') ? 1 : 0;

        Kapal::create($data);

        return redirect()->route('dashboard')->with('sukses', 'Data kapal berhasil ditambahkan!');
    }

    public function edit(Kapal $kapal)
    {
        return view('kapal.edit', compact('kapal'));
    }

    public function update(Request $request, Kapal $kapal)
    {
        $data = $this->validateRequest($request);

        // Jika status diubah ke Selesai, pindahkan ke arsip
        $data['is_archived'] = ($request->status == 'Selesai') ? 1 : 0;

        $kapal->update($data);

        $pesan = ($request->status == 'Selesai')
            ? "Kapal {$kapal->nama_kapal} telah Selesai dan dipindahkan ke laporan."
            : "Data kapal {$kapal->nama_kapal} berhasil diperbarui.";

        return redirect()->route('dashboard')->with('sukses', $pesan);
    }

    /**
     * Aksi cepat memindahkan ke arsip
     */
    public function archive($id)
    {
        $kapal = Kapal::findOrFail($id);
        $kapal->update([
            'status' => 'Selesai',
            'is_archived' => 1,
            'tanggal_selesai' => now()
        ]);

        return redirect()->route('dashboard')->with('sukses', "Kapal {$kapal->nama_kapal} berhasil diarsipkan.");
    }

    public function destroy(Kapal $kapal)
    {
        $kapal->delete();
        return redirect()->route('dashboard')->with('sukses', 'Data kapal berhasil dihapus!');
    }

    /**
     * Tampilan Pratinjau sebelum Export (Filter Web)
     */
    public function previewExport(Request $request)
    {
        $query = Kapal::query();

        // Filter Berdasarkan Hari
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_input', $request->tanggal);
        }

        // Filter Berdasarkan Bulan (Input format: YYYY-MM)
        if ($request->filled('bulan')) {
            $dateParts = explode('-', $request->bulan);
            $query->whereYear('tanggal_input', $dateParts[0])
                ->whereMonth('tanggal_input', $dateParts[1]);
        }

        $kapals = $query->orderBy('tanggal_input', 'desc')->get();

        return view('kapal.preview-export', compact('kapals'));
    }

    /**
     * Proses Download Excel (.xls) Native HTML
     */
    public function export(Request $request)
    {
        $query = Kapal::query();

        // Terapkan Filter yang SAMA dengan Preview
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_input', $request->tanggal);
        }

        if ($request->filled('bulan')) {
            $dateParts = explode('-', $request->bulan);
            $query->whereYear('tanggal_input', $dateParts[0])
                ->whereMonth('tanggal_input', $dateParts[1]);
        }

        $kapals = $query->orderBy('tanggal_input', 'asc')->get();
        $fileName = "Laporan_Monitoring_Kapal_" . date('Ymd_His') . ".xls";

        // Konfigurasi Header untuk Browser agar mendownload sebagai Excel
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header("Cache-Control: max-age=0");

        // Mulai Output HTML Excel
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
            body { font-family: Arial, sans-serif; }
            .kop-header { font-size: 14pt; font-weight: bold; text-align: center; color: #ffffff; background-color: #992222; height: 35px; }
            .sub-kop { font-size: 12pt; font-weight: bold; text-align: center; height: 25px; }
            .sub-tgl { font-size: 10pt; font-style: italic; text-align: center; height: 20px; }
            .section-title { font-weight: bold; background-color: #fce4d6; color: #c00000; border: 0.5pt solid #000000; height: 25px; padding-left: 5px; }
            table { border-collapse: collapse; }
            th { background-color: #f2f2f2; font-weight: bold; text-align: center; border: 0.5pt solid #000000; height: 25px; }
            td { border: 0.5pt solid #000000; height: 22px; padding: 2px 5px; }
            .text-center { text-align: center; }
            .str { mso-number-format:"\@"; } /* Supaya format tanggal/teks tidak berantakan di Excel */
        </style>
        </head>
        <body>
        <table>
            <tr><td colspan="10" class="kop-header">LAPORAN DATA MONITORING KAPAL</td></tr>
            <tr><td colspan="10" class="sub-kop">PT Semen Padang Unit Pabrik Dumai</td></tr>
            <tr><td colspan="10" class="sub-tgl">Tanggal Cetak : ' . date('d F Y H:i') . ' WIB</td></tr>
            <tr><td colspan="10" style="border:none; height:15px;"></td></tr>
            <tr><td colspan="10" class="section-title">LOG AKTIVITAS & MONITORING KAPAL</td></tr>
            <tr>
                <th>No</th>
                <th>Tanggal Input</th>
                <th>Nama Kapal</th>
                <th>Qty (Mton)</th>
                <th>Agen</th>
                <th>Jenis Muatan</th>
                <th>Tanggal Sandar</th>
                <th>Tanggal Muat</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
            </tr>';

        if ($kapals->count() > 0) {
            foreach ($kapals as $index => $item) {
                $tgl_input   = date('d-m-Y', strtotime($item->tanggal_input));
                $tgl_sandar  = $item->tanggal_sandar ? date('d-m-Y H:i', strtotime($item->tanggal_sandar)) : '-';
                $tgl_muat    = $item->tanggal_muat ? date('d-m-Y H:i', strtotime($item->tanggal_muat)) : '-';
                $tgl_selesai = $item->tanggal_selesai ? date('d-m-Y H:i', strtotime($item->tanggal_selesai)) : '-';

                echo '<tr>
                    <td class="text-center">' . ($index + 1) . '</td>
                    <td class="text-center str">' . $tgl_input . '</td>
                    <td>' . htmlspecialchars($item->nama_kapal) . '</td>
                    <td class="text-center">' . number_format($item->qty, 0, ',', '.') . '</td>
                    <td>' . htmlspecialchars($item->agen) . '</td>
                    <td>' . htmlspecialchars($item->jenis_muatan) . '</td>
                    <td class="text-center str">' . $tgl_sandar . '</td>
                    <td class="text-center str">' . $tgl_muat . '</td>
                    <td class="text-center str">' . $tgl_selesai . '</td>
                    <td class="text-center">' . htmlspecialchars($item->status) . '</td>
                </tr>';
            }
        } else {
            echo '<tr><td colspan="10" class="text-center" style="font-style:italic;">Tidak ada data ditemukan.</td></tr>';
        }

        echo '</table></body></html>';
        exit;
    }

    /**
     * Validasi Request (Helper)
     */
    private function validateRequest(Request $request)
    {
        return $request->validate([
            'tanggal_input'   => 'required|date',
            'nama_kapal'      => 'required|string|max:255',
            'qty'             => 'required|numeric',
            'agen'            => 'required|string|max:255',
            'jenis_muatan'    => 'required|string',
            'tanggal_sandar'  => 'nullable',
            'tanggal_muat'    => 'nullable',
            'tanggal_selesai' => 'nullable',
            'status'          => 'required|in:Menunggu Sandar,Sedang Muat,Selesai',
        ]);
    }
}
