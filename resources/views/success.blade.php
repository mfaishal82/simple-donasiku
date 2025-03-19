<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>DonasiKu</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen">
            <div class="flex min-h-screen items-center bg-gray-100  p-4">
                <div class="container flex flex-col items-center justify-center gap-3 mx-auto max-w-2xl  rounded-lg p-8">
                    <h1>Terima kasih atas donasi anda!</h1>
                    <p>Donasi untuk <span class="italic">{{ $donation->user->username }}</span> telah berhasil.</p>
                    <a href="{{ url('/') }}" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Kembali ke beranda
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>
