<?php

namespace App\Http\Controllers;

use App\Models\Portal;
use App\Models\Project;
use App\Models\Client;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of files for a portal.
     */
    public function index(Portal $portal, Request $request)
    {
        $this->authorize('view', $portal);

        $query = $portal->files()->with(['project', 'client', 'uploader']);

        // Filter by project if specified
        if ($request->has('project_id') && $request->project_id) {
            $query->where('project_id', $request->project_id);
        }

        // Filter by client if specified
        if ($request->has('client_id') && $request->client_id) {
            $query->where('client_id', $request->client_id);
        }

        // Filter by file type if specified
        if ($request->has('type') && $request->type) {
            $query->where('mime_type', 'like', $request->type . '%');
        }

        // Search by filename
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $files = $query->latest()->paginate(20);
        $projects = $portal->projects()->get();
        $clients = $portal->clients()->get();

        return view('files.index', compact('portal', 'files', 'projects', 'clients'));
    }

    /**
     * Show the form for uploading new files.
     */
    public function create(Portal $portal)
    {
        $this->authorize('view', $portal);

        $projects = $portal->projects()->get();
        $clients = $portal->clients()->get();

        return view('files.create', compact('portal', 'projects', 'clients'));
    }

    /**
     * Store newly uploaded files.
     */
    public function store(Request $request, Portal $portal)
    {
        $this->authorize('view', $portal);

        $request->validate([
            'files.*' => 'required|file|max:10240', // 10MB max per file
            'project_id' => 'nullable|exists:projects,id',
            'client_id' => 'nullable|exists:clients,id',
            'description' => 'nullable|string|max:500',
            'is_public' => 'boolean',
        ]);

        // Ensure project and client belong to this portal if specified
        if ($request->project_id) {
            $project = Project::where('id', $request->project_id)
                ->where('portal_id', $portal->id)
                ->firstOrFail();
        }

        if ($request->client_id) {
            $client = Client::where('id', $request->client_id)
                ->where('portal_id', $portal->id)
                ->firstOrFail();
        }

        $uploadedFiles = [];

        foreach ($request->file('files') as $uploadedFile) {
            // Generate unique filename
            $filename = time() . '_' . Str::random(10) . '.' . $uploadedFile->getClientOriginalExtension();
            
            // Store file in portal-specific directory
            $path = $uploadedFile->storeAs(
                'portals/' . $portal->id . '/files',
                $filename,
                'public'
            );

            // Create file record
            $file = $portal->files()->create([
                'name' => $uploadedFile->getClientOriginalName(),
                'original_name' => $uploadedFile->getClientOriginalName(),
                'file_path' => $path,
                'file_size' => $uploadedFile->getSize(),
                'file_hash' => hash_file('sha256', $uploadedFile->getRealPath()),
                'mime_type' => $uploadedFile->getMimeType(),
                'project_id' => $request->project_id,
                'client_id' => $request->client_id,
                'uploaded_by' => auth()->id(),
                'description' => $request->description,
                'is_public' => $request->boolean('is_public', false),
            ]);

            $uploadedFiles[] = $file;
        }

        $message = count($uploadedFiles) === 1 
            ? 'File uploaded successfully!' 
            : count($uploadedFiles) . ' files uploaded successfully!';

        return redirect()
            ->route('portals.files.index', $portal)
            ->with('success', $message);
    }

    /**
     * Display the specified file.
     */
    public function show(Portal $portal, File $file)
    {
        $this->authorize('view', $portal);

        // Ensure file belongs to this portal
        if ($file->portal_id !== $portal->id) {
            abort(404);
        }

        $file->load(['project', 'client', 'uploader']);

        return view('files.show', compact('portal', 'file'));
    }

    /**
     * Download the specified file.
     */
    public function download(Portal $portal, File $file)
    {
        $this->authorize('view', $portal);

        // Ensure file belongs to this portal
        if ($file->portal_id !== $portal->id) {
            abort(404);
        }

        // Check if file exists
        if (!Storage::disk('public')->exists($file->path)) {
            abort(404, 'File not found on disk.');
        }

        // Increment download count
        $file->increment('download_count');

        return Storage::disk('public')->download($file->path, $file->name);
    }

    /**
     * Show the form for editing the specified file.
     */
    public function edit(Portal $portal, File $file)
    {
        $this->authorize('view', $portal);

        // Ensure file belongs to this portal
        if ($file->portal_id !== $portal->id) {
            abort(404);
        }

        $projects = $portal->projects()->get();
        $clients = $portal->clients()->get();

        return view('files.edit', compact('portal', 'file', 'projects', 'clients'));
    }

    /**
     * Update the specified file metadata.
     */
    public function update(Request $request, Portal $portal, File $file)
    {
        $this->authorize('view', $portal);

        // Ensure file belongs to this portal
        if ($file->portal_id !== $portal->id) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'client_id' => 'nullable|exists:clients,id',
            'description' => 'nullable|string|max:500',
            'is_public' => 'boolean',
        ]);

        // Ensure project and client belong to this portal if specified
        if ($request->project_id) {
            $project = Project::where('id', $request->project_id)
                ->where('portal_id', $portal->id)
                ->firstOrFail();
        }

        if ($request->client_id) {
            $client = Client::where('id', $request->client_id)
                ->where('portal_id', $portal->id)
                ->firstOrFail();
        }

        $file->update([
            'name' => $request->name,
            'project_id' => $request->project_id,
            'client_id' => $request->client_id,
            'description' => $request->description,
            'is_public' => $request->boolean('is_public', false),
        ]);

        return redirect()
            ->route('portals.files.show', [$portal, $file])
            ->with('success', 'File updated successfully!');
    }

    /**
     * Remove the specified file from storage.
     */
    public function destroy(Portal $portal, File $file)
    {
        $this->authorize('view', $portal);

        // Ensure file belongs to this portal
        if ($file->portal_id !== $portal->id) {
            abort(404);
        }

        // Delete file from storage
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        // Delete file record
        $file->delete();

        return redirect()
            ->route('portals.files.index', $portal)
            ->with('success', 'File deleted successfully!');
    }

    /**
     * Display files for a specific project.
     */
    public function projectFiles(Portal $portal, Project $project)
    {
        $this->authorize('view', $portal);

        // Ensure project belongs to this portal
        if ($project->portal_id !== $portal->id) {
            abort(404);
        }

        $files = $project->files()->with(['client', 'uploader'])->latest()->paginate(20);

        return view('files.project', compact('portal', 'project', 'files'));
    }

    /**
     * Display files for a specific client.
     */
    public function clientFiles(Portal $portal, Client $client)
    {
        $this->authorize('view', $portal);

        // Ensure client belongs to this portal
        if ($client->portal_id !== $portal->id) {
            abort(404);
        }

        $files = $client->files()->with(['project', 'uploader'])->latest()->paginate(20);

        return view('files.client', compact('portal', 'client', 'files'));
    }
}
