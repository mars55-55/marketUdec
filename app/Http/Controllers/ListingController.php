<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Category;
use App\Models\ListingImage;
use App\Http\Requests\ListingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ListingController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filtro de status
        if ($request->status) {
            $query = Listing::with(['user', 'category', 'primaryImage'])
                ->where('status', $request->status)
                ->latest();
        } else {
            // Mostrar anuncios activos y vendidos, pero no pausados o bloqueados
            $query = Listing::with(['user', 'category', 'primaryImage'])
                ->whereIn('status', ['active', 'sold'])
                ->orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END") // Activos primero
                ->latest();
        }

        // Filtros
        if ($request->category) {
            $query->byCategory($request->category);
        }

        if ($request->search) {
            $query->search($request->search);
        }

        if ($request->min_price || $request->max_price) {
            $min = $request->min_price ?? 0;
            $max = $request->max_price ?? 999999;
            $query->priceRange($min, $max);
        }

        $listings = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();

        return view('listings.index', compact('listings', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('listings.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        file_put_contents(base_path('debug_listings.log'), "[" . now() . "] ListingController@store - Iniciando creación de listing\n", FILE_APPEND);
        file_put_contents(base_path('debug_listings.log'), "[" . now() . "] Datos recibidos: " . json_encode($request->all()) . "\n", FILE_APPEND);
        
        Log::info('ListingController@store - Iniciando creación de listing', [
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);

        file_put_contents(base_path('debug_listings.log'), "[" . now() . "] Datos recibidos: " . json_encode($request->all()) . "\n", FILE_APPEND);
        
        // Validación básica
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0|max:99999999',
                'category_id' => 'required|exists:categories,id',
                'condition' => 'required|in:nuevo,como_nuevo,bueno,aceptable,malo',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            file_put_contents(base_path('debug_listings.log'), "[" . now() . "] Error de validación: " . json_encode($e->errors()) . "\n", FILE_APPEND);
            throw $e;
        }

        file_put_contents(base_path('debug_listings.log'), "[" . now() . "] Validación pasada correctamente\n", FILE_APPEND);

        try {
            $listing = new Listing();
            $listing->title = $request->title;
            $listing->description = $request->description;
            $listing->price = $request->price;
            $listing->category_id = $request->category_id;
            $listing->condition = $request->condition;
            $listing->user_id = Auth::id();
            
            file_put_contents(base_path('debug_listings.log'), "[" . now() . "] Intentando guardar listing\n", FILE_APPEND);
            $listing->save();
            file_put_contents(base_path('debug_listings.log'), "[" . now() . "] Listing guardado con ID: {$listing->id}\n", FILE_APPEND);

            // Procesar imágenes
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('listings', 'public');
                    $listing->images()->create(['image_path' => $path]);
                }
            }

            file_put_contents(base_path('debug_listings.log'), "[" . now() . "] Listing creado exitosamente con ID: {$listing->id}\n", FILE_APPEND);

            return redirect()->route('listings.show', $listing)->with('success', 'Anuncio publicado exitosamente');
        } catch (\Exception $e) {
            file_put_contents(base_path('debug_listings.log'), "[" . now() . "] ERROR: {$e->getMessage()}\n", FILE_APPEND);
            return back()->withInput()->withErrors(['error' => 'Error al crear el anuncio: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing)
    {
        $listing->load(['user', 'category', 'images']);
        
        // Incrementar vistas solo si no es el propietario
        if (!Auth::check() || Auth::id() !== $listing->user_id) {
            $listing->incrementViews();
        }

        return view('listings.show', compact('listing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing)
    {
        $this->authorize('update', $listing);
        $categories = Category::where('is_active', true)->get();
        
        return view('listings.edit', compact('listing', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Listing $listing)
    {
        $this->authorize('update', $listing);

        // Agregar log para debug
        Log::info('Update request data:', $request->all());

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'price' => 'required|numeric|min:0|max:99999999',
            'condition' => ['required', Rule::in(['nuevo', 'como_nuevo', 'bueno', 'aceptable', 'malo'])],
            'category_id' => 'required|exists:categories,id',
            'location' => 'required|string|max:255',
            'is_negotiable' => 'boolean',
            'allows_exchange' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $listing->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'location' => $request->location,
            'is_negotiable' => $request->boolean('is_negotiable'),
            'allows_exchange' => $request->boolean('allows_exchange'),
            // Mantener el status actual del listing
        ]);

        // Procesar nuevas imágenes
        if ($request->hasFile('images')) {
            $this->uploadImages($listing, $request->file('images'));
        }

        return redirect()->route('listings.show', $listing)
            ->with('success', 'Anuncio actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing)
    {
        $this->authorize('delete', $listing);

        // Eliminar imágenes del storage
        foreach ($listing->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $listing->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Anuncio eliminado exitosamente.');
    }

    /**
     * Toggle status between active and paused
     */
    public function toggleStatus(Listing $listing)
    {
        $this->authorize('update', $listing);
        
        $listing->status = $listing->status === 'active' ? 'paused' : 'active';
        $listing->save();

        $message = $listing->status === 'active' 
            ? 'Anuncio activado exitosamente.' 
            : 'Anuncio pausado exitosamente.';

        return back()->with('success', $message);
    }

    /**
     * Mark as sold
     */
    public function markAsSold(Listing $listing)
    {
        $this->authorize('update', $listing);
        
        $listing->status = 'sold';
        $listing->save();

        return back()->with('success', 'Anuncio marcado como vendido.');
    }

    /**
     * Upload images for listing
     */
    private function uploadImages(Listing $listing, array $images)
    {
        foreach ($images as $index => $image) {
            $path = $image->store('listings', 'public');
            
            ListingImage::create([
                'listing_id' => $listing->id,
                'image_path' => $path,
                'image_name' => $image->getClientOriginalName(),
                'order' => $index,
                'is_primary' => $index === 0, // Primera imagen como principal
            ]);
        }
    }
}
