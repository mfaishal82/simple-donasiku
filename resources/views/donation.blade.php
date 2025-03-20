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
<body class="bg-gray-900 text-white flex items-center justify-center min-h-screen p-6">

    <div class="relative bg-gray-800 text-white p-8 rounded-lg shadow-lg w-full max-w-2xl">
        <button onclick="window.location.href='{{ url('/dashboard') }}'"
            class="absolute top-4 right-4 text-red-500 hover:text-red-700 text-xl">
            ✖
        </button>

        <h2 class="text-lg font-semibold">
            Kamu akan mengirimkan dukungan pada <span class="italic text-indigo-400">{{ $user->username }}</span>
        </h2>
        <p class="text-sm text-gray-400 mb-6">Silahkan lengkapi data ini</p>

        <form action="{{ route('donations.store') }}" method="post" class="space-y-4">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">

            @if ($errors->any())
                <div class="bg-red-500 text-white p-3 rounded-md">
                    <strong>Terjadi kesalahan!</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <label for="name" class="block text-sm font-medium">Nama</label>
                <input type="text" name="name" id="name" required
                    class="w-full p-2 bg-gray-700 border border-gray-600 rounded-md"
                    value="{{ auth()->check() ? auth()->user()->name : old('name') }}" 
                    @if(auth()->check()) readonly @endif>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium">Email</label>
                <input type="email" name="email" id="email" required
                    class="w-full p-2 bg-gray-700 border border-gray-600 rounded-md"
                    value="{{ auth()->check() ? auth()->user()->email : old('email') }}" 
                    @if(auth()->check()) readonly @endif>
            </div>

            <div>
                <label for="amount" class="block text-sm font-medium">Nominal</label>
                <input type="number" min="100" name="amount" id="amount" required
                    class="w-full p-2 bg-gray-700 border border-gray-600 rounded-md">
            </div>

            <div>
                <label for="message" class="block text-sm font-medium">Pesan</label>
                <textarea name="message" id="message" required
                    class="w-full p-2 bg-gray-700 border border-gray-600 rounded-md"></textarea>
            </div>

            <div class="flex justify-between items-center mt-4">
                <a href="{{ url('/dashboard') }}"
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-md">
                    Kembali
                </a>

                <button type="submit"
                    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-md">
                    Kirim
                </button>
            </div>
        </form>
    </div>

</body>
</html>
