<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

class MenuItemController extends Controller
{
    /**
     * Menampilkan daftar semua menu.
     */
    public function index()
    {

        $menuItems = MenuItem::latest()->paginate(10);
        // Pastikan path view sudah benar
        return view('dashboards.menu_items.index', compact('menuItems'));
    }

    /**
     * Menampilkan form untuk membuat menu baru.
     */
    public function create()
    {
        $categories = Category::all(); // Ambil semua kategori untuk dropdown
        // Pastikan path view sudah benar
        return view('dashboards.menu_items.create', compact('categories'));
    }

    /**
     * Menyimpan menu baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_available' => 'required|in:yes,no',      // <-- Validasi baru
            'is_recommended' => 'required|in:yes,no',   // <-- Validasi baru
            'category' => 'required|string|exists:categories,name', // <-- Validasi baru
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $filePath = $request->file('image')->store('menu_images', 'public');
            $data['image'] = $filePath;
        }

        MenuItem::create($data);

        return redirect()->route('admin.menu-items.index')
                         ->with('success', 'Menu baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit data menu.
     */
    public function edit(MenuItem $menuItem)
    {
        $categories = Category::all(); // Ambil semua kategori untuk dropdown
        // Pastikan path view sudah benar
        return view('dashboards.menu_items.edit', compact('menuItem', 'categories'));
    }

    /**
     * Memperbarui data menu di database.
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_available' => 'required|in:yes,no',      // <-- Validasi baru
            'is_recommended' => 'required|in:yes,no',   // <-- Validasi baru
            'category' => 'required|string|exists:categories,name', // <-- Validasi baru
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }
            $filePath = $request->file('image')->store('menu_images', 'public');
            $data['image'] = $filePath;
        }

        $menuItem->update($data);

        return redirect()->route('admin.menu-items.index')
                         ->with('success', 'Data menu berhasil diperbarui.');
    }

    /**
     * Menghapus data menu dari database.
     */
    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->image) {
            Storage::disk('public')->delete($menuItem->image);
        }
        
        $menuItem->delete();

        return redirect()->route('admin.menu-items.index')
                         ->with('success', 'Data menu berhasil dihapus.');
    }
}
