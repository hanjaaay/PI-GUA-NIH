@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">System Backups</h1>
        <form action="{{ route('admin.backups.create') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Create New Backup
            </button>
        </form>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($backups as $backup)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $backup['name'] }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($backup['size'] / 1024 / 1024, 2) }} MB
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $backup['created_at']->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-3">
                                <a href="{{ route('admin.backups.download', $backup['name']) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    Download
                                </a>
                                <form action="{{ route('admin.backups.restore') }}" method="POST" class="inline">
                                    @csrf
                                    <input type="hidden" name="backup_path" value="{{ $backup['path'] }}">
                                    <button type="submit" 
                                            class="text-green-600 hover:text-green-900"
                                            onclick="return confirm('Are you sure you want to restore this backup? This will overwrite current data.')">
                                        Restore
                                    </button>
                                </form>
                                <form action="{{ route('admin.backups.destroy', $backup['name']) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Are you sure you want to delete this backup?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                            No backups found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Backup Information</h2>
        <div class="space-y-4">
            <p class="text-gray-600">
                <strong>Storage Location:</strong> {{ storage_path('app/backups') }}
            </p>
            <p class="text-gray-600">
                <strong>Maximum Backups:</strong> {{ config('backup.max_backups', 5) }}
            </p>
            <p class="text-gray-600">
                <strong>Backup Contents:</strong>
                <ul class="list-disc list-inside mt-2">
                    <li>Database (MySQL dump)</li>
                    <li>Storage files (public storage)</li>
                    <li>Upload files (public/uploads)</li>
                </ul>
            </p>
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            Please ensure you have sufficient disk space before creating backups. 
                            It is recommended to store backups in a secure off-site location.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 