<?php

namespace App\Http\Livewire\Admin;

use App\Models\TouristAttraction;
use Livewire\Component;
use Livewire\WithFileUploads; // PENTING: Import trait ini

class AttractionForm extends Component
{
    use WithFileUploads; // PENTING: Gunakan trait ini

    public $attraction;

    public $name;

    public $city;

    public $province;

    public $address;

    public $latitude;

    public $longitude;

    public $description;

    public $opening_hours;

    public $closing_hours;

    public $featured_image;

    public $current_featured_image;

    public $gallery = [];

    public $current_gallery = [];

    public $facilities = [];

    public $newFacility;

    protected $rules = [
        'name' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'province' => 'required|string|max:255',
        'address' => 'nullable|string',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'description' => 'nullable|string',
        'opening_hours' => 'nullable',
        'closing_hours' => 'nullable',
        'featured_image' => 'nullable|image|max:1024', // 1MB Max
        'gallery.*' => 'nullable|image|max:1024',
        'facilities' => 'nullable|array',
        'newFacility' => 'nullable|string|max:255',
    ];

    public function mount(?TouristAttraction $attraction = null)
    {
        $this->attraction = $attraction;

        if ($this->attraction) {
            $this->name = $this->attraction->name;
            $this->city = $this->attraction->city;
            $this->province = $this->attraction->province;
            $this->address = $this->attraction->address;
            $this->latitude = $this->attraction->latitude;
            $this->longitude = $this->attraction->longitude;
            $this->description = $this->attraction->description;
            $this->opening_hours = $this->attraction->opening_hours;
            $this->closing_hours = $this->attraction->closing_hours;
            $this->current_featured_image = $this->attraction->featured_image;
            $this->current_gallery = $this->attraction->gallery ?? [];
            $this->facilities = $this->attraction->facilities ?? [];
        }
    }

    public function addFacility()
    {
        if ($this->newFacility) {
            $this->facilities[] = $this->newFacility;
            $this->newFacility = '';
        }
    }

    public function removeFacility($index)
    {
        unset($this->facilities[$index]);
        $this->facilities = array_values($this->facilities);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'slug' => \Str::slug($this->name),
            'city' => $this->city,
            'province' => $this->province,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'description' => $this->description,
            'opening_hours' => $this->opening_hours,
            'closing_hours' => $this->closing_hours,
            'facilities' => $this->facilities,
        ];

        // LOGIKA PENTING: Menangani upload gambar
        if ($this->featured_image) {
            $data['featured_image'] = $this->featured_image->store('public/attractions'); // PATH BENAR
        } elseif ($this->current_featured_image) {
            $data['featured_image'] = $this->current_featured_image;
        }

        if ($this->gallery) {
            $galleryPaths = [];
            foreach ($this->gallery as $image) {
                $galleryPaths[] = $image->store('public/attractions'); // PATH BENAR
            }
            $data['gallery'] = array_merge($this->current_gallery, $galleryPaths);
        } else {
            $data['gallery'] = $this->current_gallery;
        }

        if ($this->attraction) {
            $this->attraction->update($data);
        } else {
            TouristAttraction::create($data);
        }

        return redirect()->route('admin.attractions.index')->with('success', 'Atraksi berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.attraction-form');
    }
}
