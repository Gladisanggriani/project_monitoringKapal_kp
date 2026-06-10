<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Monitoring Kapal Dumai</title>
    @vite(['resources/css/app.css'])
    <style>
        .forced-center-card {
            max-width: 420px !important;
            width: 100% !important;
            margin: 0 auto !important;
        }
        /* Mengunci warna tombol murni agar tidak hilang saat compile compile-an Tailwind bermasalah */
        .btn-submit-custom {
            background-color: #b91c1c !important; /* Merah solid red-700 */
            color: #ffffff !important;
            display: block !important;
            width: 100% !important;
            text-align: center;
        }
        .btn-submit-custom:hover {
            background-color: #991b1b !important; /* Merah gelap red-800 */
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center antialiased p-4">

    <div class="forced-center-card bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
        
        <div class="bg-red-800 px-6 py-8 text-center border-b-4 border-red-900">
            <div class="flex justify-center mb-4">
                <img src="https://brandlogovector.com/wp-content/uploads/2021/10/Padang-Cement-Logo.png" 
                     alt="Logo Semen Padang" 
                     class="h-20 w-auto object-contain drop-shadow-md">
            </div>
            
            <h2 class="text-xl font-black text-white tracking-wider uppercase m-0 p-0 block">
                Aplikasi Monitoring
            </h2>
            <span class="text-red-100 text-xs font-bold mt-1 tracking-widest block uppercase">
                PT Semen Padang Unit Pabrik Dumai
            </span>
        </div>

        <form action="{{ route('login') }}" method="POST" class="p-6 space-y-5">
            @csrf

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-600 p-3 rounded-lg text-xs text-red-700 font-semibold">
                    Email atau password yang Anda masukkan keliru.
                </div>
            @endif

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Alamat Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@gmail.com"
                    class="w-full rounded-xl border border-gray-300 shadow-sm focus:border-red-600 focus:ring-0 outline-none py-2.5 px-3.5 text-sm text-gray-900 transition">
            </div>

            <div class="mt-2">
                <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password" required placeholder="••••••••"
                    class="w-full rounded-xl border border-gray-300 shadow-sm focus:border-red-600 focus:ring-0 outline-none py-2.5 px-3.5 text-sm text-gray-900 transition">
            </div>

            <div class="flex items-center pt-1 mt-2 mx-2">
                <input type="checkbox" name="remember" id="remember" 
                    class="h-4 w-4 text-red-600 focus:ring-0 border-gray-300 rounded cursor-pointer">
                <label  for="remember" class="ml-2 block text-xs font-bold text-gray-600 select-none cursor-pointer">
                    Ingat Akun Saya
                </label>
            </div>

            <div class="pt-2 mt-4">
                <button type="submit" 
                    class="btn-submit-custom font-extrabold text-sm py-3 px-4 rounded-xl shadow-md transition-all uppercase tracking-wider cursor-pointer border-none">
                    Masuk Ke Dashboard
                </button>
            </div>
        </form>

        <div class="bg-gray-50 border-t border-gray-100 py-3.5 text-center">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider m-0">
                &copy; {{ date('Y') }} LOGISTIK & DISTRIBUSI DUMAI
            </p>
        </div>
    </div>

</body>
</html>