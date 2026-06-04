@extends('layouts.app')

@section('content')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            @if($user->avatar)
                                <img class="h-16 w-16 rounded-full" src="{{ asset('storage/avatars/' . $user->avatar) }}" alt="{{ $user->name }}">
                            @else
                                <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-2xl text-gray-500">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            <p class="text-sm text-gray-500">{{ $user->phone }}</p>
                            <p class="text-sm text-gray-500">{{ $user->address }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@endsection 