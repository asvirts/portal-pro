<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Files') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ $portal->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('portals.files.create', $portal) }}" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                    Upload Files
                </a>
                <a href="{{ route('portals.show', $portal) }}" 
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium">
                    Back to Portal
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- File Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Files</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $files->total() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Images</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $files->where('mime_type', 'like', 'image/%')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Documents</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $files->whereIn('mime_type', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Size</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($files->sum('size') / 1024 / 1024, 1) }}MB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters and Search -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('portals.files.index', $portal) }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" 
                                placeholder="Search files...">
                        </div>
                        
                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700 mb-1">Project</label>
                            <select name="project_id" id="project_id" 
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">All Projects</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ request('project_id') == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                            <select name="client_id" id="client_id" 
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">All Clients</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 mb-1">File Type</label>
                            <select name="type" id="type" 
                                class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">All Types</option>
                                <option value="image" {{ request('type') == 'image' ? 'selected' : '' }}>Images</option>
                                <option value="application/pdf" {{ request('type') == 'application/pdf' ? 'selected' : '' }}>PDFs</option>
                                <option value="application" {{ request('type') == 'application' ? 'selected' : '' }}>Documents</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Files Grid/List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium text-gray-900">All Files</h3>
                        
                        <!-- View Toggle -->
                        <div class="flex items-center space-x-2">
                            <button id="grid-view" class="p-2 text-gray-400 hover:text-gray-600 border rounded-md view-toggle active">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                </svg>
                            </button>
                            <button id="list-view" class="p-2 text-gray-400 hover:text-gray-600 border rounded-md view-toggle">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    @if($files->count() > 0)
                        <!-- Grid View -->
                        <div id="files-grid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach($files as $file)
                                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors group">
                                    <div class="flex flex-col h-full">
                                        <!-- File Icon/Preview -->
                                        <div class="flex-shrink-0 mb-3">
                                            @if(str_starts_with($file->mime_type, 'image/'))
                                                <div class="w-full h-32 bg-gray-200 rounded-lg overflow-hidden">
                                                    <img src="{{ Storage::url($file->path) }}" 
                                                         alt="{{ $file->name }}" 
                                                         class="w-full h-full object-cover">
                                                </div>
                                            @else
                                                <div class="w-full h-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                                    @if($file->mime_type === 'application/pdf')
                                                        <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-12 h-12 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>

                                        <!-- File Info -->
                                        <div class="flex-1">
                                            <h4 class="text-sm font-medium text-gray-900 truncate mb-1">
                                                <a href="{{ route('portals.files.show', [$portal, $file]) }}" 
                                                   class="hover:text-blue-600">
                                                    {{ $file->name }}
                                                </a>
                                            </h4>
                                            
                                            <p class="text-xs text-gray-500 mb-2">
                                                {{ number_format($file->size / 1024, 1) }} KB
                                            </p>

                                            @if($file->project)
                                                <p class="text-xs text-blue-600 mb-1">{{ $file->project->name }}</p>
                                            @endif

                                            @if($file->client)
                                                <p class="text-xs text-green-600 mb-1">{{ $file->client->name }}</p>
                                            @endif

                                            <p class="text-xs text-gray-400">
                                                {{ $file->created_at->diffForHumans() }}
                                            </p>
                                        </div>

                                        <!-- Actions -->
                                        <div class="flex items-center justify-between mt-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('portals.files.download', [$portal, $file]) }}" 
                                               class="text-blue-600 hover:text-blue-700 text-xs font-medium">
                                                Download
                                            </a>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('portals.files.edit', [$portal, $file]) }}" 
                                                   class="text-gray-600 hover:text-gray-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- List View (Hidden by default) -->
                        <div id="files-list" class="hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">File</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Uploaded</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($files as $file)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 w-8 h-8">
                                                            @if(str_starts_with($file->mime_type, 'image/'))
                                                                <img src="{{ Storage::url($file->path) }}" 
                                                                     alt="{{ $file->name }}" 
                                                                     class="w-8 h-8 rounded object-cover">
                                                            @elseif($file->mime_type === 'application/pdf')
                                                                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                                </svg>
                                                            @else
                                                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                </svg>
                                                            @endif
                                                        </div>
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                <a href="{{ route('portals.files.show', [$portal, $file]) }}" 
                                                                   class="hover:text-blue-600">
                                                                    {{ Str::limit($file->name, 40) }}
                                                                </a>
                                                            </div>
                                                            <div class="text-sm text-gray-500">{{ $file->mime_type }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if($file->project)
                                                        <a href="{{ route('portals.projects.show', [$portal, $file->project]) }}" 
                                                           class="text-blue-600 hover:text-blue-700">
                                                            {{ $file->project->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-gray-400">Unassigned</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @if($file->client)
                                                        <a href="{{ route('portals.clients.show', [$portal, $file->client]) }}" 
                                                           class="text-green-600 hover:text-green-700">
                                                            {{ $file->client->name }}
                                                        </a>
                                                    @else
                                                        <span class="text-gray-400">Unassigned</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ number_format($file->size / 1024, 1) }} KB
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $file->created_at->format('M j, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <a href="{{ route('portals.files.download', [$portal, $file]) }}" 
                                                           class="text-blue-600 hover:text-blue-900">Download</a>
                                                        <a href="{{ route('portals.files.edit', [$portal, $file]) }}" 
                                                           class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $files->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No files yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by uploading your first file.</p>
                            <div class="mt-6">
                                <a href="{{ route('portals.files.create', $portal) }}" 
                                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Upload Files
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for view toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gridViewBtn = document.getElementById('grid-view');
            const listViewBtn = document.getElementById('list-view');
            const filesGrid = document.getElementById('files-grid');
            const filesList = document.getElementById('files-list');

            gridViewBtn.addEventListener('click', function() {
                filesGrid.classList.remove('hidden');
                filesList.classList.add('hidden');
                gridViewBtn.classList.add('active', 'bg-blue-50', 'text-blue-600', 'border-blue-300');
                listViewBtn.classList.remove('active', 'bg-blue-50', 'text-blue-600', 'border-blue-300');
            });

            listViewBtn.addEventListener('click', function() {
                filesList.classList.remove('hidden');
                filesGrid.classList.add('hidden');
                listViewBtn.classList.add('active', 'bg-blue-50', 'text-blue-600', 'border-blue-300');
                gridViewBtn.classList.remove('active', 'bg-blue-50', 'text-blue-600', 'border-blue-300');
            });
        });
    </script>

    <style>
        .view-toggle.active {
            @apply bg-blue-50 text-blue-600 border-blue-300;
        }
    </style>
</x-app-layout>
