@extends('layouts.auth')

@section('content')

{{-- TITLE --}}
<div class="text-center mb-5">

    <h1 class="auth-title">

        Verify Your Email

    </h1>

    <p class="auth-subtitle">

        Thanks for joining Festigo.
        Please verify your email address
        before continuing.

    </p>

</div>

{{-- SUCCESS --}}
@if (session('status') == 'verification-link-sent')

    <div
        class="alert alert-success border-0 rounded-4 mb-4">

        A new verification link has been
        sent to your email address.

    </div>

@endif

{{-- VERIFY FORM --}}
<form
    method="POST"
    action="{{ route('verification.send') }}">

    @csrf

    <button
        type="submit"
        class="btn-auth">

        Resend Verification Email

    </button>

</form>

{{-- LOGOUT --}}
<form
    method="POST"
    action="{{ route('logout') }}"
    class="mt-4">

    @csrf

    <button
        type="submit"
        class="btn btn-link auth-link text-decoration-none w-100">

        Logout

    </button>

</form>

@endsection