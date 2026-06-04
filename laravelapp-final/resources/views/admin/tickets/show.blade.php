@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Ticket Details</h1>
        <div>
            <a href="{{ route('admin.tickets.edit', $ticket) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Ticket
            </a>
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Ticket Information -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Ticket Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Name</dt>
                                <dd class="col-sm-8">{{ $ticket->name }}</dd>

                                <dt class="col-sm-4">Type</dt>
                                <dd class="col-sm-8">
                                    <span class="badge badge-info">
                                        {{ ucfirst($ticket->type) }}
                                    </span>
                                </dd>

                                <dt class="col-sm-4">Price</dt>
                                <dd class="col-sm-8">Rp {{ number_format($ticket->price, 0, ',', '.') }}</dd>

                                <dt class="col-sm-4">Quota</dt>
                                <dd class="col-sm-8">{{ $ticket->quota }}</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Attraction</dt>
                                <dd class="col-sm-8">{{ $ticket->touristAttraction->name }}</dd>

                                <dt class="col-sm-4">Valid From</dt>
                                <dd class="col-sm-8">{{ $ticket->valid_from->format('d M Y H:i') }}</dd>

                                <dt class="col-sm-4">Valid Until</dt>
                                <dd class="col-sm-8">{{ $ticket->valid_until->format('d M Y H:i') }}</dd>

                                <dt class="col-sm-4">Status</dt>
                                <dd class="col-sm-8">
                                    <span class="badge badge-{{ $ticket->is_active ? 'success' : 'danger' }}">
                                        {{ $ticket->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </dd>
                            </dl>
                        </div>
                    </div>

                    @if($ticket->description)
                    <div class="mt-4">
                        <h6 class="font-weight-bold">Description</h6>
                        <p>{{ $ticket->description }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Booking History -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Booking History</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Customer</th>
                                    <th>Quantity</th>
                                    <th>Total Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ticket->bookings as $booking)
                                <tr>
                                    <td>{{ $booking->id }}</td>
                                    <td>{{ $booking->user->name }}</td>
                                    <td>{{ $booking->quantity }}</td>
                                    <td>Rp {{ number_format($booking->total_amount, 0, ',', '.') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $booking->status === 'completed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No bookings found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">QR Code</h6>
                </div>
                <div class="card-body text-center">
                    @if($ticket->qr_code)
                        <img src="{{ asset('storage/qrcodes/tickets/' . $ticket->qr_code . '.png') }}" 
                             alt="Ticket QR Code" 
                             class="img-fluid mb-3">
                        <p class="text-muted">{{ $ticket->qr_code }}</p>
                        <a href="{{ route('admin.tickets.download-qr', $ticket) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-download"></i> Download QR Code
                        </a>
                    @else
                        <p class="text-muted">No QR code generated yet</p>
                        <form action="{{ route('admin.tickets.generate-qr', $ticket) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-qrcode"></i> Generate QR Code
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <form action="{{ route('admin.tickets.toggle-status', $ticket) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-{{ $ticket->is_active ? 'warning' : 'success' }} btn-block">
                                <i class="fas fa-{{ $ticket->is_active ? 'ban' : 'check' }}"></i>
                                {{ $ticket->is_active ? 'Deactivate' : 'Activate' }} Ticket
                            </button>
                        </form>

                        <form action="{{ route('admin.tickets.destroy', $ticket) }}" 
                              method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-block">
                                <i class="fas fa-trash"></i> Delete Ticket
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 