@extends('layouts.auth')

@section('content')

{{-- TITLE --}}
<div class="text-center mb-5">

    <h1 class="auth-title">

        Welcome Back

    </h1>

    <p class="auth-subtitle">

        Login to access your tickets,
        bookings, and upcoming experiences.

    </p>

</div>

{{-- SESSION STATUS --}}
@if (session('status'))

    <div
        class="alert alert-success border-0 rounded-4 mb-4">

        {{ session('status') }}

    </div>

@endif

{{-- FORM --}}
<form
    method="POST"
    action="{{ route('login') }}">

    @csrf

    {{-- EMAIL --}}
    <div class="mb-4">

        <label
            for="email"
            class="auth-label">

            Email Address

        </label>

        <input
            id="email"
            type="email"
            name="email"
            value="{{ old('email') }}"
            required
            autofocus
            autocomplete="username"
            class="auth-input"
            placeholder="Enter your email">

        @error('email')

            <div class="auth-error">

                {{ $message }}

            </div>

        @enderror

    </div>

    {{-- PASSWORD --}}
    <div class="mb-4">

        <label
            for="password"
            class="auth-label">

            Password

        </label>

        <input
            id="password"
            type="password"
            name="password"
            required
            autocomplete="current-password"
            class="auth-input"
            placeholder="Enter your password">

        @error('password')

            <div class="auth-error">

                {{ $message }}

            </div>

        @enderror

    </div>

    {{-- REMEMBER --}}
    <div
        class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

        <div class="form-check">

            <input
                class="form-check-input"
                type="checkbox"
                name="remember"
                id="remember">

            <label
                class="form-check-label text-secondary"
                for="remember">

                Remember me

            </label>

        </div>

        @if (Route::has('password.request'))

            <a
                href="{{ route('password.request') }}"
                class="auth-link">

                Forgot password?

            </a>

        @endif

    </div>

    {{-- BUTTON --}}
    <button
        type="submit"
        class="btn-auth">

        Login to Festigo

    </button>

    {{-- REGISTER --}}
    <div
        class="text-center mt-4">

        <span class="text-secondary">

            Don’t have an account?

        </span>

        <a
            href="{{ route('register') }}"
            class="auth-link fw-semibold">

            Create account

        </a>

    </div>

</form>

@endsection