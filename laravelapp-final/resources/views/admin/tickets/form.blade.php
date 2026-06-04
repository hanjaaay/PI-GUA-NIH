@props(['ticket' => null, 'attractions', 'ticketTypes'])

<div class="form-group">
    <label for="tourist_attraction_id">Attraction</label>
    <select name="tourist_attraction_id" id="tourist_attraction_id" class="form-control @error('tourist_attraction_id') is-invalid @enderror" required>
        <option value="">Select Attraction</option>
        @foreach($attractions as $attraction)
            <option value="{{ $attraction->id }}" {{ old('tourist_attraction_id', $ticket?->tourist_attraction_id) == $attraction->id ? 'selected' : '' }}>
                {{ $attraction->name }}
            </option>
        @endforeach
    </select>
    @error('tourist_attraction_id')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="name">Ticket Name</label>
    <input type="text" 
           name="name" 
           id="name" 
           class="form-control @error('name') is-invalid @enderror" 
           value="{{ old('name', $ticket?->name) }}" 
           required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="type">Ticket Type</label>
    <select name="type" id="type" class="form-control @error('type') is-invalid @enderror" required>
        <option value="">Select Type</option>
        @foreach($ticketTypes as $value => $label)
            <option value="{{ $value }}" {{ old('type', $ticket?->type) == $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('type')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="price">Price (Rp)</label>
    <input type="number" 
           name="price" 
           id="price" 
           class="form-control @error('price') is-invalid @enderror" 
           value="{{ old('price', $ticket?->price) }}" 
           min="0" 
           step="0.01" 
           required>
    @error('price')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <label for="quota">Quota</label>
    <input type="number" 
           name="quota" 
           id="quota" 
           class="form-control @error('quota') is-invalid @enderror" 
           value="{{ old('quota', $ticket?->quota) }}" 
           min="1" 
           required>
    @error('quota')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-row">
    <div class="form-group col-md-6">
        <label for="valid_from">Valid From</label>
        <input type="datetime-local" 
               name="valid_from" 
               id="valid_from" 
               class="form-control @error('valid_from') is-invalid @enderror" 
               value="{{ old('valid_from', $ticket?->valid_from?->format('Y-m-d\TH:i')) }}" 
               required>
        @error('valid_from')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group col-md-6">
        <label for="valid_until">Valid Until</label>
        <input type="datetime-local" 
               name="valid_until" 
               id="valid_until" 
               class="form-control @error('valid_until') is-invalid @enderror" 
               value="{{ old('valid_until', $ticket?->valid_until?->format('Y-m-d\TH:i')) }}" 
               required>
        @error('valid_until')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="form-group">
    <label for="description">Description</label>
    <textarea name="description" 
              id="description" 
              class="form-control @error('description') is-invalid @enderror" 
              rows="3">{{ old('description', $ticket?->description) }}</textarea>
    @error('description')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="form-group">
    <div class="custom-control custom-switch">
        <input type="checkbox" 
               class="custom-control-input" 
               id="is_active" 
               name="is_active" 
               value="1" 
               {{ old('is_active', $ticket?->is_active) ? 'checked' : '' }}>
        <label class="custom-control-label" for="is_active">Active</label>
    </div>
</div> 