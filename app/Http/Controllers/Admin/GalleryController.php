<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Menu;
use App\Models\Event;
use App\Models\Promo;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $query = Gallery::with(['parent']);
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        
        $galleries = $query->orderBy('sort_order')->paginate(20);
        
        return view('admin.galleries.index', compact('galleries'));
    }
    
    public function create()
    {
        $menus = Menu::select('id', 'name')->get();
        $events = Event::select('id', 'title')->get();
        $promos = Promo::select('id', 'title')->get();
        $testimonials = Testimonial::select('id', 'customer_name')->get();
        
        return view('admin.galleries.create', compact('menus', 'events', 'promos', 'testimonials'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:image,video',
            'file' => 'required|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:10240',
            'parent_type' => 'required|in:menu,event,promo,testimonial',
            'parent_id' => 'required|integer',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
            'category' => 'nullable|string',
            'description' => 'nullable|string'
        ]);
        
        // Upload file ke storage
        $path = $request->file('file')->store('galleries/' . $validated['parent_type'], 'public');
        
        $data = [
            'title' => $validated['title'],
            'type' => $validated['type'],
            'file_path' => $path,
            'category' => $validated['category'] ?? $validated['parent_type'],
            'description' => $validated['description'],
            'is_featured' => $validated['is_featured'] ?? false,
            'sort_order' => $validated['sort_order'] ?? 0,
        ];
        
        // Set foreign key berdasarkan parent type
        switch ($validated['parent_type']) {
            case 'menu':
                $data['menu_id'] = $validated['parent_id'];
                break;
            case 'event':
                $data['event_id'] = $validated['parent_id'];
                break;
            case 'promo':
                $data['promo_id'] = $validated['parent_id'];
                break;
            case 'testimonial':
                $data['testimonial_id'] = $validated['parent_id'];
                break;
        }
        
        $gallery = Gallery::create($data);
        
        // Jika featured, unfeature lainnya untuk parent yang sama
        if ($data['is_featured']) {
            $this->unfeatureOthers($validated['parent_type'], $validated['parent_id'], $gallery->id);
        }
        
        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery berhasil ditambahkan');
    }
    
    public function edit(Gallery $gallery)
    {
        $menus = Menu::select('id', 'name')->get();
        $events = Event::select('id', 'title')->get();
        $promos = Promo::select('id', 'title')->get();
        $testimonials = Testimonial::select('id', 'customer_name')->get();
        
        return view('admin.galleries.edit', compact('gallery', 'menus', 'events', 'promos', 'testimonials'));
    }
    
    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:image,video',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:10240',
            'parent_type' => 'required|in:menu,event,promo,testimonial',
            'parent_id' => 'required|integer',
            'is_featured' => 'boolean',
            'sort_order' => 'nullable|integer',
            'category' => 'nullable|string',
            'description' => 'nullable|string'
        ]);
        
        $data = [
            'title' => $validated['title'],
            'type' => $validated['type'],
            'category' => $validated['category'] ?? $validated['parent_type'],
            'description' => $validated['description'],
            'is_featured' => $validated['is_featured'] ?? false,
            'sort_order' => $validated['sort_order'] ?? 0,
        ];
        
        // Upload file baru jika ada
        if ($request->hasFile('file')) {
            // Hapus file lama
            $gallery->deleteFile();
            
            // Upload file baru
            $path = $request->file('file')->store('galleries/' . $validated['parent_type'], 'public');
            $data['file_path'] = $path;
        }
        
        // Reset foreign keys
        $gallery->update([
            'menu_id' => null,
            'event_id' => null,
            'promo_id' => null,
            'testimonial_id' => null,
        ]);
        
        // Set foreign key berdasarkan parent type
        switch ($validated['parent_type']) {
            case 'menu':
                $data['menu_id'] = $validated['parent_id'];
                break;
            case 'event':
                $data['event_id'] = $validated['parent_id'];
                break;
            case 'promo':
                $data['promo_id'] = $validated['parent_id'];
                break;
            case 'testimonial':
                $data['testimonial_id'] = $validated['parent_id'];
                break;
        }
        
        $gallery->update($data);
        
        // Jika featured, unfeature lainnya untuk parent yang sama
        if ($data['is_featured']) {
            $this->unfeatureOthers($validated['parent_type'], $validated['parent_id'], $gallery->id);
        }
        
        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery berhasil diupdate');
    }
    
    public function destroy(Gallery $gallery)
    {
        // Hapus file dari storage
        $gallery->deleteFile();
        
        // Hapus record dari database
        $gallery->delete();
        
        return redirect()->route('admin.galleries.index')
            ->with('success', 'Gallery berhasil dihapus');
    }
    
    public function toggleFeatured(Gallery $gallery)
    {
        $gallery->update(['is_featured' => !$gallery->is_featured]);
        
        return response()->json(['success' => true, 'is_featured' => $gallery->is_featured]);
    }
    
    public function bulkUpload(Request $request)
    {
        $validated = $request->validate([
            'files.*' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            'parent_type' => 'required|in:menu,event,promo,testimonial',
            'parent_id' => 'required|integer',
        ]);
        
        $uploaded = [];
        
        foreach ($request->file('files') as $file) {
            $path = $file->store('galleries/' . $validated['parent_type'], 'public');
            
            $data = [
                'title' => $file->getClientOriginalName(),
                'type' => 'image',
                'file_path' => $path,
                'category' => $validated['parent_type'],
            ];
            
            switch ($validated['parent_type']) {
                case 'menu':
                    $data['menu_id'] = $validated['parent_id'];
                    break;
                case 'event':
                    $data['event_id'] = $validated['parent_id'];
                    break;
                case 'promo':
                    $data['promo_id'] = $validated['parent_id'];
                    break;
                case 'testimonial':
                    $data['testimonial_id'] = $validated['parent_id'];
                    break;
            }
            
            Gallery::create($data);
            $uploaded[] = $file->getClientOriginalName();
        }
        
        return redirect()->back()->with('success', count($uploaded) . ' file berhasil diupload');
    }
    
    public function updateSortOrder(Request $request)
    {
        $orders = $request->input('orders', []);
        
        foreach ($orders as $order) {
            Gallery::where('id', $order['id'])->update(['sort_order' => $order['sort_order']]);
        }
        
        return response()->json(['success' => true]);
    }
    
    public function getParents(Request $request)
    {
        $type = $request->get('type');
        $parents = [];
        
        switch ($type) {
            case 'menu':
                $parents = Menu::select('id', 'name as name')->get();
                break;
            case 'event':
                $parents = Event::select('id', 'title as name')->get();
                break;
            case 'promo':
                $parents = Promo::select('id', 'title as name')->get();
                break;
            case 'testimonial':
                $parents = Testimonial::select('id', 'customer_name as name')->get();
                break;
        }
        
        return response()->json($parents);
    }
    
    private function unfeatureOthers($parentType, $parentId, $currentId)
    {
        $query = Gallery::where('is_featured', true)->where('id', '!=', $currentId);
        
        switch ($parentType) {
            case 'menu':
                $query->where('menu_id', $parentId);
                break;
            case 'event':
                $query->where('event_id', $parentId);
                break;
            case 'promo':
                $query->where('promo_id', $parentId);
                break;
            case 'testimonial':
                $query->where('testimonial_id', $parentId);
                break;
        }
        
        $query->update(['is_featured' => false]);
    }
}