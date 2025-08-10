<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Table;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- Tambahkan ini

class TableController extends Controller
{
    /**
     * Menampilkan daftar semua meja.
     */
    public function index()
    {
        $tables = Table::with('place')->latest()->paginate(10);
        // Sesuaikan path view jika perlu
        return view('dashboards.tables.index', compact('tables'));
    }

    /**
     * Menampilkan form untuk membuat meja baru.
     */
    public function create()
    {
        $places = Place::all(); // Untuk dropdown pilihan tempat
        // Sesuaikan path view jika perlu
        return view('dashboards.tables.create', compact('places'));
    }

    /**
     * Menyimpan meja baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'place_id' => 'required|exists:places,id',
            'table_number' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi gambar
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $filePath = $request->file('image')->store('table_images', 'public');
            $data['image'] = $filePath;
        }

        Table::create($data);

        return redirect()->route('admin.tables.index')
                         ->with('success', 'Meja baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data meja.
     */
    public function edit(Table $table)
    {
        $places = Place::all();
        // Sesuaikan path view jika perlu
        return view('dashboards.tables.edit', compact('table', 'places'));
    }

    /**
     * Memperbarui data meja di database.
     */
    public function update(Request $request, Table $table)
    {
        $request->validate([
            'place_id' => 'required|exists:places,id',
            'table_number' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi gambar
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($table->image) {
                Storage::disk('public')->delete($table->image);
            }
            // Upload gambar baru
            $filePath = $request->file('image')->store('table_images', 'public');
            $data['image'] = $filePath;
        }

        $table->update($data);

        return redirect()->route('admin.tables.index')
                         ->with('success', 'Data meja berhasil diperbarui.');
    }

    /**
     * Menghapus data meja dari database.
     */
    public function destroy(Table $table)
    {
        // Hapus gambar terkait dari storage
        if ($table->image) {
            Storage::disk('public')->delete($table->image);
        }
        
        $table->delete();

        return redirect()->route('admin.tables.index')
                         ->with('success', 'Data meja berhasil dihapus.');
    }
}
