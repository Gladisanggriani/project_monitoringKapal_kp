<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Kapal | Dashboard Monitoring Kapal</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- Untuk preview jika vite tidak jalan, hapus jika sudah di lingkungan Laravel -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .dashboard-card {
            position: relative;
            background: white;
            border-radius: 20px;
            padding: 24px;
            overflow: hidden;
            cursor: pointer;
            transition: all .4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #f1f5f9;
        }

        .dashboard-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgba(220, 38, 38, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #fecaca;
        }

        .icon-box {
            width: 54px;
            height: 54px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: .3s;
        }

        .dashboard-card:hover .icon-box {
            transform: rotate(-12deg) scale(1.1);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        @keyframes popupAnimation {
            from {
                opacity: 0;
                transform: scale(.95) translateY(20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .animate-popup {
            animation: popupAnimation .3s ease-out forwards;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>

<body class="bg-[#f8fafc] text-slate-800 antialiased">

    <!-- Navigasi Modern -->
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

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 pb-20">

        <!-- Hero Section -->
        <div
            class="relative overflow-hidden bg-gradient-to-br from-red-700 via-red-600 to-orange-600 rounded-[2rem] p-8 md:p-12 text-white shadow-2xl shadow-red-200 mb-10">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div class="max-w-2xl">
                    <span
                        class="inline-block px-4 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold uppercase tracking-widest mb-4">
                        Real-time Monitoring
                    </span>
                    <h2 class="text-3xl md:text-3xl font-black tracking-tight leading-tight">DASHBOARD<br>MONITORING
                        KAPAL
                    </h2>
                </div>
                <div class="flex flex-wrap gap-4">
                    @auth
                        <a href="{{ route('kapal.create') }}"
                            class="bg-white text-red-600 hover:bg-red-50 px-6 py-3.5 rounded-2xl text-sm font-extrabold shadow-xl transition-all flex items-center gap-3 active:scale-95">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round" />
                            </svg>
                            INPUT DATA
                        </a>
                    @endauth
                    <a href="{{ route('kapal.preview-export') }}"
                        class="bg-red-800/40 hover:bg-red-800/60 text-white border border-white/30 backdrop-blur-md px-6 py-3.5 rounded-2xl text-sm font-extrabold transition-all active:scale-95">
                        EXPORT EXCEL
                    </a>
                </div>
            </div>
            <!-- Dekorasi Abstrak -->
            <div
                class="absolute top-0 right-0 -translate-y-12 translate-x-12 w-64 h-64 bg-white/10 rounded-full blur-3xl">
            </div>
            <div
                class="absolute bottom-0 left-0 translate-y-12 -translate-x-12 w-64 h-64 bg-red-900/20 rounded-full blur-3xl">
            </div>
        </div>

        @if (session('sukses'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-2xl mb-8 flex items-center gap-3 animate-popup">
                <svg class="w-6 h-6 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                </svg>
                <span class="font-bold">{{ session('sukses') }}</span>
            </div>
        @endif

        <!-- Grid Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <!-- Total -->
            <div onclick='showKapal("🚢 Daftar Kapal Hari Ini", @json($kapals->pluck('nama_kapal')->toArray()))' class="dashboard-card">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Total Kapal</p>
                        <h3 class="text-4xl font-black text-slate-900 mt-2">{{ $totalKapal }}</h3>
                    </div>
                    <div class="icon-box bg-slate-100 text-slate-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M19 14l-7 7m0 0l-7-7m7 7V3" stroke-width="2" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Menunggu -->
            <div onclick='showKapal("⏳ Kapal Menunggu Sandar", @json($kapals->where('status', 'Menunggu Sandar')->pluck('nama_kapal')->toArray()))'
                class="dashboard-card border-l-4 border-l-amber-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-bold text-amber-500 uppercase tracking-widest">Menunggu</p>
                        <h3 class="text-4xl font-black text-slate-900 mt-2">{{ $menungguSandar }}</h3>
                    </div>
                    <div class="icon-box bg-amber-50 text-amber-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Muat -->
            <div onclick='showKapal("⚓ Kapal Sedang Muat", @json($kapals->where('status', 'Sedang Muat')->pluck('nama_kapal')->toArray()))'
                class="dashboard-card border-l-4 border-l-blue-500">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-bold text-blue-500 uppercase tracking-widest">Sedang Muat</p>
                        <h3 class="text-4xl font-black text-slate-900 mt-2">{{ $sedangMuat }}</h3>
                    </div>
                    <div class="icon-box bg-blue-50 text-blue-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" stroke-width="2" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Selesai -->
            <div onclick='showKapal("✅ Kapal Selesai", @json($kapalsSelesai->pluck('nama_kapal')->toArray()))'
                class="dashboard-card border-l-4 border-l-emerald-500">

                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm font-bold text-emerald-500 uppercase tracking-widest">Selesai</p>
                        <h3 class="text-4xl font-black text-slate-900 mt-2">{{ $selesai }}</h3>
                    </div>

                    <div class="icon-box bg-emerald-50 text-emerald-600">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M5 13l4 4L19 7" stroke-width="2" />
                        </svg>
                    </div>
                </div>

            </div>
        </div>

        <!-- Tabel Monitoring Modern -->
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
                            @auth <th
                                    class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">
                                Aksi</th> @endauth
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kapals as $index => $item)
                            <tr class="hover:bg-slate-50/80 transition-colors duration-200 group">
                                <td class="px-6 py-5 text-sm font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-5">
                                    <div class="font-bold text-slate-900">{{ $item->nama_kapal }}</div>
                                    <div class="text-[10px] text-slate-400 font-bold uppercase mt-1 tracking-tighter">
                                        ID: {{ $item->id }}</div>
                                </td>
                                <td class="px-6 py-5 text-center font-black text-slate-700">
                                    {{ number_format($item->qty) }}</td>
                                <td class="px-6 py-5">
                                    <div class="text-sm font-bold text-slate-800">{{ $item->agen }}</div>
                                    <div class="text-xs text-slate-500 font-medium italic mt-0.5">
                                        {{ $item->jenis_muatan }}</div>
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
                                        class="px-4 py-1.5 {{ $statusStyles[$item->status] ?? 'bg-slate-100' }} rounded-full text-[11px] font-black uppercase tracking-wider">
                                        {{ $item->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col gap-1 text-[11px]">
                                        <div class="flex justify-between"><span class="text-slate-400">Sandar:</span>
                                            <span
                                                class="font-bold">{{ $item->tanggal_sandar ? date('d/m H:i', strtotime($item->tanggal_sandar)) : '-' }}</span>
                                        </div>
                                        <div class="flex justify-between"><span class="text-slate-400">Selesai:</span>
                                            <span
                                                class="font-bold text-emerald-600">{{ $item->tanggal_selesai ? date('d/m H:i', strtotime($item->tanggal_selesai)) : '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                @auth
                                    <td class="px-6 py-5">
                                        <div class="flex justify-center gap-2">
                                            <a href="{{ route('kapal.edit', $item->id) }}"
                                                class="p-2 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-500 hover:text-white transition-all duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                        stroke-width="2" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('kapal.destroy', $item->id) }}" method="POST"
                                                class="inline" onsubmit="return confirm('Hapus data ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-600 hover:text-white transition-all duration-200">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                            stroke-width="2" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                @endauth
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

    <!-- MODAL PREMIUM (Glassmorphism) -->
    <div id="infoModal" class="hidden fixed inset-0 z-[100]  items-center justify-center px-4">
        <div onclick="closeModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity">
        </div>
        <div
            class="relative bg-white w-full max-w-lg rounded-[2.5rem] overflow-hidden shadow-2xl animate-popup border border-white">
            <div
                class="bg-gradient-to-r from-red-700 to-red-600 px-8 py-6 text-white flex justify-between items-center">
                <h2 id="modalTitle" class="text-xl font-black uppercase tracking-wider"></h2>
                <button onclick="closeModal()"
                    class="p-2 bg-white/20 hover:bg-white/30 rounded-full transition-all">✕</button>
            </div>
            <div class="p-8">
                <div class="bg-slate-50 rounded-3xl p-6 max-h-[60vh] overflow-y-auto" id="modalText">
                    <!-- Data injected via JS -->
                </div>
                <button onclick="closeModal()"
                    class="w-full mt-8 bg-slate-900 text-white py-4 rounded-2xl font-black uppercase tracking-widest shadow-xl shadow-slate-200 hover:shadow-none transition-all active:scale-95">
                    Selesai
                </button>
            </div>
        </div>
    </div>
    <footer class="fixed bottom-0 left-0 w-full bg-white border-t border-slate-200 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3 text-xs font-semibold text-slate-400">
                <p>&copy; {{ date('Y') }} PT Semen Padang. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        function showKapal(title, data) {
            const modalTitle = document.getElementById('modalTitle');
            const modalText = document.getElementById('modalText');
            const modal = document.getElementById('infoModal');

            modalTitle.innerText = title;
            let html = '';

            if (data.length === 0) {
                html = `
                    <div class="text-center py-10 opacity-40">
                        <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                        <p class="font-bold uppercase tracking-widest">Kosong</p>
                    </div>`;
            } else {
                html = `<div class="grid gap-3">`;
                data.forEach(item => {
                    html += `
                        <div class="flex items-center gap-4 bg-white p-4 rounded-2xl border border-slate-100 shadow-sm">
                            <div class="w-10 h-10 bg-red-50 text-red-600 rounded-xl flex items-center justify-center font-bold">
                                🚢
                            </div>
                            <div class="font-extrabold text-slate-800 uppercase tracking-tight">${item}</div>
                        </div>`;
                });
                html += `</div>`;
            }

            modalText.innerHTML = html;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('infoModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        // Close on ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closeModal();
        });
    </script>
</body>

</html>
