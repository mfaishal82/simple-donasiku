<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DonasiKu - Sukses</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen p-6">

    <div class="relative bg-gray-800 text-white p-8 rounded-lg shadow-lg w-full max-w-md text-center">
        <!-- Tombol Silang (X) -->
        <button onclick="window.location.href='{{ url('/') }}'"
            class="absolute top-4 right-4 text-red-500 hover:text-red-700 text-xl">
            ✖
        </button>

        <div class="flex items-center justify-center mb-4">
            <svg class="w-16 h-16 text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h1 class="text-xl font-semibold mb-2">Terima Kasih atas Donasi Anda!</h1>
        <p class="text-gray-300 mb-6">
            Donasi untuk <span class="italic text-indigo-400">{{ $donation->user->username }}</span> telah berhasil.
        </p>

        <a href="{{ url('/') }}" 
            class="inline-block w-full px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white font-semibold rounded-md">
            Kembali ke Beranda
        </a>
    </div>

</body>
</html>
