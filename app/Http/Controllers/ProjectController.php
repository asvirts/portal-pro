<?php

namespace App\Http\Controllers;

use App\Models\Portal;
use App\Models\Project;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of projects for a portal.
     */
    public function index(Portal $portal)
    {
        $this->authorize('view', $portal);

        $projects = $portal->projects()
            ->with(['client'])
            ->latest()
            ->paginate(15);

        return view('projects.index', compact('portal', 'projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create(Portal $portal)
    {
        $this->authorize('view', $portal);

        $clients = $portal->clients()->get();

        return view('projects.create', compact('portal', 'clients'));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request, Portal $portal)
    {
        $this->authorize('view', $portal);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'nullable|exists:clients,id',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);

        // Ensure client belongs to this portal if specified
        if ($validated['client_id']) {
            $client = Client::where('id', $validated['client_id'])
                ->where('portal_id', $portal->id)
                ->firstOrFail();
        }

        $project = $portal->projects()->create($validated);

        return redirect()
            ->route('portals.projects.show', [$portal, $project])
            ->with('success', 'Project created successfully!');
    }

    /**
     * Display the specified project.
     */
    public function show(Portal $portal, Project $project)
    {
        $this->authorize('view', $portal);

        // Ensure project belongs to this portal
        if ($project->portal_id !== $portal->id) {
            abort(404);
        }

        $project->load(['client', 'files', 'invoices']);

        return view('projects.show', compact('portal', 'project'));
    }

    /**
     * Show the form for editing the specified project.
     */
    public function edit(Portal $portal, Project $project)
    {
        $this->authorize('view', $portal);

        // Ensure project belongs to this portal
        if ($project->portal_id !== $portal->id) {
            abort(404);
        }

        $clients = $portal->clients()->get();

        return view('projects.edit', compact('portal', 'project', 'clients'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, Portal $portal, Project $project)
    {
        $this->authorize('view', $portal);

        // Ensure project belongs to this portal
        if ($project->portal_id !== $portal->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'client_id' => 'nullable|exists:clients,id',
            'status' => 'required|in:planning,active,on_hold,completed,cancelled',
            'priority' => 'required|in:low,medium,high,urgent',
            'start_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'hourly_rate' => 'nullable|numeric|min:0',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        // Ensure client belongs to this portal if specified
        if ($validated['client_id']) {
            $client = Client::where('id', $validated['client_id'])
                ->where('portal_id', $portal->id)
                ->firstOrFail();
        }

        $project->update($validated);

        return redirect()
            ->route('portals.projects.show', [$portal, $project])
            ->with('success', 'Project updated successfully!');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Portal $portal, Project $project)
    {
        $this->authorize('view', $portal);

        // Ensure project belongs to this portal
        if ($project->portal_id !== $portal->id) {
            abort(404);
        }

        $project->delete();

        return redirect()
            ->route('portals.projects.index', $portal)
            ->with('success', 'Project deleted successfully!');
    }
}
