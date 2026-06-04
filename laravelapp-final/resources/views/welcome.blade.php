<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    {{-- CSS hasil build Vite (agar tidak perlu `npm run dev`) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 text-gray-900">

    {{-- Navbar --}}
    <nav class="flex justify-between items-center p-4 bg-white shadow">
        <a href="{{ route('home') }}" class="text-lg font-bold">
            {{ config('app.name', 'MyApp') }}
        </a>

        <div class="space-x-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="text-blue-600 font-medium">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-red-500">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-blue-600">Login</a>
                <a href="{{ route('register') }}" class="text-green-600">Register</a>
            @endauth
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="flex flex-col items-center justify-center text-center py-20 bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
        <h1 class="text-4xl font-extrabold mb-4">Selamat Datang di {{ config('app.name', 'MyApp') }}</h1>
        <p class="max-w-xl mb-6">Website booking tiket yang cepat, aman, dan mudah digunakan.</p>
        <a href="#tiket" class="px-6 py-3 bg-white text-indigo-600 font-semibold rounded-lg shadow hover:bg-gray-200">
            Lihat Tiket
        </a>
    </section>

    {{-- Content Section --}}
    <section id="tiket" class="py-16 px-6 max-w-6xl mx-auto">
        <h2 class="text-2xl font-bold mb-6 text-center">Event Populer</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-semibold text-lg mb-2">Konser Coldplay</h3>
                <p class="text-gray-600 mb-4">Nikmati pengalaman konser internasional.</p>
                <a href="#" class="text-blue-600 font-medium">Pesan Sekarang</a>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-semibold text-lg mb-2">Festival Jazz</h3>
                <p class="text-gray-600 mb-4">Temukan musisi jazz terbaik dunia.</p>
                <a href="#" class="text-blue-600 font-medium">Pesan Sekarang</a>
            </div>
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="font-semibold text-lg mb-2">Teater Musikal</h3>
                <p class="text-gray-600 mb-4">Drama dan musik dalam satu panggung megah.</p>
                <a href="#" class="text-blue-600 font-medium">Pesan Sekarang</a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-100 text-center py-6 mt-10 text-sm text-gray-500">
        &copy; {{ date('Y') }} {{ config('app.name', 'MyApp') }}. All rights reserved.
    </footer>
</body>
</html>
