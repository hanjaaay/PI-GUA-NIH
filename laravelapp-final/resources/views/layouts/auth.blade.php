<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token"
          content="{{ csrf_token() }}">

    <title>Festigo Auth</title>

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

    <style>

        * {

            font-family:
                'Inter',
                sans-serif;
        }

        body {

            min-height:
                100vh;

            background:
                #050505;

            overflow-x:
                hidden;

            color:
                white;

            position:
                relative;
        }

        

        .auth-glow {

            position:
                fixed;

            width:
                450px;

            height:
                450px;

            border-radius:
                999px;

            filter:
                blur(120px);

            pointer-events:
                none;

            z-index:
                0;
        }

        

        .auth-wrapper {

            min-height:
                100vh;

            display:
                flex;

            align-items:
                center;

            justify-content:
                center;

            position:
                relative;

            z-index:
                2;

            padding:
                40px 20px;
        }

        

        .auth-card {

            width:
                100%;

            max-width:
                560px;

            background:
                rgba(255,255,255,0.05);

            border:
                1px solid rgba(255,255,255,0.08);

            backdrop-filter:
                blur(24px);

            border-radius:
                36px;

            padding:
                50px;
        }

        

        .auth-logo {

            height:
                64px;

            width:
                auto;

            object-fit:
                contain;
        }

        

        .auth-title {

            font-size:
                clamp(2rem,5vw,3rem);

            font-weight:
                900;

            line-height:
                1;

            margin-bottom:
                16px;

            letter-spacing:
                -2px;
        }

        .auth-subtitle {

            color:
                #9ca3af;

            line-height:
                1.9;

            margin-bottom:
                40px;
        }

        

        .auth-label {

            font-size:
                14px;

            margin-bottom:
                10px;

            color:
                #d1d5db;

            font-weight:
                600;
        }

        .auth-input {

            width:
                100%;

            background:
                rgba(255,255,255,0.05);

            border:
                1px solid rgba(255,255,255,0.08);

            border-radius:
                18px;

            padding:
                16px 18px;

            color:
                white;

            outline:
                none;

            transition:
                0.3s ease;
        }

        .auth-input:focus {

            border-color:
                #6d5dfc;

            box-shadow:
                0 0 0 4px rgba(109,93,252,0.12);
        }

        .auth-input::placeholder {

            color:
                #6b7280;
        }

        

        .btn-auth {

            width:
                100%;

            border:
                none;

            border-radius:
                18px;

            padding:
                16px;

            font-weight:
                700;

            color:
                white;

            background:
                linear-gradient(
                    135deg,
                    #6d5dfc,
                    #4f46e5
                );

            transition:
                0.3s ease;
        }

        .btn-auth:hover {

            transform:
                translateY(-2px);

            box-shadow:
                0 18px 40px rgba(109,93,252,0.35);
        }

        

        .auth-link {

            color:
                #9ca3af;

            text-decoration:
                none;

            transition:
                0.3s ease;
        }

        .auth-link:hover {

            color:
                white;
        }

        

        .auth-error {

            color:
                #f87171;

            font-size:
                14px;

            margin-top:
                10px;
        }

        

        @media(max-width:768px) {

            .auth-card {

                padding:
                    32px 24px;

                border-radius:
                    28px;
            }

            .auth-logo {

                height:
                    52px;
            }
        }

    </style>

</head>

<body>

    {{-- GLOW --}}
    <div
        class="auth-glow"
        style="
            top:-120px;
            left:-120px;
            background:rgba(109,93,252,0.18);
        ">
    </div>

    <div
        class="auth-glow"
        style="
            bottom:-120px;
            right:-120px;
            background:rgba(124,58,237,0.14);
        ">
    </div>

    {{-- WRAPPER --}}
    <div class="auth-wrapper">

        <div class="auth-card">

            {{-- LOGO --}}
            <div class="text-center mb-5">

                <a href="{{ route('home') }}">

                    <img
                        src="{{ asset('images/festigo-logo.png') }}"
                        class="auth-logo"
                        alt="Festigo">

                </a>

            </div>

            {{-- CONTENT --}}
            @yield('content')

        </div>

    </div>

</body>

</html>