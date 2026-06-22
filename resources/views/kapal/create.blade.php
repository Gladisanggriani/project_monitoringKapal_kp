<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Kapal Baru</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-50 py-12 text-gray-800 antialiased">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold mb-8 text-gray-900 border-b pb-4">Form Tambah Aktivitas Kapal</h2>

        <form action="{{ route('kapal.store') }}" method="POST" class="space-y-7">
            @csrf

            <input type="hidden" name="status" value="Menunggu Sandar">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Input Data</label>
                <input type="date" name="tanggal_input" value="{{ date('Y-m-d') }}" required
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 py-2.5">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kapal</label>
                    <input type="text" name="nama_kapal" required placeholder="Contoh: Kapal Ferry"
                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 py-2.5">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Qty Muatan</label>
                    <input type="number" name="qty" required placeholder="0"
                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 py-2.5">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Agen</label>
                    <input type="text" name="agen" required placeholder="Contoh: Jasa Bahari"
                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 py-2.5">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Jenis Muatan
                    </label>

                    <select name="jenis_muatan" id="jenis_muatan" required
                        class="w-full rounded-xl border border-gray-300 shadow-sm py-2.5 px-3.5">
                        <option value="">-- Pilih Jenis Muatan --</option>
                        <option value="Semen Padang">Semen Padang</option>
                        <option value="Dynamix">Dynamix</option>
                        <option value="Merdeka">Merdeka</option>
                    </select>
                </div>

                <div id="berat-container" style="display:none;">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Berat
                    </label>

                    <select name="berat_muatan" id="berat_muatan"
                        class="w-full rounded-xl border border-gray-300 shadow-sm py-2.5 px-3.5">
                        <option value="">-- Pilih Berat --</option>
                        <option value="40 Kg">40 Kg</option>
                        <option value="50 Kg">50 Kg</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('dashboard') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium transition">Batal</a>
                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition">Simpan
                    Data</button>
            </div>
        </form>
    </div>
</body>

</html>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const jenisMuatan = document.getElementById('jenis_muatan');
    const beratContainer = document.getElementById('berat-container');

    jenisMuatan.addEventListener('change', function () {

        if (this.value !== '') {
            beratContainer.style.display = 'block';
        } else {
            beratContainer.style.display = 'none';
        }

    });

});
</script>
