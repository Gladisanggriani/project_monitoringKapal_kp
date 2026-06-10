<?php
namespace App\Http\Controllers;

use App\Models\Kapal;
use Illuminate\Http\Request;

class KapalController extends Controller
{
    // Tampilan Utama Dashboard (Viewer & Admin)
    public function index()
    {
        $kapals = Kapal::orderBy('tanggal_input', 'desc')->get();

        // Hitung Counter Box secara dinamis
        $totalKapal     = $kapals->count();
        $menungguSandar = $kapals->where('status', 'Menunggu Sandar')->count();
        $sedangMuat     = $kapals->where('status', 'Sedang Muat')->count();
        $selesai        = $kapals->where('status', 'Selesai')->count();

        return view('dashboard', compact('kapals', 'totalKapal', 'menungguSandar', 'sedangMuat', 'selesai'));
    }

    // Form Input Data (Admin Only)
    public function create()
    {
        return view('kapal.create');
    }

    // Simpan Data Baru (Admin Only)
    public function store(Request $request)
    {
        $validasi = $request->validate([
            'tanggal_input' => 'required|date',
            'nama_kapal'    => 'required|string',
            'qty'           => 'required|numeric',
            'agen'          => 'required|string',
            'jenis_muatan'  => 'required|string',
            'status'        => 'required|in:Menunggu Sandar,Sedang Muat,Selesai',
        ]);

        Kapal::create($request->all());
        return redirect()->route('dashboard')->with('sukses', 'Data kapal berhasil ditambahkan!');
    }

    // Form Edit Data (Admin Only)
    public function edit(Kapal $kapal)
    {
        return view('kapal.edit', compact('kapal'));
    }

    // Update Data (Admin Only)
    public function update(Request $request, Kapal $kapal)
    {
        $request->validate([
            'tanggal_input' => 'required|date',
            'nama_kapal'    => 'required|string',
            'qty'           => 'required|numeric',
            'agen'          => 'required|string',
            'jenis_muatan'  => 'required|string',
            'status'        => 'required|in:Menunggu Sandar,Sedang Muat,Selesai',
        ]);

        $kapal->update($request->all());
        return redirect()->route('dashboard')->with('sukses', 'Data kapal berhasil diperbarui!');
    }

    // Hapus Data (Admin Only)
    public function destroy(Kapal $kapal)
    {
        $kapal->delete();
        return redirect()->route('dashboard')->with('sukses', 'Data kapal berhasil dihapus!');
    }

    public function export()
    {
        // Ambil data terbaru dari database
        $kapals = Kapal::orderBy('tanggal_input', 'desc')->get();

        // Tentukan nama file laporan excel
        $fileName = "Laporan_Nuansa_Harian_Kapal_" . date('Ymd') . ".xls";

        // Atur header HTTP agar browser mendownloadnya sebagai file Excel resmi
        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Pragma: no-cache");
        header("Expires: 0");

        // Mulai cetak struktur HTML Table yang dibaca otomatis oleh Excel dengan border rapi
        echo '
        <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <style>
                /* Pengaturan Font dan Border Garis agar persis format perusahaan */
                body { font-family: Arial, sans-serif; }
                .kop-header { font-size: 14pt; font-weight: bold; text-align: center; color: #ffffff; background-color: #992222; }
                .sub-kop { font-size: 12pt; font-weight: bold; text-align: center; }
                .sub-tgl { font-size: 10pt; font-style: italic; text-align: center; }
                .section-title { font-weight: bold; background-color: #fce4d6; color: #c00000; border: 1px solid #000000; }
                
                table { border-collapse: collapse; width: 100%; }
                th { background-color: #f2f2f2; font-weight: bold; text-align: center; vertical-align: middle; border: 1px solid #000000; font-size: 10pt; height: 25px; }
                td { border: 1px solid #000000; font-size: 10pt; vertical-align: middle; height: 22px; }
                .text-center { text-align: center; }
                .text-right { text-align: right; }
            </style>
        </head>
        <body>

            <table>
                <tr>
                    <td colspan="10" class="kop-header" height="30">LAPORAN DATA MONITORING KAPAL</td>
                </tr>
                <tr>
                    <td colspan="10" class="sub-kop" height="25">PT Semen Padang Unit Pabrik Dumai</td>
                </tr>
                <tr>
                    <td colspan="10" class="sub-tgl" height="20">Tanggal Cetak Laporan: ' . date('d F Y') . '</td>
                </tr>
                <tr>
                    <td colspan="10" height="15" style="border:none;"></td>
                </tr>

                <tr>
                    <td colspan="10" class="section-title" height="25"> LOG AKTIVITAS & MONITORING KAPAL</td>
                </tr>

                <tr>
                    <th width="50">No</th>
                    <th width="120">Tanggal Input</th>
                    <th width="180">Nama Kapal</th>
                    <th width="80">Qty</th>
                    <th width="120">Agen</th>
                    <th width="180">Jenis Muatan</th>
                    <th width="120">Tanggal Sandar</th>
                    <th width="120">Tanggal Muat</th>
                    <th width="120">Tanggal Selesai</th>
                    <th width="140">Status</th>
                </tr>';

                // LOOPING MEMASUKKAN DATA KAPAL DARI DATABASE
                if($kapals->count() > 0) {
                    foreach ($kapals as $index => $item) {
                        echo '
                        <tr>
                            <td class="text-center">' . ($index + 1) . '</td>
                            <td class="text-center">' . date('d-m-Y', strtotime($item->tanggal_input)) . '</td>
                            <td>' . htmlspecialchars($item->nama_kapal) . '</td>
                            <td class="text-center">' . number_format($item->qty, 0, ',', '.') . '</td>
                            <td class="text-center">' . htmlspecialchars($item->agen) . '</td>
                            <td>' . htmlspecialchars($item->jenis_muatan) . '</td>
                            <td class="text-center">' . ($item->tanggal_sandar ? date('d-m-Y', strtotime($item->tanggal_sandar)) : '-') . '</td>
                            <td class="text-center">' . ($item->tanggal_muat ? date('d-m-Y', strtotime($item->tanggal_muat)) : '-') . '</td>
                            <td class="text-center">' . ($item->tanggal_selesai ? date('d-m-Y', strtotime($item->tanggal_selesai)) : '-') . '</td>
                            <td class="text-center" style="font-weight:bold; color: ' . ($item->status == 'Selesai' ? '#008000' : '#b22222') . ';">' . $item->status . '</td>
                        </tr>';
                    }
                } else {
                    echo '<tr><td colspan="10" class="text-center" height="30">Belum ada data aktivitas monitoring kapal.</td></tr>';
                }

        echo '
            </table>
        </body>
        </html>';
        exit;
    }
    
}