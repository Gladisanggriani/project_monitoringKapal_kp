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

public function previewExport(Request $request)
{
    $query = Kapal::query();

    if ($request->filled('tanggal')) {

        $query->whereDate(
            'tanggal_input',
            $request->tanggal
        );
    }

    if ($request->filled('bulan')) {

        $bulan = explode('-', $request->bulan);

        $query->whereYear(
            'tanggal_input',
            $bulan[0]
        );

        $query->whereMonth(
            'tanggal_input',
            $bulan[1]
        );
    }

    $kapals = $query
        ->orderBy('tanggal_input', 'desc')
        ->get();

    return view(
        'kapal.preview-export',
        compact('kapals')
    );
}



    public function export(Request $request)
    {
        $query = Kapal::query();

        if ($request->jenis_filter == 'hari' && $request->tanggal) {

            $query->whereDate(
                'tanggal_input',
                $request->tanggal
            );
        }

        if ($request->jenis_filter == 'bulan' && $request->bulan) {

            $bulan = explode('-', $request->bulan);

            $query->whereYear(
                'tanggal_input',
                $bulan[0]
            );

            $query->whereMonth(
                'tanggal_input',
                $bulan[1]
            );
        }

        $kapals = $query
            ->orderBy('tanggal_input', 'desc')
            ->get();

        $fileName = "Laporan_Monitoring_Kapal_" . date('Ymd_His') . ".xls";

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '
    <html xmlns:o="urn:schemas-microsoft-com:office:office"
          xmlns:x="urn:schemas-microsoft-com:office:excel"
          xmlns="http://www.w3.org/TR/REC-html40">

    <head>

        <meta http-equiv="Content-Type"
              content="text/html; charset=utf-8">

        <style>

            body {
                font-family: Arial, sans-serif;
            }

            .kop-header {
                font-size:14pt;
                font-weight:bold;
                text-align:center;
                color:#ffffff;
                background-color:#992222;
            }

            .sub-kop {
                font-size:12pt;
                font-weight:bold;
                text-align:center;
            }

            .sub-tgl {
                font-size:10pt;
                font-style:italic;
                text-align:center;
            }

            .section-title {
                font-weight:bold;
                background-color:#fce4d6;
                color:#c00000;
                border:1px solid #000;
            }

            table {
                border-collapse:collapse;
                width:100%;
            }

            th {
                background:#f2f2f2;
                font-weight:bold;
                text-align:center;
                border:1px solid #000;
            }

            td {
                border:1px solid #000;
            }

            .text-center {
                text-align:center;
            }

        </style>

    </head>

    <body>

        <table>

            <tr>
                <td colspan="10" class="kop-header">
                    LAPORAN DATA MONITORING KAPAL
                </td>
            </tr>

            <tr>
                <td colspan="10" class="sub-kop">
                    PT Semen Padang Unit Pabrik Dumai
                </td>
            </tr>

            <tr>
                <td colspan="10" class="sub-tgl">
                    Tanggal Cetak : ' . date('d F Y H:i') . '
                </td>
            </tr>

            <tr>
                <td colspan="10"></td>
            </tr>

            <tr>
                <td colspan="10" class="section-title">
                    LOG AKTIVITAS & MONITORING KAPAL
                </td>
            </tr>

            <tr>
                <th>No</th>
                <th>Tanggal Input</th>
                <th>Nama Kapal</th>
                <th>Qty</th>
                <th>Agen</th>
                <th>Jenis Muatan</th>
                <th>Tanggal Sandar</th>
                <th>Tanggal Muat</th>
                <th>Tanggal Selesai</th>
                <th>Status</th>
            </tr>';

        if ($kapals->count() > 0) {

            foreach ($kapals as $index => $item) {

                echo '
            <tr>

                <td class="text-center">
                    ' . ($index + 1) . '
                </td>

                <td class="text-center">
                    ' . date('d-m-Y', strtotime($item->tanggal_input)) . '
                </td>

                <td>
                    ' . htmlspecialchars($item->nama_kapal) . '
                </td>

                <td class="text-center">
                    ' . number_format($item->qty, 0, ',', '.') . '
                </td>

                <td>
                    ' . htmlspecialchars($item->agen) . '
                </td>

                <td>
                    ' . htmlspecialchars($item->jenis_muatan) . '
                </td>

                <td class="text-center">
                    ' . ($item->tanggal_sandar
                    ? date('d-m-Y', strtotime($item->tanggal_sandar))
                    : '-') . '
                </td>

                <td class="text-center">
                    ' . ($item->tanggal_muat
                    ? date('d-m-Y', strtotime($item->tanggal_muat))
                    : '-') . '
                </td>

                <td class="text-center">
                    ' . ($item->tanggal_selesai
                    ? date('d-m-Y', strtotime($item->tanggal_selesai))
                    : '-') . '
                </td>

                <td class="text-center">
                    ' . htmlspecialchars($item->status) . '
                </td>

            </tr>';
            }
        } else {

            echo '
        <tr>

            <td colspan="10" class="text-center">
                Tidak ada data yang sesuai filter
            </td>

        </tr>';
        }

        echo '
        </table>

    </body>

    </html>';

        exit;
    }
}
