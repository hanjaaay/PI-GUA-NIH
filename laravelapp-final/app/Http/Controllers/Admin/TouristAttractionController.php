<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TouristAttraction;

class TouristAttractionController extends Controller
{
    // Hanya perlu metode index, create, edit
    // Logika create/update akan ditangani oleh Livewire

    public function index()
    {
        $attractions = TouristAttraction::latest()->paginate(10);

        return view('admin.attractions.index', compact('attractions'));
    }

    public function create()
    {
        return view('admin.attractions.create');
    }

    public function edit(TouristAttraction $attraction)
    {
        return view('admin.attractions.edit', compact('attraction'));
    }

    // Metode store, update, destroy DIHAPUS karena akan di-handle oleh Livewire
    // atau didefinisikan secara terpisah
}
