@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ticket Management</h1>
        <a href="{{ route('admin.tickets.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Create New Ticket
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Attraction</th>
                            <th>Type</th>
                            <th>Price</th>
                            <th>Quota</th>
                            <th>Valid Period</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                        <tr>
                            <td>{{ $ticket->id }}</td>
                            <td>{{ $ticket->name }}</td>
                            <td>{{ $ticket->touristAttraction->name }}</td>
                            <td>
                                <span class="badge badge-info">
                                    {{ ucfirst($ticket->type) }}
                                </span>
                            </td>
                            <td>Rp {{ number_format($ticket->price, 0, ',', '.') }}</td>
                            <td>{{ $ticket->quota }}</td>
                            <td>
                                {{ $ticket->valid_from->format('d M Y') }} -
                                {{ $ticket->valid_until->format('d M Y') }}
                            </td>
                            <td>
                                <span class="badge badge-{{ $ticket->is_active ? 'success' : 'danger' }}">
                                    {{ $ticket->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.tickets.show', $ticket) }}" 
                                       class="btn btn-sm btn-info" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.tickets.edit', $ticket) }}" 
                                       class="btn btn-sm btn-primary" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="{{ route('admin.tickets.download-qr', $ticket) }}" 
                                       class="btn btn-sm btn-secondary" 
                                       title="Download QR Code">
                                        <i class="fas fa-qrcode"></i>
                                    </a>
                                    <form action="{{ route('admin.tickets.toggle-status', $ticket) }}" 
                                          method="POST" 
                                          class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="btn btn-sm btn-{{ $ticket->is_active ? 'warning' : 'success' }}"
                                                title="{{ $ticket->is_active ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $ticket->is_active ? 'ban' : 'check' }}"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.tickets.destroy', $ticket) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="btn btn-sm btn-danger"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 