@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">My Portals</h1>
                    <p class="mt-2 text-gray-600">Manage your client portals and projects</p>
                </div>
                <a href="{{ route('portals.create') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Create Portal
                </a>
            </div>
        </div>

        @if($portals->count() > 0)
            <!-- Portals Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($portals as $portal)
                    <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                        <!-- Portal Header -->
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    @if($portal->logo)
                                        <img src="{{ Storage::url($portal->logo) }}" alt="{{ $portal->name }}" class="w-10 h-10 rounded-lg object-cover mr-3">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center mr-3">
                                            <span class="text-white font-bold text-lg">{{ substr($portal->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $portal->name }}</h3>
                                        @if($portal->domain)
                                            <p class="text-sm text-gray-500">{{ $portal->domain }}</p>
                                        @endif
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $portal->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                    {{ $portal->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            @if($portal->description)
                                <p class="mt-3 text-gray-600 text-sm">{{ Str::limit($portal->description, 100) }}</p>
                            @endif
                        </div>

                        <!-- Portal Stats -->
                        <div class="p-6">
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-blue-600">{{ $portal->clients()->count() }}</div>
                                    <div class="text-xs text-gray-500">Clients</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ $portal->projects()->count() }}</div>
                                    <div class="text-xs text-gray-500">Projects</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-purple-600">{{ $portal->invoices()->count() }}</div>
                                    <div class="text-xs text-gray-500">Invoices</div>
                                </div>
                            </div>

                            <!-- Recent Activity -->
                            <div class="mb-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Recent Activity</h4>
                                @php
                                    $recentClients = $portal->clients()->latest()->limit(2)->get();
                                    $recentProjects = $portal->projects()->latest()->limit(2)->get();
                                @endphp
                                
                                @if($recentClients->count() > 0 || $recentProjects->count() > 0)
                                    <div class="space-y-1">
                                        @foreach($recentClients as $client)
                                            <div class="text-xs text-gray-600">
                                                <span class="font-medium">{{ $client->name }}</span> joined {{ $client->created_at->diffForHumans() }}
                                            </div>
                                        @endforeach
                                        @foreach($recentProjects as $project)
                                            <div class="text-xs text-gray-600">
                                                Project <span class="font-medium">{{ $project->name }}</span> created {{ $project->created_at->diffForHumans() }}
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-xs text-gray-500">No recent activity</p>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-2">
                                <a href="{{ route('portals.show', $portal) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View
                                </a>
                                <a href="{{ route('portals.edit', $portal) }}" 
                                   class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($portals->hasPages())
                <div class="mt-8">
                    {{ $portals->links() }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-2m-2 0H7m5 0v-9a2 2 0 00-2-2H8a2 2 0 00-2 2v9m8 0V9a2 2 0 012-2h2a2 2 0 012 2v9M7 7h.01M7 10h.01M7 13h.01"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No portals</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating your first client portal.</p>
                <div class="mt-6">
                    <a href="{{ route('portals.create') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create Your First Portal
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
