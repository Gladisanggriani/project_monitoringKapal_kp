<?php

namespace App\Http\Controllers;

use App\Models\Kapal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class KapalController extends Controller
{
    // Tampilan Utama Dashboard (Hanya menampilkan kapal yang BELUM diarsipkan)
    public function index()
    {
        // 1. QUERY UNTUK TABEL: Hanya menampilkan kapal aktif (belum diarsipkan)
        $kapals = Kapal::where('is_archived', 0)
            ->orderBy('tanggal_input', 'desc')
            ->get();

        // 2. QUERY UNTUK KOTAK STATISTIK: Menggunakan camelCase agar sinkron dengan file blade Anda
        $totalKapal     = Kapal::count();
        $menungguSandar = Kapal::where('status', 'Menunggu Sandar')->count();
        $sedangMuat     = Kapal::where('status', 'Sedang Muat')->count();
        $selesai        = Kapal::where('status', 'Selesai')->count();

        // Mengirimkan variabel dengan nama yang tepat ke view dashboard
        return view('dashboard', compact('kapals', 'totalKapal', 'menungguSandar', 'sedangMuat', 'selesai'));
    }

    // Form Input Data Baru (Admin Only)
    public function create()
    {
        return view('kapal.create');
    }

    // Simpan Data Baru (Admin Only)
    public function store(Request $request)
    {
        $request->validate([
            'tanggal_input'   => 'required|date',
            'nama_kapal'      => 'required|string',
            'qty'             => 'required|numeric',
            'agen'            => 'required|string',
            'jenis_muatan'    => 'required|string',
            'tanggal_sandar'  => 'nullable',
            'tanggal_muat'    => 'nullable',
            'tanggal_selesai' => 'nullable',
            'status'          => 'required|in:Menunggu Sandar,Sedang Muat,Selesai',
        ]);

        // Menyimpan data masukan mentah (is_archived otomatis 0 dari database default)
        Kapal::create($request->all());

        return redirect()->route('dashboard')->with('sukses', 'Data kapal berhasil ditambahkan!');
    }

    // Form Edit / Update Data (Admin Only)
    public function edit(Kapal $kapal)
    {
        return view('kapal.edit', compact('kapal'));
    }

    // Update Proses Data (Admin Only)
    // Update Data (Admin Only)
    public function update(Request $request, Kapal $kapal)
    {
        $request->validate([
            'tanggal_input'   => 'required|date',
            'nama_kapal'      => 'required|string',
            'qty'             => 'required|numeric',
            'agen'            => 'required|string',
            'jenis_muatan'    => 'required|string',
            'tanggal_sandar'  => 'nullable',
            'tanggal_muat'    => 'nullable',
            'tanggal_selesai' => 'nullable',
            'status'          => 'required|in:Menunggu Sandar,Sedang Muat,Selesai',
        ]);

        // Ambil semua data inputan dari form
        $inputData = $request->all();

        // LOGIKA OTOMATIS: Jika admin mengubah status menjadi 'Selesai',
        // maka field 'is_archived' langsung diset menjadi 1 agar otomatis hilang dari dashboard.
        if ($request->status == 'Selesai') {
            $inputData['is_archived'] = 1;

            // Eksekusi update data ke database
            $kapal->update($inputData);

            // Berikan pesan alert khusus bahwa data dipindahkan ke laporan
            return redirect()->route('dashboard')->with('sukses', "Aktivitas kapal {$kapal->nama_kapal} telah Selesai! Data otomatis dipindahkan ke dalam laporan.");
        }

        // Jika status masih Menunggu Sandar / Sedang Muat, update seperti biasa (tetap tampil di dashboard)
        $inputData['is_archived'] = 0;
        $kapal->update($inputData);

        return redirect()->route('dashboard')->with('sukses', 'Data kapal berhasil diperbarui!');
    }

    // Aksi Centang Sukses (Memindahkan data aktif ke arsip laporan agar hilang dari dashboard)
    public function archive($id)
    {
        $kapal = Kapal::findOrFail($id);

        // Sembunyikan dari dashboard utama dengan mengubah status arsip menjadi 1
        $kapal->update(['is_archived' => 1]);

        return redirect()->route('dashboard')->with('sukses', "Aktivitas kapal {$kapal->nama_kapal} telah selesai diselesaikan dan dipindahkan ke riwayat laporan.");
    }

    // Hapus Permanen Data Kapal (Admin Only)
    public function destroy(Kapal $kapal)
    {
        $kapal->delete();
        return redirect()->route('dashboard')->with('sukses', 'Data kapal berhasil dihapus dari sistem!');
    }

    // Tampilan Halaman Pratinjau Sebelum Didownload (Mampu melihat data aktif maupun terarsip)
    // Tampilan Halaman Pratinjau Sebelum Didownload
    public function previewExport(Request $request)
    {
        $query = Kapal::query();

        // Inisialisasi default agar variabel tidak memicu "Undefined variable"
        $filter_tanggal = $request->tanggal;
        $filter_bulan = $request->bulan;
        $jenis_filter = 'semua';

        // Deteksi inputan filter dari user
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_input', $request->tanggal);
            $jenis_filter = 'hari';
        } elseif ($request->filled('bulan')) {
            $bulan = explode('-', $request->bulan);
            $query->whereYear('tanggal_input', $bulan[0]);
            $query->whereMonth('tanggal_input', $bulan[1]);
            $jenis_filter = 'bulan';
        }

        // Ambil data kapal sesuai saringan filter
        $kapals = $query->orderBy('tanggal_input', 'desc')->get();

        // Pastikan nama variabel di compact sama persis dengan yang dipanggil di blade
        return view('kapal.preview-export', compact('kapals', 'filter_tanggal', 'filter_bulan', 'jenis_filter'));
    }

    // Proses Download Format Spreadsheet Excel (.xls)
    public function export(Request $request)
    {
        $query = Kapal::query();

        // Filter harian data unduhan
        if ($request->jenis_filter == 'hari' && $request->tanggal) {
            $query->whereDate('tanggal_input', $request->tanggal);
        }

        // Filter bulanan data unduhan
        if ($request->jenis_filter == 'bulan' && $request->bulan) {
            $bulan = explode('-', $request->bulan);
            $query->whereYear('tanggal_input', $bulan[0]);
            $query->whereMonth('tanggal_input', $bulan[1]);
        }

        // Ambil semua data (termasuk yang diarsipkan) agar tetap masuk laporan
        $kapals = $query->orderBy('tanggal_input', 'asc')->get();

        $fileName = "Laporan_Monitoring_Kapal_" . date('Ymd_His') . ".xls";

        // Pengaturan Header HTTP yang ketat agar dibaca sebagai Excel Dokumen asli, bukan teks CSV
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$fileName\"");
        header("Cache-Control: max-age=0");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Membuka output stream untuk menulis HTML bersih tanpa ada whitespace Laravel yang merusak format
        $output = fopen('php://output', 'w');

        $html = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
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
    /* Paksa Excel membaca kolom sebagai format teks agar jam tidak berubah menjadi angka desimal aneh */
    .str { mso-number-format:"\@"; }
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
                $tgl_sandar  = $item->tanggal_sandar ? date('d-m-Y H:i', strtotime($item->tanggal_sandar)) : '-';
                $tgl_muat    = $item->tanggal_muat ? date('d-m-Y H:i', strtotime($item->tanggal_muat)) : '-';
                $tgl_selesai = $item->tanggal_selesai ? date('d-m-Y H:i', strtotime($item->tanggal_selesai)) : '-';

                $html .= '<tr>
                    <td class="text-center">' . ($index + 1) . '</td>
                    <td class="text-center str">' . date('d-m-Y', strtotime($item->tanggal_input)) . '</td>
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
            $html .= '<tr>
                <td colspan="10" class="text-center" style="font-style:italic; color:#777; height:30px;">
                    Tidak ada data riwayat yang sesuai dengan kriteria filter pencarian.
                </td>
            </tr>';
        }

        $html .= '</table></body></html>';

        fwrite($output, $html);
        fclose($output);
        exit;
    }
}
