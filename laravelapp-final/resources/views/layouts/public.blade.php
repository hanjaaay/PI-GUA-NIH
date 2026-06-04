<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>Festigo</title>

    {{-- VITE --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    {{-- BOOTSTRAP --}}
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    {{-- ICON --}}
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- FONT --}}
    <link rel="preconnect"
          href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    {{-- AOS --}}
    <link
        href="https://unpkg.com/aos@2.3.4/dist/aos.css"
        rel="stylesheet">

    @stack('styles')

    <style>

        :root {

            --bg-primary:
                #050505;

            --card-bg:
                rgba(255,255,255,0.05);

            --border-color:
                rgba(255,255,255,0.08);

            --gradient-primary:
                linear-gradient(
                    135deg,
                    #6d5dfc 0%,
                    #4f46e5 100%
                );
        }

        * {

            font-family:
                'Inter',
                sans-serif;
        }

        html {

            scroll-behavior:
                smooth;
        }

        body {

            background:
                #050505;

            color:
                white;

            overflow-x:
                hidden;
        }

        /*
        |--------------------------------------------------------------------------
        | NAVBAR
        |--------------------------------------------------------------------------
        */

        .festigo-navbar {

            position:
                sticky;

            isolation:
                isolate;

            top:
                0;

            z-index:
                999;

            background:
                rgba(5,5,5,0.82);

            backdrop-filter:
                blur(20px);

            border-bottom:
                1px solid rgba(255,255,255,0.06);
        }

        .nav-link-custom {

            color:
                #b3b3b3;

            text-decoration:
                none;

            position: relative;
            z-index: 1001;

            font-weight:
                500;

            transition:
                0.3s ease;
        }

        .nav-link-custom:hover {

            color:
                white;
        }

        .btn-festigo {

            background:
                var(--gradient-primary);

            border:
                none;

            color:
                white;

            padding:
                12px 22px;

            border-radius:
                14px;

            text-decoration:
                none;

            font-weight:
                600;

            transition:
                0.3s ease;
        }

        .btn-festigo:hover {

            transform:
                translateY(-2px);

            color:
                white;

            box-shadow:
                0 10px 30px rgba(109,93,252,0.4);
        }

        /*
        |--------------------------------------------------------------------------
        | LOGO
        |--------------------------------------------------------------------------
        */

        .festigo-logo {

            height:
                42px;

            width:
                auto;

            object-fit:
                contain;
        }

        /*
        |--------------------------------------------------------------------------
        | GLASS
        |--------------------------------------------------------------------------
        */

        .glass-card {

            background:
                rgba(255,255,255,0.05);

            border:
                1px solid rgba(255,255,255,0.06);

            backdrop-filter:
                blur(20px);

            border-radius:
                24px;
        }

        /*
        |--------------------------------------------------------------------------
        | GLOW
        |--------------------------------------------------------------------------
        */

        .hero-glow {

            position:
                fixed;

            width:
                500px;

            height:
                500px;

            border-radius:
                999px;

            filter:
                blur(120px);

            z-index:
                0;

            pointer-events:
                none;
        }

        /*
        |--------------------------------------------------------------------------
        | MOBILE MENU
        |--------------------------------------------------------------------------
        */

        .mobile-menu-btn {

            width:
                48px;

            height:
                48px;

            border:
                1px solid rgba(255,255,255,0.08);

            background:
                rgba(255,255,255,0.04);

            border-radius:
                14px;

            color:
                white;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            font-size:
                20px;
        }

        .mobile-menu {

            position:
                fixed;

            top:
                0;

            right:
                -100%;

            width:
                280px;

            height:
                100vh;

            background:
                rgba(10,10,10,0.96);

            backdrop-filter:
                blur(24px);

            border-left:
                1px solid rgba(255,255,255,0.06);

            z-index:
                9999;

            padding:
                30px;

            transition:
                0.35s ease;
        }

        .mobile-menu.active {

            right:
                0;
        }

        .mobile-link {

            display:
                block;

            color:
                white;

            text-decoration:
                none;

            padding:
                16px 0;

            border-bottom:
                1px solid rgba(255,255,255,0.06);

            font-weight:
                600;
        }

        .mobile-overlay {

            position:
                fixed;

            inset:
                0;

            background:
                rgba(0,0,0,0.5);

            z-index:
                9998;

            opacity:
                0;

            visibility:
                hidden;

            transition:
                0.3s ease;
        }

        .mobile-overlay.active {

            opacity:
                1;

            visibility:
                visible;
        }

        /*
        |--------------------------------------------------------------------------
        | SCROLLBAR
        |--------------------------------------------------------------------------
        */

        ::-webkit-scrollbar {

            width:
                10px;
        }

        ::-webkit-scrollbar-thumb {

            background:
                #6d5dfc;

            border-radius:
                999px;
        }

        /*
        |--------------------------------------------------------------------------
        | RESPONSIVE
        |--------------------------------------------------------------------------
        */

        @media(max-width:991px) {

            .hero-glow {

                width:
                    260px;

                height:
                    260px;
            }

            .container {

                padding-left:
                    20px;

                padding-right:
                    20px;
            }
        }

        @media(max-width:768px) {

            .btn-festigo {

                position: relative;
                z-index: 1001;

                padding:
                    11px 18px;

                font-size:
                    14px;
            }

            .festigo-logo {

                height:
                    36px;
            }

            .glass-card {

                border-radius:
                    20px;
            }
        }

    </style>

