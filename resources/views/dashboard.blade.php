<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Monitoring Kapal</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<style>
    .dashboard-card {
        position: relative;

        background: white;

        border-radius: 30px;

        padding: 24px;

        overflow: hidden;

        cursor: pointer;

        transition: all .35s ease;

        box-shadow:
            0 8px 25px rgba(0, 0, 0, .05);

        border: none;
    }

    .dashboard-card::before {

        content: '';

        position: absolute;

        top: 0;
        left: 0;

        width: 100%;
        height: 4px;

        background: linear-gradient(90deg,
                #dc2626,
                #ef4444);

        transform: scaleX(0);

        transition: .3s;
    }

    .dashboard-card:hover {

        transform:
            translateY(-10px) scale(1.02);

        box-shadow:
            0 25px 50px rgba(220, 38, 38, .15);

        background: white;
    }

    .dashboard-card:hover::before {

        transform: scaleX(1);
    }

    .dashboard-card.active {

        background:
            linear-gradient(135deg,
                #b91c1c,
                #ef4444);

        color: white;

        box-shadow:
            0 20px 45px rgba(239, 68, 68, .35);
    }

    .dashboard-card.active p,
    .dashboard-card.active h3 {

        color: white !important;
    }

    .dashboard-card.active .icon-box {

        background:
            rgba(255, 255, 255, .2);
    }

    .icon-box {

        width: 60px;
        height: 60px;

        border-radius: 20px;

        display: flex;

        align-items: center;
        justify-content: center;

        font-size: 28px;

        transition: .3s;
    }

    .dashboard-card:hover .icon-box {

        transform: rotate(-8deg) scale(1.08);
    }
</style>

<body class="bg-gray-50 text-gray-800 antialiased">

    <nav class="bg-white shadow-sm border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">Dashboard Aplikasi Monitoring Kapal</h1>
            <div>
                @auth
                    <span class="text-sm text-gray-500 mr-4">Halo, {{ Auth::user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium transition">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="bg-red-600 hover:bg-red-700 text-white px-5 py-2 rounded-lg text-sm font-medium shadow-sm transition">Login
                        Admin</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div
            class="bg-gradient-to-r from-red-700 to-red-600 rounded-2xl p-6 md:p-8 text-white shadow-md mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">MONITORING KAPAL</h2>
                <p class="text-red-100 text-sm mt-1">Monitoring aktivitas kapal pengangkut semen secara real-time</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('kapal.create') }}"
                    class="bg-white hover:bg-gray-50 text-red-700 px-4 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition flex items-center gap-2">
                    <span>+ Input Data</span>
                </a>

                <a href="{{ route('kapal.preview-export') }}"
                    class="bg-red-800 hover:bg-red-900 text-white border border-red-500 px-4 py-2.5 rounded-xl text-sm font-semibold transition inline-block text-center shadow-sm">
                    Export Excel
                </a>
            </div>
        </div>

        @if (session('sukses'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 p-4 rounded-xl mb-6 text-sm">
                {{ session('sukses') }}
            </div>
        @endif

        <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-8">

            <div id="card-all" onclick="filterTable('all', this)" class="dashboard-card active">

                <div class="flex justify-between items-center">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Total Kapal
                        </p>

                        <h3 class="text-4xl font-bold text-gray-900 mt-2">
                            {{ $totalKapal }}
                        </h3>

                    </div>

                    <div class="icon-box bg-red-100">
                        🚢
                    </div>

                </div>

            </div>

            <div onclick="filterTable('Menunggu Sandar', this)" class="dashboard-card">

                <div class="flex justify-between items-center">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Menunggu Sandar
                        </p>

                        <h3 class="text-4xl font-bold text-amber-500 mt-2">
                            {{ $menungguSandar }}
                        </h3>

                    </div>

                    <div class="icon-box bg-amber-100">
                        ⏳
                    </div>

                </div>

            </div>

            <div onclick="filterTable('Sedang Muat', this)" class="dashboard-card">

                <div class="flex justify-between items-center">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Sedang Muat
                        </p>

                        <h3 class="text-4xl font-bold text-blue-500 mt-2">
                            {{ $sedangMuat }}
                        </h3>

                    </div>

                    <div class="icon-box bg-blue-100">
                        ⚓
                    </div>

                </div>

            </div>

            <div onclick="filterTable('Selesai', this)" class="dashboard-card">

                <div class="flex justify-between items-center">

                    <div>

                        <p class="text-sm font-medium text-gray-500">
                            Selesai
                        </p>

                        <h3 class="text-4xl font-bold text-emerald-500 mt-2">
                            {{ $selesai }}
                        </h3>

                    </div>

                    <div class="icon-box bg-emerald-100">
                        ✅
                    </div>

                </div>

            </div>

        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

            <div class="px-6 py-6 bg-gradient-to-r from-red-700 to-red-600 shadow-md rounded-t-2xl">
                <h3 class="font-extrabold text-white tracking-wider text-lg md:text-xl uppercase">
                    Data Monitoring Kapal
                </h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-gray-50 text-gray-700 text-xs uppercase font-bold border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-center">No</th>
                            <th class="px-6 py-3">Tanggal Input</th>
                            <th class="px-6 py-3">Nama Kapal</th>
                            <th class="px-6 py-3 text-center">Qty</th>
                            <th class="px-6 py-3">Agen</th>
                            <th class="px-6 py-3">Jenis Muatan</th>
                            <th class="px-6 py-3 text-center">Tanggal Sandar</th>
                            <th class="px-6 py-3 text-center">Tanggal Muat</th>
                            <th class="px-6 py-3 text-center">Tanggal Selesai</th>
                            <th class="px-6 py-3 text-center">Status</th>

                            @auth
                                <th class="px-6 py-3 text-center">AKSI</th>
                            @endauth
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        @forelse($kapals as $index => $item)
                            <tr data-status="{{ $item->status }}" class="hover:bg-red-50/60 transition duration-200">
                                <td class="px-6 py-4 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4">
                                    {{ \Carbon\Carbon::parse($item->tanggal_input)->format('d-m-Y') }}</td>
                                <td class="px-6 py-4 font-semibold text-gray-900">{{ $item->nama_kapal }}</td>
                                <td class="px-6 py-4">{{ $item->qty }}</td>
                                <td class="px-6 py-4">{{ $item->agen }}</td>
                                <td class="px-6 py-4">{{ $item->jenis_muatan }}</td>

                                <td class="px-6 py-4">
                                    {{ $item->tanggal_sandar ? \Carbon\Carbon::parse($item->tanggal_sandar)->format('d-m-Y H:i') : '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $item->tanggal_muat ? \Carbon\Carbon::parse($item->tanggal_muat)->format('d-m-Y H:i') : '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    {{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d-m-Y H:i') : '-' }}
                                </td>

                                <td class="px-6 py-4">
                                    @if ($item->status == 'Selesai')
                                        <span
                                            class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full font-medium text-xs">Selesai</span>
                                    @elseif($item->status == 'Sedang Muat')
                                        <span
                                            class="px-2.5 py-1 bg-blue-100 text-blue-800 rounded-full font-medium text-xs">Sedang
                                            Muat</span>
                                    @else
                                        <span
                                            class="px-2.5 py-1 bg-amber-100 text-amber-800 rounded-full font-medium text-xs">Menunggu
                                            Sandar</span>
                                    @endif
                                </td>

                                @auth
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-2">

                                            <a href="{{ route('kapal.edit', $item->id) }}"
                                                class="bg-amber-500 hover:bg-amber-600 text-white text-xs px-3 py-1.5 rounded-lg font-medium transition">
                                                Update
                                            </a>

                                            <form action="{{ route('kapal.destroy', $item->id) }}" method="POST"
                                                class="inline" onsubmit="return confirm('Hapus permanen data kapal ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded-lg font-medium transition">
                                                    Hapus
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                @endauth
                            </tr>
                        @empty
                            <tr>
                                <td colspan="@auth 11 @else 10 @endauth" class="text-center py-8 text-gray-400 italic">
                                    Belum ada data monitoring kapal.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script>
        function filterTable(status, card) {

            document
                .querySelectorAll('.dashboard-card')
                .forEach(c => c.classList.remove('active'));

            card.classList.add('active');

            const rows =
                document.querySelectorAll(
                    'tbody tr[data-status]'
                );

            rows.forEach(row => {

                if (status === 'all') {

                    row.style.display = '';

                } else {

                    row.style.display =
                        row.dataset.status === status ?
                        '' :
                        'none';
                }

            });

        }
    </script>
</body>

</html>
