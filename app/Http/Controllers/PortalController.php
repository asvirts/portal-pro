<?php

namespace App\Http\Controllers;

use App\Models\Portal;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PortalController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $portals = Portal::where('user_id', auth()->id())
            ->withCount(['clients', 'projects', 'invoices'])
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        return view('portals.index', compact('portals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('portals.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subdomain' => 'nullable|string|max:50|unique:portals,subdomain|alpha_dash',
            'custom_domain' => 'nullable|string|max:255|unique:portals,custom_domain',
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);
        
        $validated['user_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['name']);
        
        // Ensure unique slug
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Portal::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        $portal = Portal::create($validated);
        
        return redirect()->route('portals.show', $portal)
            ->with('success', 'Portal created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Portal $portal): View
    {
        $this->authorize('view', $portal);
        
        $portal->load(['clients', 'projects.client', 'invoices']);
        
        return view('portals.show', compact('portal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Portal $portal): View
    {
        $this->authorize('update', $portal);
        
        return view('portals.edit', compact('portal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Portal $portal): RedirectResponse
    {
        $this->authorize('update', $portal);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'subdomain' => [
                'nullable',
                'string',
                'max:50',
                'alpha_dash',
                Rule::unique('portals', 'subdomain')->ignore($portal->id)
            ],
            'custom_domain' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('portals', 'custom_domain')->ignore($portal->id)
            ],
            'primary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'secondary_color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'is_active' => 'boolean',
        ]);
        
        $portal->update($validated);
        
        return redirect()->route('portals.show', $portal)
            ->with('success', 'Portal updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portal $portal): RedirectResponse
    {
        $this->authorize('delete', $portal);
        
        $portal->delete();
        
        return redirect()->route('portals.index')
            ->with('success', 'Portal deleted successfully!');
    }
}
