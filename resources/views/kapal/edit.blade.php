<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Data Kapal</title>
    @vite(['resources/css/app.css'])
</head>

<body class="bg-gray-50 py-12 text-gray-800 antialiased">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <h2 class="text-xl font-bold mb-8 text-gray-900 border-b pb-4">Form Update Aktivitas Kapal</h2>

        <form action="{{ route('kapal.update', $kapal->id) }}" method="POST" class="space-y-7">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Input Data</label>
                <input type="date" name="tanggal_input" value="{{ $kapal->tanggal_input }}" required
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 py-2.5">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kapal</label>
                    <input type="text" name="nama_kapal" value="{{ $kapal->nama_kapal }}" required
                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 py-2.5">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Qty Muatan</label>
                    <input type="number" name="qty" value="{{ $kapal->qty }}" required
                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 py-2.5">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Agen</label>
                    <input type="text" name="agen" value="{{ $kapal->agen }}" required
                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 py-2.5">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2 pt-6">Jenis Muatan</label>
                <select name="jenis_muatan" required
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 bg-white font-medium text-gray-700 transition py-2.5">
                    <option value="" disabled>-- Pilih Jenis Muatan --</option>
                    <option value="Dynamik 50 Kg" {{ $kapal->jenis_muatan == 'Dynamik 50 Kg' ? 'selected' : '' }}>
                        Dynamik 50 Kg</option>
                    <option value="Semen OPC Bag 50 Kg"
                        {{ $kapal->jenis_muatan == 'Semen OPC Bag 50 Kg' ? 'selected' : '' }}>Semen OPC Bag 50 Kg
                    </option>
                    <option value="Semen PCC Bag 50 Kg"
                        {{ $kapal->jenis_muatan == 'Semen PCC Bag 50 Kg' ? 'selected' : '' }}>Semen PCC Bag 50 Kg
                    </option>
                    <option value="Semen Curah (Bulk Cement)"
                        {{ $kapal->jenis_muatan == 'Semen Curah (Bulk Cement)' ? 'selected' : '' }}>Semen Curah (Bulk
                        Cement)</option>
                    <option value="Clinker (Terak)" {{ $kapal->jenis_muatan == 'Clinker (Terak)' ? 'selected' : '' }}>
                        Clinker (Terak)</option>
                </select>
            </div>

            <div class="pt-4 pb-2 my-2">
                <div class="w-full border-t border-gray-200 mb-4"></div>

                <div class="flex items-center gap-2">
                    <div class="w-2 h-5 bg-red-600 rounded-sm"></div>
                    <h3 class="text-sm font-extrabold text-red-700 uppercase tracking-wider">
                        Wajib Diisi: Waktu & Jam Operasional Kapal
                    </h3>
                </div>
                <p class="text-xs text-gray-400 mt-1 pl-4">
                    Pastikan tanggal dan jam di bawah ini diinput dengan teliti sesuai kondisi riil di pelabuhan.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 pt-6 border-t border-gray-100">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Sandar</label>
                    <input type="datetime-local" name="tanggal_sandar"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Muat</label>
                    <input type="datetime-local" name="tanggal_muat"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                    <input type="datetime-local" name="tanggal_selesai"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2 pt-6">Status Operasional</label>
                <select name="status" required
                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 bg-white font-medium text-gray-700 transition py-2.5">
                    <option value="Menunggu Sandar" {{ $kapal->status == 'Menunggu Sandar' ? 'selected' : '' }}>
                        Menunggu Sandar</option>
                    <option value="Sedang Muat" {{ $kapal->status == 'Sedang Muat' ? 'selected' : '' }}>Sedang Muat
                    </option>
                    <option value="Selesai" {{ $kapal->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                <a href="{{ route('dashboard') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium transition">Batal</a>
                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm transition">Perbarui
                    Data</button>
            </div>
        </form>
    </div>
</body>

</html>
