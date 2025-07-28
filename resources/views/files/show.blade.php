<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $file->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ $portal->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('portals.files.download', [$portal, $file]) }}" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                    Download
                </a>
                <a href="{{ route('portals.files.edit', [$portal, $file]) }}" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                    Edit
                </a>
                <a href="{{ route('portals.files.index', $portal) }}" 
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium">
                    Back to Files
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- File Preview -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">File Preview</h3>
                            
                            <div class="border border-gray-200 rounded-lg overflow-hidden">
                                @if(str_starts_with($file->mime_type, 'image/'))
                                    <!-- Image Preview -->
                                    <div class="bg-gray-50 p-4 text-center">
                                        <img src="{{ Storage::url($file->path) }}" 
                                             alt="{{ $file->name }}" 
                                             class="max-w-full max-h-96 mx-auto rounded-lg shadow-sm">
                                    </div>
                                @elseif($file->mime_type === 'application/pdf')
                                    <!-- PDF Preview -->
                                    <div class="bg-gray-50 p-8 text-center">
                                        <svg class="mx-auto h-24 w-24 text-red-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <h4 class="text-lg font-medium text-gray-900 mb-2">PDF Document</h4>
                                        <p class="text-gray-600 mb-4">{{ $file->name }}</p>
                                        <a href="{{ route('portals.files.download', [$portal, $file]) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Open PDF
                                        </a>
                                    </div>
                                @elseif(in_array($file->mime_type, ['application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']))
                                    <!-- Word Document Preview -->
                                    <div class="bg-gray-50 p-8 text-center">
                                        <svg class="mx-auto h-24 w-24 text-blue-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <h4 class="text-lg font-medium text-gray-900 mb-2">Word Document</h4>
                                        <p class="text-gray-600 mb-4">{{ $file->name }}</p>
                                        <a href="{{ route('portals.files.download', [$portal, $file]) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Download Document
                                        </a>
                                    </div>
                                @else
                                    <!-- Generic File Preview -->
                                    <div class="bg-gray-50 p-8 text-center">
                                        <svg class="mx-auto h-24 w-24 text-gray-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <h4 class="text-lg font-medium text-gray-900 mb-2">{{ ucfirst(explode('/', $file->mime_type)[0]) }} File</h4>
                                        <p class="text-gray-600 mb-4">{{ $file->name }}</p>
                                        <a href="{{ route('portals.files.download', [$portal, $file]) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            Download File
                                        </a>
                                    </div>
                                @endif
                            </div>

                            @if($file->description)
                                <div class="mt-6">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                                    <p class="text-gray-600">{{ $file->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- File Details Sidebar -->
                <div class="space-y-6">
                    <!-- File Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">File Details</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">File Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $file->name }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">File Size</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ number_format($file->size / 1024, 1) }} KB</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">File Type</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $file->mime_type }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Uploaded</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $file->created_at->format('M j, Y \a\t g:i A') }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Uploaded By</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $file->uploader->name ?? 'Unknown' }}</dd>
                                </div>

                                @if($file->download_count > 0)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Downloads</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $file->download_count }}</dd>
                                    </div>
                                @endif

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Visibility</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $file->is_public ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $file->is_public ? 'Public' : 'Private' }}
                                        </span>
                                    </dd>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project Assignment -->
                    @if($file->project)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Project</h3>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white font-bold text-sm" 
                                         style="background-color: {{ $portal->primary_color ?? '#3B82F6' }}">
                                        {{ strtoupper(substr($file->project->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $file->project->name }}</p>
                                        <p class="text-sm text-gray-600">
                                            Status: 
                                            <span class="capitalize">{{ str_replace('_', ' ', $file->project->status) }}</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('portals.projects.show', [$portal, $file->project]) }}" 
                                        class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                        View Project Details →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Client Assignment -->
                    @if($file->client)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Client</h3>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ strtoupper(substr($file->client->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $file->client->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $file->client->email }}</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('portals.clients.show', [$portal, $file->client]) }}" 
                                        class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                        View Client Details →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- File Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Actions</h3>
                            <div class="space-y-3">
                                <a href="{{ route('portals.files.download', [$portal, $file]) }}" 
                                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Download
                                </a>

                                <a href="{{ route('portals.files.edit', [$portal, $file]) }}" 
                                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit Details
                                </a>

                                @if($file->is_public)
                                    <div class="pt-2 border-t border-gray-200">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Public Link</label>
                                        <div class="flex">
                                            <input type="text" readonly 
                                                   value="{{ route('portals.files.download', [$portal, $file]) }}" 
                                                   class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border-gray-300 focus:ring-blue-500 focus:border-blue-500 text-sm bg-gray-50"
                                                   id="public-link">
                                            <button type="button" 
                                                    onclick="copyToClipboard()"
                                                    class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 rounded-r-md bg-gray-50 text-gray-500 text-sm hover:bg-gray-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard() {
            const linkInput = document.getElementById('public-link');
            linkInput.select();
            linkInput.setSelectionRange(0, 99999); // For mobile devices
            
            try {
                document.execCommand('copy');
                
                // Show feedback
                const button = event.target.closest('button');
                const originalContent = button.innerHTML;
                button.innerHTML = '<svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
                
                setTimeout(() => {
                    button.innerHTML = originalContent;
                }, 2000);
            } catch (err) {
                console.error('Failed to copy: ', err);
            }
        }
    </script>
</x-app-layout>
