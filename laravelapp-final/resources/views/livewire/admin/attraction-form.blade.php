<div>
    <form wire:submit.prevent="save" class="p-6">
        @csrf

        {{-- Form Fields --}}
        <div>
            <label for="name" class="block font-medium text-sm text-gray-700">Name</label>
            <input id="name" type="text" class="mt-1 block w-full" wire:model.defer="name">
            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <label for="city" class="block font-medium text-sm text-gray-700">City</label>
            <input id="city" type="text" class="mt-1 block w-full" wire:model.defer="city">
            @error('city') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <label for="province" class="block font-medium text-sm text-gray-700">Province</label>
            <input id="province" type="text" class="mt-1 block w-full" wire:model.defer="province">
            @error('province') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <label for="address" class="block font-medium text-sm text-gray-700">Address</label>
            <textarea id="address" class="mt-1 block w-full" wire:model.defer="address"></textarea>
            @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <label for="description" class="block font-medium text-sm text-gray-700">Description</label>
            <textarea id="description" class="mt-1 block w-full" wire:model.defer="description"></textarea>
            @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        
        <div class="mt-4">
            <label for="opening_hours" class="block font-medium text-sm text-gray-700">Opening Hours</label>
            <input id="opening_hours" type="time" class="mt-1 block w-full" wire:model.defer="opening_hours">
            @error('opening_hours') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div class="mt-4">
            <label for="closing_hours" class="block font-medium text-sm text-gray-700">Closing Hours</label>
            <input id="closing_hours" type="time" class="mt-1 block w-full" wire:model.defer="closing_hours">
            @error('closing_hours') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        {{-- Featured Image Upload --}}
        <div class="mt-4">
            <label for="featured_image" class="block font-medium text-sm text-gray-700">Featured Image</label>
            <input id="featured_image" type="file" wire:model="featured_image" class="mt-1">
            @error('featured_image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

            {{-- Tampilkan gambar lama --}}
            @if ($current_featured_image)
                <p class="mt-2 text-sm text-gray-500">Current Image:</p>
                <img src="{{ asset('storage/' . $current_featured_image) }}" class="mt-2 h-32 w-32 object-cover rounded-md">
            @endif
        </div>

        {{-- Gallery Upload --}}
        <div class="mt-4">
            <label for="gallery" class="block font-medium text-sm text-gray-700">Photo Gallery</label>
            <input id="gallery" type="file" wire:model="gallery" multiple class="mt-1">
            @error('gallery.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            
            {{-- Tampilkan galeri lama --}}
            @if(count($current_gallery) > 0)
                <p class="mt-2 text-sm text-gray-500">Current Gallery:</p>
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach($current_gallery as $imagePath)
                        <img src="{{ asset('storage/' . $imagePath) }}" class="h-20 w-20 object-cover rounded-md">
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Facilities --}}
        <div class="mt-4">
            <label class="block font-medium text-sm text-gray-700">Facilities</label>
            <div class="flex items-center gap-2 mt-1">
                <input type="text" class="flex-grow" wire:model.defer="newFacility" placeholder="Add new facility">
                <button type="button" wire:click="addFacility" class="bg-indigo-500 text-white px-4 py-2 rounded-md">Add</button>
            </div>
            @error('newFacility') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            <div class="mt-2 space-y-1">
                @foreach($facilities as $index => $facility)
                    <div class="flex items-center justify-between bg-gray-100 p-2 rounded-md">
                        <span>{{ $facility }}</span>
                        <button type="button" wire:click="removeFacility({{ $index }})" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-4 flex justify-end">
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Save
            </button>
        </div>
    </form>
</div>