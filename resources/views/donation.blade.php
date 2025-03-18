<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans">
        <div class="min-h-screen">
            <div class="flex min-h-screen items-center bg-gray-100 justify-center p-4">
                <div class="container mx-auto max-w-2xl  rounded-lg p-8">
                    <form action="{{ route('donations.store') }}" method="post" class="w-full">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                        @if ($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <strong class="font-bold">Terjadi kesalahan!</strong>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>

                            </div>
                        @endif
                        <div class="space-y-2">
                            <div class="border-b border-gray-900/10 pb-12">
                                <div class="flex flex-col w-full justify-between items-start gap-2">
                                    <div class="flex justify-between gap-x-5 items-center w-full">
                                        <h2 class="text-base font-semibold text-gray-900">
                                            Kamu akan mengirimkan dukungan pada {{ $user->username }}
                                        </h2>

                                        @guest
                                            <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-sm font-semibold text-blue-500 shadow-sm">
                                                Log in
                                            </a>
                                        @endguest
                                    </div>
                                    <p class="text-sm text-gray-500">Silahkan lengkapi data ini</p>
                                    <div class="mt-7 w-full grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                                        <div class="sm:col-span-full">
                                            <label for="name" class="block text-sm font-medium text-gray-900">Nama</label>
                                            <input type="text" name="name" id="name" required placeholder="Nama" 
                                                class="block w-full rounded-md border-b py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                value="{{ auth()->check() ? auth()->user()->name : old('name') }}" 
                                                @if(auth()->check()) readonly @endif  
                                            />
                                        </div>
                                        <div class="sm:col-span-full">
                                            <label for="email" class="block text-sm font-medium text-gray-900">Email</label>
                                            <input type="text" name="email" id="email" required placeholder="Email" 
                                                class="block w-full rounded-md border-b py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                                value="{{ auth()->check() ? auth()->user()->email : old('email') }}" 
                                                @if(auth()->check()) readonly @endif  
                                            />
                                        </div>
                                        <div class="sm:col-span-full">
                                            <label for="amount" class="block text-sm font-medium text-gray-900">Nominal</label>
                                            <input type="number" min="100" name="amount" id="amount" required placeholder="Nominal" 
                                                class="block w-full rounded-md border-b py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" value="9999"
                                            />
                                        </div>
                                        <div class="sm:col-span-full">
                                            <label for="message" class="block text-sm font-medium text-gray-900">Pesan</label>
                                            <textarea min="1000" name="message" id="message" required placeholder="Pesan" 
                                                class="block w-full rounded-md border-b py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" value="test"
                                            ></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end items-center gap-4">
                            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-700">
                                Kirim
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </body>
</html>
