@props([
    'ticket' => null,
    'attractions' => [],
    'ticketTypes' => [],
])

<div class="form-group mb-3">
    <label for="tourist_attraction_id">Attraction</label>

    <select
        name="tourist_attraction_id"
        id="tourist_attraction_id"
        class="form-control"
        required
    >
        <option value="">Select Attraction</option>

        @foreach($attractions as $attraction)
            <option
                value="{{ $attraction->id }}"
                @selected(
                    old(
                        'tourist_attraction_id',
                        $ticket?->tourist_attraction_id
                    ) == $attraction->id
                )
            >
                {{ $attraction->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group mb-3">
    <label for="name">Ticket Name</label>

    <input
        type="text"
        name="name"
        id="name"
        class="form-control"
        value="{{ old('name', $ticket?->name) }}"
        required
    >
</div>

<div class="form-group mb-3">
    <label for="ticket_type">Ticket Type</label>

    <select
        name="ticket_type"
        id="ticket_type"
        class="form-control"
        required
    >
        @foreach($ticketTypes as $type)
            <option
                value="{{ $type }}"
                @selected(
                    old(
                        'ticket_type',
                        $ticket?->ticket_type
                    ) == $type
                )
            >
                {{ ucfirst(str_replace('_', ' ', $type)) }}
            </option>
        @endforeach
    </select>
</div>

<div class="form-group mb-3">
    <label for="valid_date">Valid Date</label>

    <input
        type="date"
        name="valid_date"
        id="valid_date"
        class="form-control"
        value="{{ old('valid_date', optional($ticket?->valid_date)->format('Y-m-d')) }}"
    >
</div>

<div class="form-group mb-3">
    <label for="price">Price</label>

    <input
        type="number"
        name="price"
        id="price"
        class="form-control"
        value="{{ old('price', $ticket?->price) }}"
        required
    >
</div>

<div class="form-group mb-3">
    <label for="quota">Quota</label>

    <input
        type="number"
        name="quota"
        id="quota"
        class="form-control"
        value="{{ old('quota', $ticket?->quota) }}"
        required
    >
</div>

<div class="form-group mb-3">
    <label for="description">Description</label>

    <textarea
        name="description"
        id="description"
        class="form-control"
        rows="4"
    >{{ old('description', $ticket?->description) }}</textarea>
</div>

<div class="form-group mb-4">
    <div class="form-check">
        <input
            type="checkbox"
            name="is_active"
            id="is_active"
            class="form-check-input"
            value="1"
            @checked(
                old(
                    'is_active',
                    $ticket?->is_active ?? true
                )
            )
        >

        <label
            class="form-check-label"
            for="is_active"
        >
            Active Ticket
        </label>
    </div>
</div>