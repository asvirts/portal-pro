<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $portal->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ $portal->description }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ $portal->url }}" target="_blank" 
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium">
                    View Live Portal
                </a>
                <a href="{{ route('portals.edit', $portal) }}" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                    Edit Portal
                </a>
                <a href="{{ route('portals.index') }}" 
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium">
                    Back to Portals
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Portal Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-2.25"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Clients</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $portal->clients->count() }}</p>
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Projects</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $portal->projects->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Invoices</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $portal->invoices->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center" 
                                     style="background-color: {{ $portal->primary_color }}">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Status</p>
                                <p class="text-sm font-semibold {{ $portal->is_active ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $portal->is_active ? 'Active' : 'Inactive' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Portal Configuration -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Portal Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Portal Configuration</h3>
                        <dl class="grid grid-cols-1 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Portal URL</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <a href="{{ $portal->url }}" target="_blank" class="text-blue-600 hover:text-blue-500">
                                        {{ $portal->url }}
                                    </a>
                                </dd>
                            </div>
                            @if($portal->subdomain)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Subdomain</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $portal->subdomain }}.portalprohub.com</dd>
                                </div>
                            @endif
                            @if($portal->custom_domain)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Custom Domain</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $portal->custom_domain }}</dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Created</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $portal->created_at->format('M j, Y') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Branding Preview -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Branding Preview</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white font-bold text-xl" 
                                     style="background-color: {{ $portal->primary_color }}">
                                    {{ strtoupper(substr($portal->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $portal->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ Str::limit($portal->description, 50) }}</p>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <div class="w-4 h-4 rounded-full" style="background-color: {{ $portal->primary_color }}"></div>
                                <div class="w-4 h-4 rounded-full" style="background-color: {{ $portal->secondary_color }}"></div>
                                <span class="text-xs text-gray-500">Brand Colors</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Recent Projects</h3>
                        <button class="text-blue-600 hover:text-blue-500 text-sm font-medium">
                            View All Projects
                        </button>
                    </div>
                    
                    @if($portal->projects->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($portal->projects->take(5) as $project)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $project->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $project->client->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                    {{ $project->status === 'active' ? 'bg-green-100 text-green-800' : 
                                                       ($project->status === 'completed' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800') }}">
                                                    {{ ucfirst($project->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="flex items-center">
                                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                                        <div class="h-2 rounded-full" 
                                                             style="width: {{ $project->progress }}%; background-color: {{ $portal->primary_color }}"></div>
                                                    </div>
                                                    <span class="text-xs">{{ $project->progress }}%</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $project->updated_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No projects yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Start by adding your first project to this portal.</p>
                            <div class="mt-6">
                                <button class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Add Project
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