</head>

<body>

    {{-- GLOW --}}
    <div class="hero-glow"
         style="
            top:-200px;
            left:-100px;
            background:rgba(109,93,252,0.18);
         ">
    </div>

    <div class="hero-glow"
         style="
            bottom:-200px;
            right:-100px;
            background:rgba(124,58,237,0.14);
         ">
    </div>

    {{-- MOBILE OVERLAY --}}
    <div
        class="mobile-overlay"
        id="mobileOverlay"
        onclick="closeMenu()">
    </div>

    {{-- MOBILE MENU --}}
    <div
        class="mobile-menu"
        id="mobileMenu">

        <div
            class="d-flex justify-content-between align-items-center mb-5">

            <img
                src="{{ asset('images/festigo-logo.png') }}"
                class="festigo-logo">

            <button
                onclick="closeMenu()"
                class="mobile-menu-btn">

                <i class="bi bi-x-lg"></i>

            </button>

        </div>

        @auth

            <a href="{{ route('bookings.index') }}"
               class="mobile-link">

                My Tickets

            </a>


            <form
                action="{{ route('logout') }}"
                method="POST"
                class="mt-4">

                @csrf

                <button
                    type="submit"
                    class="btn-festigo w-100">

                    Logout

                </button>

            </form>

        @else

            <a href="{{ route('login') }}"
               class="btn-festigo w-100 mt-4 text-center">

                Login

            </a>

        @endauth

    </div>

    {{-- NAVBAR --}}
    <nav class="festigo-navbar">

        <div class="container">

            <div class="d-flex justify-content-between align-items-center py-3">

                {{-- LEFT --}}
                <div class="d-flex align-items-center gap-5">

                    <a href="{{ route('home') }}">

                        <img
                            src="{{ asset('images/festigo-logo.png') }}"
                            alt="Festigo"
                            class="festigo-logo">

                    </a>

                    @auth
                    {{-- DESKTOP MENU --}}
                    <div
                        class="d-none d-lg-flex align-items-center gap-4">

                        <a href="{{ route('bookings.index') }}"
                           class="nav-link-custom">

                            My Tickets

                        </a>

                    </div>
                    @endauth

                </div>

                {{-- RIGHT --}}
                <div class="d-flex align-items-center gap-3">

                    {{-- DESKTOP --}}
                    <div
                        class="d-none d-lg-flex align-items-center gap-3">

                        @auth

    <form
        action="{{ route('logout') }}"
        method="POST">

        @csrf

        <button
            type="submit"
            class="btn-festigo">

            Logout

        </button>

    </form>

@else

    <a href="{{ route('login') }}"
       class="btn-festigo">

        Login

    </a>

@endauth

                    </div>

                    {{-- MOBILE --}}
                    <button
                        class="mobile-menu-btn d-lg-none"
                        onclick="openMenu()">

                        <i class="bi bi-list"></i>

                    </button>

                </div>

            </div>

        </div>

    </nav>

    {{-- CONTENT --}}
    <main
        style="
            position:relative;
            z-index:2;
        ">

        @yield('content')

    </main>

    {{-- BOOTSTRAP --}}
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    {{-- AOS --}}
    <script
        src="https://unpkg.com/aos@2.3.4/dist/aos.js">
    </script>

    <script>

        AOS.init({

            once: true,
            duration: 800
        });

        function openMenu() {

            document
                .getElementById('mobileMenu')
                .classList.add('active');

            document
                .getElementById('mobileOverlay')
                .classList.add('active');
        }

        function closeMenu() {

            document
                .getElementById('mobileMenu')
                .classList.remove('active');

            document
                .getElementById('mobileOverlay')
                .classList.remove('active');
        }

    </script>

    @stack('scripts')

</body>

</html>
