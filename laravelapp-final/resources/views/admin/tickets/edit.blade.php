@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Ticket</h1>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST">
                @csrf
                @method('PUT')
                
                <x-ticket-form 
                    :ticket="$ticket"
                    :attractions="$attractions"
                    :ticketTypes="$ticketTypes"
                />

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 