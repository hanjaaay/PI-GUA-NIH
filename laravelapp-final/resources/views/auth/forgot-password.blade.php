@extends('layouts.auth')

@section('content')

{{-- TITLE --}}
<div class="text-center mb-5">

    <h1 class="auth-title">

        Forgot Password

    </h1>

    <p class="auth-subtitle">

        Enter your email address and
        we’ll send you a password reset link.

    </p>

</div>

{{-- SUCCESS --}}
@if (session('status'))

    <div
        class="alert alert-success border-0 rounded-4 mb-4">

        {{ session('status') }}

    </div>

@endif

{{-- FORM --}}
<form
    method="POST"
    action="{{ route('password.email') }}">

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
            class="auth-input"
            placeholder="Enter your email">

        @error('email')

            <div class="auth-error">

                {{ $message }}

            </div>

        @enderror

    </div>

    {{-- BUTTON --}}
    <button
        type="submit"
        class="btn-auth">

        Send Reset Link

    </button>

    {{-- BACK --}}
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