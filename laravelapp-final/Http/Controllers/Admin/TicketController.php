<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TouristAttraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with('touristAttraction')
            ->latest()
            ->paginate(10);

        return view('admin.tickets.index', compact('tickets'));
    }

    public function create()
    {
        $attractions = TouristAttraction::all();
        $ticketTypes = [
            'regular' => 'Regular',
            'vip' => 'VIP',
            'package' => 'Package',
            'children' => 'Children',
            'senior' => 'Senior Citizen',
            'student' => 'Student',
        ];

        return view('admin.tickets.create', compact('attractions', 'ticketTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tourist_attraction_id' => 'required|exists:tourist_attractions,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $ticket = Ticket::create($validated);

        // Generate QR code
        $qrCode = QrCode::format('png')
            ->size(300)
            ->generate($ticket->generateQrCode());

        // Save QR code image
        $qrPath = 'qrcodes/tickets/'.$ticket->qr_code.'.png';
        Storage::put('public/'.$qrPath, $qrCode);

        return redirect()
            ->route('admin.tickets.index')
            ->with('success', 'Ticket created successfully.');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('touristAttraction', 'bookings');

        return view('admin.tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $attractions = TouristAttraction::all();
        $ticketTypes = [
            'regular' => 'Regular',
            'vip' => 'VIP',
            'package' => 'Package',
            'children' => 'Children',
            'senior' => 'Senior Citizen',
            'student' => 'Student',
        ];

        return view('admin.tickets.edit', compact('ticket', 'attractions', 'ticketTypes'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'tourist_attraction_id' => 'required|exists:tourist_attractions,id',
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'price' => 'required|numeric|min:0',
            'quota' => 'required|integer|min:1',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after:valid_from',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $ticket->update($validated);

        return redirect()
            ->route('admin.tickets.index')
            ->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        // Delete QR code image if exists
        if ($ticket->qr_code) {
            Storage::delete('public/qrcodes/tickets/'.$ticket->qr_code.'.png');
        }

        $ticket->delete();

        return redirect()
            ->route('admin.tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }

    public function toggleStatus(Ticket $ticket)
    {
        $ticket->update(['is_active' => ! $ticket->is_active]);

        return redirect()
            ->route('admin.tickets.index')
            ->with('success', 'Ticket status updated successfully.');
    }

    public function downloadQrCode(Ticket $ticket)
    {
        if (! $ticket->qr_code) {
            return back()->with('error', 'QR code not found.');
        }

        $path = 'public/qrcodes/tickets/'.$ticket->qr_code.'.png';

        if (! Storage::exists($path)) {
            // Generate new QR code if not exists
            $qrCode = QrCode::format('png')
                ->size(300)
                ->generate($ticket->qr_code);

            Storage::put($path, $qrCode);
        }

        return Storage::download($path, 'ticket-qr-'.$ticket->id.'.png');
    }
}
