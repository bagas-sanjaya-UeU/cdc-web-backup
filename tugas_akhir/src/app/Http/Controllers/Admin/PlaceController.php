<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Place;
use Illuminate\Http\Request;

class PlaceController extends Controller
{
    /**
     * Menampilkan daftar semua tempat.
     */
    public function index()
    {
        $places = Place::latest()->paginate(10);
        return view('dashboards.places.index', compact('places'));
    }

    /**
     * Menampilkan form untuk membuat tempat baru.
     */
    public function create()
    {
        return view('dashboards.places.create');
    }

    /**
     * Menyimpan tempat baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:places',
            'description' => 'nullable|string',
        ]);

        Place::create($request->all());

        return redirect()->route('admin.places.index')
                         ->with('success', 'Tempat baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu tempat (opsional, bisa langsung ke edit).
     */
    public function show(Place $place)
    {
        return view('dashboards.places.show', compact('place'));
    }

    /**
     * Menampilkan form untuk mengedit data tempat.
     */
    public function edit(Place $place)
    {
        return view('dashboards.places.edit', compact('place'));
    }

    /**
     * Memperbarui data tempat di database.
     */
    public function update(Request $request, Place $place)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:places,name,' . $place->id,
            'description' => 'nullable|string',
        ]);

        $place->update($request->all());

        return redirect()->route('admin.places.index')
                         ->with('success', 'Data tempat berhasil diperbarui.');
    }

    /**
     * Menghapus data tempat dari database.
     */
    public function destroy(Place $place)
    {
        // Tambahkan validasi, misalnya jangan hapus jika masih ada booking aktif
        if ($place->bookings()->where('status', '!=', 'completed')->exists()) {
            return redirect()->route('admin.places.index')
                             ->with('error', 'Tidak bisa menghapus tempat yang masih memiliki booking aktif.');
        }

        $place->delete();

        return redirect()->route('admin.places.index')
                         ->with('success', 'Data tempat berhasil dihapus.');
    }
}
