<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit File') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ $file->name }} - {{ $portal->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('portals.files.show', [$portal, $file]) }}" 
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium">
                    View File
                </a>
                <a href="{{ route('portals.files.index', $portal) }}" 
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium">
                    Back to Files
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('portals.files.update', [$portal, $file]) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- File Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">File Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $file->name) }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Project Assignment -->
                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700">Assign to Project (Optional)</label>
                            <select name="project_id" id="project_id" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Select a project...</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id', $file->project_id) == $project->id ? 'selected' : '' }}>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Client Assignment -->
                        <div>
                            <label for="client_id" class="block text-sm font-medium text-gray-700">Assign to Client (Optional)</label>
                            <select name="client_id" id="client_id" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Select a client...</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id', $file->client_id) == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} ({{ $client->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                            <textarea name="description" id="description" rows="3" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder="Add a description for this file...">{{ old('description', $file->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Privacy Settings -->
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_public" id="is_public" value="1" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    {{ old('is_public', $file->is_public) ? 'checked' : '' }}>
                                <label for="is_public" class="ml-2 block text-sm text-gray-900">
                                    Make file publicly accessible
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Public files can be accessed by clients without logging in
                            </p>
                            @error('is_public')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-between">
                            <form method="POST" action="{{ route('portals.files.destroy', [$portal, $file]) }}" 
                                onsubmit="return confirm('Are you sure you want to delete this file? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Delete File
                                </button>
                            </form>

                            <div class="flex space-x-3">
                                <a href="{{ route('portals.files.show', [$portal, $file]) }}" 
                                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Cancel
                                </a>
                                <button type="submit" 
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Update File
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
