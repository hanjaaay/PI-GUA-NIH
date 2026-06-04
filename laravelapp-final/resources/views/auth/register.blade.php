@extends('layouts.auth')

@section('content')

{{-- TITLE --}}
<div class="text-center mb-5">

    <h1 class="auth-title">

        Create Account

    </h1>

    <p class="auth-subtitle">

        Join Festigo and start booking
        unforgettable experiences today.

    </p>

</div>

{{-- FORM --}}
<form
    method="POST"
    action="{{ route('register') }}">

    @csrf

    {{-- NAME --}}
    <div class="mb-4">

        <label
            for="name"
            class="auth-label">

            Full Name

        </label>

        <input
            id="name"
            type="text"
            name="name"
            value="{{ old('name') }}"
            required
            autofocus
            autocomplete="name"
            class="auth-input"
            placeholder="Enter your full name">

        @error('name')

            <div class="auth-error">

                {{ $message }}

            </div>

        @enderror

    </div>

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
            autocomplete="new-password"
            class="auth-input"
            placeholder="Create password">

        @error('password')

            <div class="auth-error">

                {{ $message }}

            </div>

        @enderror

    </div>

    {{-- CONFIRM PASSWORD --}}
    <div class="mb-4">

        <label
            for="password_confirmation"
            class="auth-label">

            Confirm Password

        </label>

        <input
            id="password_confirmation"
            type="password"
            name="password_confirmation"
            required
            autocomplete="new-password"
            class="auth-input"
            placeholder="Repeat password">

        @error('password_confirmation')

            <div class="auth-error">

                {{ $message }}

            </div>

        @enderror

    </div>

    {{-- BUTTON --}}
    <button
        type="submit"
        class="btn-auth">

        Create Festigo Account

    </button>

    {{-- LOGIN --}}
    <div
        class="text-center mt-4">

        <span class="text-secondary">

            Already have an account?

        </span>

        <a
            href="{{ route('login') }}"
            class="auth-link fw-semibold">

            Login

        </a>

    </div>

</form>

@endsection