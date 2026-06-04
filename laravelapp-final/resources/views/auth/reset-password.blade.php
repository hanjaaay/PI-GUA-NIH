@extends('layouts.auth')

@section('content')

{{-- TITLE --}}
<div class="text-center mb-5">

    <h1 class="auth-title">

        Reset Password

    </h1>

    <p class="auth-subtitle">

        Create a new secure password
        for your Festigo account.

    </p>

</div>

{{-- FORM --}}
<form
    method="POST"
    action="{{ route('password.store') }}">

    @csrf

    {{-- TOKEN --}}
    <input
        type="hidden"
        name="token"
        value="{{ $request->route('token') }}">

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
            value="{{ old('email', $request->email) }}"
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

            New Password

        </label>

        <input
            id="password"
            type="password"
            name="password"
            required
            autocomplete="new-password"
            class="auth-input"
            placeholder="Create new password">

        @error('password')

            <div class="auth-error">

                {{ $message }}

            </div>

        @enderror

    </div>

    {{-- CONFIRM --}}
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

        Reset Password

    </button>

    {{-- LOGIN --}}
    <div
        class="text-center mt-4">

        <a
            href="{{ route('login') }}"
            class="auth-link fw-semibold">

            Back to login

        </a>

    </div>

</form>

@endsection