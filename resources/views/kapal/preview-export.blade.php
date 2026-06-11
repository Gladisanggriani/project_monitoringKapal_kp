<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Export Data</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-800 antialiased">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- HEADER -->
        <div
            class="bg-gradient-to-r from-red-700 to-red-600 rounded-2xl p-6 md:p-8 text-white shadow-md mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h1 class="text-3xl font-extrabold tracking-tight">
                    Preview Export Data
                </h1>

                <p class="text-red-100 mt-2">
                    Filter dan unduh laporan monitoring kapal
                </p>
            </div>

            <div>
                <a href="{{ route('dashboard') }}"
                    class="bg-white text-red-700 hover:bg-gray-100 px-5 py-3 rounded-xl font-semibold transition shadow-sm inline-block">

                    ← Kembali

                </a>
            </div>

        </div>
        <!-- FILTER -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm p-6 mb-8">

            <h3 class="text-lg font-bold text-gray-900 mb-5">
                Filter Data
            </h3>

            <form action="{{ route('kapal.preview-export') }}" method="GET">

                <div class="grid md:grid-cols-3 gap-5">

                    <div>

                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Per Hari
                        </label>

                        <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                            class="w-full rounded-xl border border-gray-200 focus:border-red-500 focus:ring-red-500">

                    </div>

                    <div>

                        <label class="block text-sm font-medium text-gray-600 mb-2">
                            Per Bulan
                        </label>

                        <input type="month" name="bulan" value="{{ request('bulan') }}"
                            class="w-full rounded-xl border border-gray-200 focus:border-red-500 focus:ring-red-500">

                    </div>

                    <div class="flex items-end">

                        <button type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white py-3 rounded-xl font-semibold transition shadow-sm">

                            Tampilkan Data

                        </button>

                    </div>

                </div>

            </form>

        </div>

        <!-- ACTION BUTTON -->
        <div class="flex flex-wrap gap-3 mb-6">

            <a href="{{ route('kapal.export', request()->all()) }}"
                class="
        inline-flex items-center
        gap-2

        bg-red-600
        hover:bg-red-700

        text-white

        px-6 py-3

        rounded-2xl

        font-bold

        shadow-lg
        shadow-red-300/40

        transition-all
        duration-300

        hover:-translate-y-1
        ">
                Download Excel

            </a>

        </div>

        <!-- TABLE -->
        <div class="bg-white rounded-3xl border  border-gray-100 shadow-sm overflow-hidden">

            <div class="px-6 py-5 bg-gradient-to-r from-red-700 to-red-600 rounded-2xl p-6 md:p-8">

                <h3 class="text-white font-bold tracking-wide uppercase text-lg">
                    Hasil Preview Data
                </h3>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm whitespace-nowrap">

                    <thead class="bg-gray-50 text-gray-700 text-xs uppercase font-bold border-b border-gray-200">

                        <tr>

                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Tanggal Input</th>
                            <th class="px-6 py-4">Nama Kapal</th>
                            <th class="px-6 py-4">Qty</th>
                            <th class="px-6 py-4">Agen</th>
                            <th class="px-6 py-4">Status</th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($kapals as $kapal)
                            <tr class="hover:bg-red-50 transition duration-200">

                                <td class="px-6 py-4">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($kapal->tanggal_input)->format('d-m-Y') }}
                                </td>

                                <td class="px-6 py-4 font-semibold text-gray-900">
                                    {{ $kapal->nama_kapal }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ number_format($kapal->qty, 0, ',', '.') }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $kapal->agen }}
                                </td>

                                <td class="px-6 py-4">

                                    @if ($kapal->status == 'Selesai')
                                        <span
                                            class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">

                                            Selesai

                                        </span>
                                    @elseif($kapal->status == 'Sedang Muat')
                                        <span
                                            class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">

                                            Sedang Muat

                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">

                                            Menunggu Sandar

                                        </span>
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-10 text-gray-400 italic">

                                    Tidak ada data yang ditemukan

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</body>

</html>
