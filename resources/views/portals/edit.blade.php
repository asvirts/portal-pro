@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Edit Portal') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ $portal->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('portals.show', $portal) }}" 
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium">
                    View Portal
                </a>
                <a href="{{ route('portals.index') }}" 
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium">
                    Back to Portals
                </a>
            </div>
        </div>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('portals.update', $portal) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Portal Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Portal Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $portal->name) }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder="My Awesome Portal" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder="Describe your portal's purpose...">{{ old('description', $portal->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subdomain -->
                        <div>
                            <label for="subdomain" class="block text-sm font-medium text-gray-700">Subdomain (Optional)</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain', $portal->subdomain) }}" 
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-l-md border-gray-300 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                    placeholder="myportal">
                                <span class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 sm:text-sm">
                                    .portalprohub.com
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Leave blank to use default URL structure</p>
                            @error('subdomain')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Custom Domain -->
                        <div>
                            <label for="custom_domain" class="block text-sm font-medium text-gray-700">Custom Domain (Optional)</label>
                            <input type="text" name="custom_domain" id="custom_domain" value="{{ old('custom_domain', $portal->custom_domain) }}" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                placeholder="portal.yourdomain.com">
                            <p class="mt-1 text-sm text-gray-500">Use your own domain for the portal</p>
                            @error('custom_domain')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Branding Colors -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="primary_color" class="block text-sm font-medium text-gray-700">Primary Color</label>
                                <div class="mt-1 flex items-center space-x-3">
                                    <input type="color" name="primary_color" id="primary_color" value="{{ old('primary_color', $portal->primary_color ?? '#3B82F6') }}" 
                                        class="h-10 w-20 border border-gray-300 rounded-md cursor-pointer">
                                    <input type="text" value="{{ old('primary_color', $portal->primary_color ?? '#3B82F6') }}" 
                                        class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                        readonly>
                                </div>
                                @error('primary_color')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="secondary_color" class="block text-sm font-medium text-gray-700">Secondary Color</label>
                                <div class="mt-1 flex items-center space-x-3">
                                    <input type="color" name="secondary_color" id="secondary_color" value="{{ old('secondary_color', $portal->secondary_color ?? '#1F2937') }}" 
                                        class="h-10 w-20 border border-gray-300 rounded-md cursor-pointer">
                                    <input type="text" value="{{ old('secondary_color', $portal->secondary_color ?? '#1F2937') }}" 
                                        class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" 
                                        readonly>
                                </div>
                                @error('secondary_color')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Preview Section -->
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Portal Preview</h3>
                            <div class="bg-gray-50 rounded-lg p-6" id="portal-preview">
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="w-12 h-12 rounded-lg flex items-center justify-center text-white font-bold text-xl" 
                                         style="background-color: var(--primary-color, {{ $portal->primary_color ?? '#3B82F6' }})">
                                        {{ strtoupper(substr($portal->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900" id="preview-name">{{ $portal->name }}</h4>
                                        <p class="text-sm text-gray-600" id="preview-description">{{ $portal->description ?: 'Portal description will appear here' }}</p>
                                    </div>
                                </div>
                                <div class="grid grid-cols-3 gap-4">
                                    <div class="bg-white p-3 rounded-lg shadow-sm">
                                        <div class="w-8 h-8 rounded-full mb-2" style="background-color: var(--primary-color, {{ $portal->primary_color ?? '#3B82F6' }})"></div>
                                        <p class="text-sm font-medium text-gray-900">Projects</p>
                                        <p class="text-xs text-gray-500">{{ $portal->projects->count() }} active</p>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg shadow-sm">
                                        <div class="w-8 h-8 rounded-full mb-2" style="background-color: var(--secondary-color, {{ $portal->secondary_color ?? '#1F2937' }})"></div>
                                        <p class="text-sm font-medium text-gray-900">Files</p>
                                        <p class="text-xs text-gray-500">{{ $portal->files->count() }} uploaded</p>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg shadow-sm">
                                        <div class="w-8 h-8 rounded-full mb-2" style="background-color: var(--primary-color, {{ $portal->primary_color ?? '#3B82F6' }})"></div>
                                        <p class="text-sm font-medium text-gray-900">Invoices</p>
                                        <p class="text-xs text-gray-500">{{ $portal->invoices->count() }} total</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('portals.show', $portal) }}" 
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Update Portal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for live preview -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nameInput = document.getElementById('name');
            const descriptionInput = document.getElementById('description');
            const primaryColorInput = document.getElementById('primary_color');
            const secondaryColorInput = document.getElementById('secondary_color');
            
            const previewName = document.getElementById('preview-name');
            const previewDescription = document.getElementById('preview-description');
            const previewElement = document.getElementById('portal-preview');
            
            function updatePreview() {
                const name = nameInput.value || '{{ $portal->name }}';
                const description = descriptionInput.value || '{{ $portal->description ?: "Portal description will appear here" }}';
                const primaryColor = primaryColorInput.value;
                const secondaryColor = secondaryColorInput.value;
                
                previewName.textContent = name;
                previewDescription.textContent = description;
                
                previewElement.style.setProperty('--primary-color', primaryColor);
                previewElement.style.setProperty('--secondary-color', secondaryColor);
                
                // Update color input text fields
                primaryColorInput.nextElementSibling.value = primaryColor;
                secondaryColorInput.nextElementSibling.value = secondaryColor;
            }
            
            nameInput.addEventListener('input', updatePreview);
            descriptionInput.addEventListener('input', updatePreview);
            primaryColorInput.addEventListener('input', updatePreview);
            secondaryColorInput.addEventListener('input', updatePreview);
            
            // Initial preview update
            updatePreview();
        });
    </script>
    </div>
</div>
@endsection
