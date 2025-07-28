<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit Project') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ $project->name }} - {{ $portal->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('portals.projects.show', [$portal, $project]) }}" 
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium">
                    View Project
                </a>
                <a href="{{ route('portals.show', $portal) }}" 
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium">
                    Back to Portal
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('portals.projects.update', [$portal, $project]) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Project Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Project Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $project->name) }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder="Website Redesign" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="4" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder="Describe the project scope and objectives...">{{ old('description', $project->description) }}</textarea>
                            @error('description')
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
                                    <option value="{{ $client->id }}" {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }} ({{ $client->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status and Priority -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                    <option value="planning" {{ old('status', $project->status) == 'planning' ? 'selected' : '' }}>Planning</option>
                                    <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="on_hold" {{ old('status', $project->status) == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                    <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                                <select name="priority" id="priority" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                                    <option value="low" {{ old('priority', $project->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                    <option value="medium" {{ old('priority', $project->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                    <option value="high" {{ old('priority', $project->priority) == 'high' ? 'selected' : '' }}>High</option>
                                    <option value="urgent" {{ old('priority', $project->priority) == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                </select>
                                @error('priority')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Progress -->
                        <div>
                            <label for="progress" class="block text-sm font-medium text-gray-700">Progress (%)</label>
                            <div class="mt-1 flex items-center space-x-3">
                                <input type="range" name="progress" id="progress" min="0" max="100" 
                                    value="{{ old('progress', $project->progress ?? 0) }}" 
                                    class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                                <span id="progress-value" class="text-sm font-medium text-gray-900 min-w-[3rem]">
                                    {{ old('progress', $project->progress ?? 0) }}%
                                </span>
                            </div>
                            @error('progress')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" name="start_date" id="start_date" 
                                    value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                                <input type="date" name="due_date" id="due_date" 
                                    value="{{ old('due_date', $project->due_date?->format('Y-m-d')) }}" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                @error('due_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Budget and Hourly Rate -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="budget" class="block text-sm font-medium text-gray-700">Budget ($)</label>
                                <input type="number" name="budget" id="budget" 
                                    value="{{ old('budget', $project->budget) }}" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                    placeholder="5000" step="0.01" min="0">
                                @error('budget')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="hourly_rate" class="block text-sm font-medium text-gray-700">Hourly Rate ($)</label>
                                <input type="number" name="hourly_rate" id="hourly_rate" 
                                    value="{{ old('hourly_rate', $project->hourly_rate) }}" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                    placeholder="75" step="0.01" min="0">
                                @error('hourly_rate')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-between">
                            <form method="POST" action="{{ route('portals.projects.destroy', [$portal, $project]) }}" 
                                onsubmit="return confirm('Are you sure you want to delete this project? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    Delete Project
                                </button>
                            </form>

                            <div class="flex space-x-3">
                                <a href="{{ route('portals.projects.show', [$portal, $project]) }}" 
                                    class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Cancel
                                </a>
                                <button type="submit" 
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Update Project
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for progress slider -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const progressSlider = document.getElementById('progress');
            const progressValue = document.getElementById('progress-value');
            
            progressSlider.addEventListener('input', function() {
                progressValue.textContent = this.value + '%';
            });
        });
    </script>
</x-app-layout>
