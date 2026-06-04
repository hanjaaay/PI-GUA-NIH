@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Ticket</h1>
        <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.tickets.store') }}" method="POST">
                @csrf
                
                <x-ticket-form 
                    :attractions="$attractions"
                    :ticketTypes="$ticketTypes"
                />

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 