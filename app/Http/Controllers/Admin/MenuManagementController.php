<?php
// app/Http/Controllers/Admin/MenuManagementController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuManagementController extends Controller
{
    /**
     * Display a listing of the menu.
     */
    public function index(Request $request)
    {
        $query = Menu::query();
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $menus = $query->orderBy('sort_order')->paginate(15);
        $categories = Menu::select('category')->distinct()->pluck('category');
        
        return view('admin.menus.index', compact('menus', 'categories'));
    }
    
    /**
     * Show the form for creating a new menu.
     */
    public function create()
    {
        return view('admin.menus.create');
    }
    
    /**
     * Store a newly created menu in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:makanan,minuman,dessert',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_available' => 'boolean',
            'is_recommended' => 'boolean',
            'sort_order' => 'integer'
        ]);
        
        // Set default values for checkboxes
        $validated['is_available'] = $request->has('is_available');
        $validated['is_recommended'] = $request->has('is_recommended');
        
        // Upload image if exists
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }
        
        Menu::create($validated);
        
        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil ditambahkan');
    }
    
    /**
     * Display the specified menu.
     */
    public function show(Menu $menu)
    {
        return view('admin.menus.show', compact('menu'));
    }
    
    /**
     * Show the form for editing the specified menu.
     */
    public function edit(Menu $menu)
    {
        return view('admin.menus.edit', compact('menu'));
    }
    
    /**
     * Update the specified menu in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|in:makanan,minuman,dessert',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_available' => 'boolean',
            'is_recommended' => 'boolean',
            'sort_order' => 'integer'
        ]);
        
        // Set default values for checkboxes
        $validated['is_available'] = $request->has('is_available');
        $validated['is_recommended'] = $request->has('is_recommended');
        
        // Upload new image if exists
        if ($request->hasFile('image')) {
            // Delete old image
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $validated['image'] = $request->file('image')->store('menus', 'public');
        }
        
        $menu->update($validated);
        
        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil diupdate');
    }
    
    /**
     * Remove the specified menu from storage.
     */
    public function destroy(Menu $menu)
    {
        // Delete image file
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        
        $menu->delete();
        
        return redirect()->route('admin.menus.index')
            ->with('success', 'Menu berhasil dihapus');
    }
    
    /**
     * Toggle menu availability status.
     */
    public function toggleAvailability(Menu $menu)
    {
        $menu->update(['is_available' => !$menu->is_available]);
        
        return response()->json([
            'success' => true, 
            'is_available' => $menu->is_available
        ]);
    }
}