<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Festigo</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">

    <div class="container">

        <a class="navbar-brand fw-bold"
           href="{{ route('home') }}">

            Festigo

        </a>

        <div class="d-flex align-items-center gap-3">

            @auth

                <a href="{{ route('bookings.index') }}"
                   class="text-decoration-none text-dark">

                    My Bookings

                </a>

                <form action="{{ route('logout') }}"
                      method="POST">

                    @csrf

                    <button class="btn btn-danger btn-sm">

                        Logout

                    </button>

                </form>

            @else

                <a href="{{ route('login') }}"
                   class="btn btn-primary btn-sm">

                    Login

                </a>

            @endauth

        </div>
    </div>
</nav>

<main>

    @yield('content')

</main>

</body>
</html>