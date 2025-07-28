<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ $client->name }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    Client in {{ $portal->name }}
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('portals.clients.edit', [$portal, $client]) }}" 
                   class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit Client
                </a>
                <a href="{{ route('portals.clients.index', $portal) }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Clients
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Client Header -->
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xl">
                            {{ $client->initials }}
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">{{ $client->name }}</h3>
                            <p class="text-gray-600">{{ $client->email }}</p>
                            @if($client->company)
                                <p class="text-sm text-gray-500">{{ $client->company }}</p>
                            @endif
                        </div>
                        <div class="ml-auto">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $client->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $client->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <!-- Client Details Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Contact Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-lg mb-3">Contact Information</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="font-medium text-gray-700">Email:</span>
                                    <span class="text-gray-900">{{ $client->email }}</span>
                                </div>
                                @if($client->phone)
                                    <div>
                                        <span class="font-medium text-gray-700">Phone:</span>
                                        <span class="text-gray-900">{{ $client->phone }}</span>
                                    </div>
                                @endif
                                @if($client->address)
                                    <div>
                                        <span class="font-medium text-gray-700">Address:</span>
                                        <span class="text-gray-900">{{ $client->address }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Account Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-lg mb-3">Account Information</h4>
                            <div class="space-y-2">
                                <div>
                                    <span class="font-medium text-gray-700">Status:</span>
                                    <span class="text-gray-900">{{ $client->is_active ? 'Active' : 'Inactive' }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Email Verified:</span>
                                    <span class="text-gray-900">{{ $client->email_verified_at ? 'Yes' : 'No' }}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-gray-700">Joined:</span>
                                    <span class="text-gray-900">{{ $client->created_at->format('M j, Y') }}</span>
                                </div>
                                @if($client->last_login_at)
                                    <div>
                                        <span class="font-medium text-gray-700">Last Login:</span>
                                        <span class="text-gray-900">{{ $client->last_login_at->format('M j, Y g:i A') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4 pt-6 border-t border-gray-200">
                        <form method="POST" action="{{ route('portals.clients.invite', [$portal, $client]) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Send Invitation
                            </button>
                        </form>
                        
                        <a href="{{ route('portals.clients.edit', [$portal, $client]) }}" 
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Client
                        </a>
                        
                        <form method="POST" action="{{ route('portals.clients.destroy', [$portal, $client]) }}" 
                              class="inline" 
                              onsubmit="return confirm('Are you sure you want to delete this client?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                Delete Client
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
