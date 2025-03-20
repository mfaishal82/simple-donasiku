<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>DonasiKu</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-900 text-white flex flex-col items-center justify-center min-h-screen p-6 relative">
        <nav class="absolute top-6 right-6 flex space-x-3 sm:space-x-2 sm:top-4 sm:right-4">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white rounded-md text-sm sm:text-base">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md text-sm sm:text-base">Log in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-800 text-white rounded-md text-sm sm:text-base">Register</a>
                    @endif
                @endauth
            @endif
        </nav>
        <div class="text-center space-y-6 mt-10 px-4 w-full">
            <h1 class="text-3xl sm:text-5xl font-bold">Selamat Datang di DonasiKu</h1>
            <p class="text-base sm:text-lg text-gray-400">Platform donasi mudah dan terpercaya</p>
            <a href="{{ route('login') }}" class="inline-block mt-6 px-6 py-3 bg-green-500 hover:bg-green-600 text-white text-lg rounded-md">Mulai Donasi</a>
        </div>
    </body>
</html>
