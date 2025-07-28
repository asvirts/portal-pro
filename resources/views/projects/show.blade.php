<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $project->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ $portal->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('portals.projects.edit', [$portal, $project]) }}" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                    Edit Project
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
            <!-- Project Overview -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Main Project Info -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Project Details</h3>
                                <div class="flex items-center space-x-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $project->status === 'active' ? 'bg-green-100 text-green-800' : 
                                           ($project->status === 'completed' ? 'bg-blue-100 text-blue-800' : 
                                           ($project->status === 'on_hold' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($project->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $project->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                           ($project->priority === 'high' ? 'bg-orange-100 text-orange-800' : 
                                           ($project->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800')) }}">
                                        {{ ucfirst($project->priority) }} Priority
                                    </span>
                                </div>
                            </div>

                            @if($project->description)
                                <div class="mb-6">
                                    <h4 class="text-sm font-medium text-gray-700 mb-2">Description</h4>
                                    <p class="text-gray-600">{{ $project->description }}</p>
                                </div>
                            @endif

                            <!-- Progress Bar -->
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-2">
                                    <h4 class="text-sm font-medium text-gray-700">Progress</h4>
                                    <span class="text-sm text-gray-600">{{ $project->progress ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="h-3 rounded-full transition-all duration-300" 
                                         style="width: {{ $project->progress ?? 0 }}%; background-color: {{ $portal->primary_color ?? '#3B82F6' }}"></div>
                                </div>
                            </div>

                            <!-- Project Timeline -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                @if($project->start_date)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700">Start Date</h4>
                                        <p class="text-gray-600">{{ $project->start_date->format('M j, Y') }}</p>
                                    </div>
                                @endif
                                @if($project->due_date)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700">Due Date</h4>
                                        <p class="text-gray-600 {{ $project->due_date->isPast() && $project->status !== 'completed' ? 'text-red-600 font-medium' : '' }}">
                                            {{ $project->due_date->format('M j, Y') }}
                                            @if($project->due_date->isPast() && $project->status !== 'completed')
                                                <span class="text-xs">(Overdue)</span>
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <!-- Budget Information -->
                            @if($project->budget || $project->hourly_rate)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @if($project->budget)
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700">Budget</h4>
                                            <p class="text-gray-600">${{ number_format($project->budget, 2) }}</p>
                                        </div>
                                    @endif
                                    @if($project->hourly_rate)
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-700">Hourly Rate</h4>
                                            <p class="text-gray-600">${{ number_format($project->hourly_rate, 2) }}/hour</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Client Info -->
                    @if($project->client)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Client</h3>
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-gray-300 rounded-full flex items-center justify-center">
                                        <span class="text-sm font-medium text-gray-700">
                                            {{ strtoupper(substr($project->client->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $project->client->name }}</p>
                                        <p class="text-sm text-gray-600">{{ $project->client->email }}</p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('portals.clients.show', [$portal, $project->client]) }}" 
                                        class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                        View Client Details →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Quick Stats -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Stats</h3>
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Files</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $project->files->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Invoices</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $project->invoices->count() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Created</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $project->created_at->format('M j, Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Last Updated</span>
                                    <span class="text-sm font-medium text-gray-900">{{ $project->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Project Milestones -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Project Milestones</h3>
                        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium text-sm">
                            Add Milestone
                        </button>
                    </div>

                    <!-- Timeline -->
                    <div class="relative">
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                        
                        <!-- Sample milestones - this would be dynamic in a real implementation -->
                        <div class="space-y-6">
                            <div class="relative flex items-start space-x-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">Project Kickoff</h4>
                                    <p class="text-sm text-gray-600">Initial client meeting and requirements gathering completed</p>
                                    <p class="text-xs text-gray-500 mt-1">Completed 2 days ago</p>
                                </div>
                            </div>

                            <div class="relative flex items-start space-x-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <div class="w-3 h-3 bg-white rounded-full"></div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">Design Phase</h4>
                                    <p class="text-sm text-gray-600">Create wireframes and mockups for client approval</p>
                                    <p class="text-xs text-gray-500 mt-1">In progress</p>
                                </div>
                            </div>

                            <div class="relative flex items-start space-x-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    <div class="w-3 h-3 bg-white rounded-full"></div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">Development</h4>
                                    <p class="text-sm text-gray-600">Build the application based on approved designs</p>
                                    <p class="text-xs text-gray-500 mt-1">Upcoming</p>
                                </div>
                            </div>

                            <div class="relative flex items-start space-x-4">
                                <div class="flex-shrink-0 w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                    <div class="w-3 h-3 bg-white rounded-full"></div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">Testing & Launch</h4>
                                    <p class="text-sm text-gray-600">Quality assurance testing and deployment</p>
                                    <p class="text-xs text-gray-500 mt-1">Upcoming</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Files -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Recent Files</h3>
                            <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                View All Files
                            </button>
                        </div>
                        
                        @if($project->files->count() > 0)
                            <div class="space-y-3">
                                @foreach($project->files->take(5) as $file)
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $file->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $file->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No files uploaded yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Invoices -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Recent Invoices</h3>
                            <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                                View All Invoices
                            </button>
                        </div>
                        
                        @if($project->invoices->count() > 0)
                            <div class="space-y-3">
                                @foreach($project->invoices->take(5) as $invoice)
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Invoice #{{ $invoice->invoice_number }}</p>
                                            <p class="text-xs text-gray-500">{{ $invoice->created_at->format('M j, Y') }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-medium text-gray-900">${{ number_format($invoice->amount, 2) }}</p>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                                {{ $invoice->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                                   ($invoice->status === 'overdue' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                {{ ucfirst($invoice->status) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">No invoices created yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
