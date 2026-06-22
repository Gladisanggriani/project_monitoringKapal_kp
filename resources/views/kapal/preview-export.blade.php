<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview Export | ShipWatch Pro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>

<body class="bg-[#f8fafc] text-slate-800 antialiased">

    <nav class="glass-nav sticky top-0 z-40 border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-10 w-auto">
                <div class="flex flex-col">
                    <span class="font-extrabold text-slate-900 tracking-tight text-lg">
                        Aplikasi Monitoring <span class="text-red-600">Kapal</span>
                    </span>
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-wider">PT Semen Padang — Unit
                        Pabrik Dumai</span>
                </div>
            </div>
            <div class="flex items-center gap-4">
                @auth
                    <div class="flex flex-col items-end mr-2">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Administrator</span>
                        <span class="text-sm font-bold text-slate-800">{{ Auth::user()->name }}</span>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="p-2.5 rounded-xl bg-slate-100 hover:bg-red-50 hover:text-red-600 text-slate-600 transition-all duration-200 group">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-slate-900 hover:bg-slate-800 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-lg shadow-slate-200 transition-all active:scale-95">
                        Sign In
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8 pb-20">

        <div
            class="bg-gradient-to-r from-red-700 to-red-600 rounded-2xl p-8 text-white shadow-lg mb-8 flex flex-col md:flex-row md:items-center justify-between gap-6">

            <div>
                <h1 class="text-3xl font-extrabold tracking-tight">Preview Laporan</h1>
            </div>

            @if ($kapals->count() > 0)
                <div class="ml-auto flex items-center gap-3">

                    <a href="{{ route('kapal.export', request()->all()) }}"
                        class="inline-flex items-center gap-2 bg-white text-red-700 hover:bg-gray-100 px-6 py-3 rounded-xl font-bold shadow ">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                stroke-width="2.5" />
                        </svg>

                        DOWNLOAD EXCEL
                    </a>

                    <a href="{{ route('dashboard') }}"
                        class="bg-white text-red-700 hover:bg-gray-100 px-5 py-3 rounded-xl font-semibold transition shadow-sm">

                        ← Kembali
                    </a>

                </div>
            @endif

        </div>

        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 p-8 mb-10">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
                        stroke-width="2" />
                </svg>
                Filter Laporan
            </h3>
            <form action="{{ route('kapal.preview-export') }}" method="GET" class="flex flex-wrap items-end gap-6">
                <div class="w-full md:w-56">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Pilih Hari</label>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                        class="w-full bg-slate-50 border-slate-200 rounded-xl py-3 px-4 focus:ring-2 focus:ring-red-500 transition-all outline-none">
                </div>

                <div class="w-full md:w-56">
                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Pilih Bulan</label>
                    <input type="month" name="bulan" value="{{ request('bulan') }}"
                        class="w-full bg-slate-50 border-slate-200 rounded-xl py-3 px-4 focus:ring-2 focus:ring-red-500 transition-all outline-none">
                </div>

                <div class="flex gap-3 w-full md:w-auto">
                    <button type="submit"
                        class="flex-1 md:flex-none bg-slate-900 hover:bg-slate-800 text-white px-8 py-3 rounded-xl text-sm font-bold transition-all active:scale-95">
                        Tampilkan Data
                    </button>
                    <a href="{{ route('kapal.preview-export') }}"
                        class="px-6 py-3 text-slate-400 hover:text-slate-600 text-sm font-bold transition-all">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="font-extrabold text-slate-900 text-xl flex items-center gap-3">
                    <span class="w-2 h-8 bg-red-600 rounded-full"></span>
                    LOG MONITORING
                </h3>
                <span class="px-4 py-1 bg-slate-200 text-slate-700 rounded-full text-xs font-bold tracking-widest">
                    {{ count($kapals) }} RECORDS
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">No</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Nama Kapal
                            </th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">
                                Qty</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Agen /
                                Muatan</th>
                            <th
                                class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">
                                Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Timeline
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kapals as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition-colors duration-200 group">
                                <td class="px-6 py-5 text-sm font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-5">
                                    <div class="font-bold text-slate-900 group-hover:text-red-600 transition-colors">
                                        {{ $item->nama_kapal }}</div>
                                    <div
                                        class="text-[10px] text-slate-400 font-bold uppercase mt-1 tracking-tighter italic">
                                        ID: {{ $item->id }} • Input:
                                        {{ \Carbon\Carbon::parse($item->tanggal_input)->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center font-black text-slate-700">
                                    {{ number_format($item->qty) }}
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-sm font-bold text-slate-800">{{ $item->agen }}</div>
                                    <div class="text-xs text-slate-500 font-medium italic mt-0.5">
                                        {{ $item->jenis_muatan }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @php
                                        $statusStyles = [
                                            'Selesai' => 'bg-emerald-100 text-emerald-700',
                                            'Sedang Muat' => 'bg-blue-100 text-blue-700',
                                            'Menunggu Sandar' => 'bg-amber-100 text-amber-700',
                                        ];
                                    @endphp
                                    <span
                                        class="px-4 py-1.5 {{ $statusStyles[$item->status] ?? 'bg-slate-100 text-slate-700' }} rounded-full text-[11px] font-black uppercase tracking-wider">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col gap-1 text-[11px]">
                                        <div class="flex justify-between gap-4">
                                            <span class="text-slate-400">Sandar:</span>
                                            <span
                                                class="font-bold">{{ $item->tanggal_sandar ? date('d/m H:i', strtotime($item->tanggal_sandar)) : '-' }}</span>
                                        </div>
                                        <div class="flex justify-between gap-4">
                                            <span class="text-slate-400">Selesai:</span>
                                            <span
                                                class="font-bold text-emerald-600">{{ $item->tanggal_selesai ? date('d/m H:i', strtotime($item->tanggal_selesai)) : '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center py-20">
                                    <div class="flex flex-col items-center opacity-20">
                                        <svg class="w-20 h-20 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-xl font-black uppercase tracking-widest">Belum ada data</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <footer class="fixed bottom-0 left-0 w-full bg-white border-t border-slate-200 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3 text-xs font-semibold text-slate-400">
                <p>&copy; {{ date('Y') }} PT Semen Padang. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>

</html>
