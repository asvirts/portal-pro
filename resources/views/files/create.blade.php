<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Upload Files') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ $portal->name }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('portals.files.index', $portal) }}" 
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-medium">
                    Back to Files
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('portals.files.store', $portal) }}" 
                          enctype="multipart/form-data" class="space-y-6" id="file-upload-form">
                        @csrf

                        <!-- File Upload Area -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Files</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors"
                                 id="file-drop-zone">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="files" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload files</span>
                                            <input id="files" name="files[]" type="file" class="sr-only" multiple required>
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, PDF, DOC, DOCX up to 10MB each</p>
                                </div>
                            </div>
                            @error('files.*')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Selected Files Preview -->
                        <div id="file-preview" class="hidden">
                            <h4 class="text-sm font-medium text-gray-700 mb-3">Selected Files</h4>
                            <div id="file-list" class="space-y-2"></div>
                        </div>

                        <!-- Project Assignment -->
                        <div>
                            <label for="project_id" class="block text-sm font-medium text-gray-700">Assign to Project (Optional)</label>
                            <select name="project_id" id="project_id" 
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Select a project...</option>
                                @foreach($projects as $project)
                                    <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
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
                                    <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
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
                                placeholder="Add a description for these files...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Privacy Settings -->
                        <div>
                            <div class="flex items-center">
                                <input type="checkbox" name="is_public" id="is_public" value="1" 
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                    {{ old('is_public') ? 'checked' : '' }}>
                                <label for="is_public" class="ml-2 block text-sm text-gray-900">
                                    Make files publicly accessible
                                </label>
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Public files can be accessed by clients without logging in
                            </p>
                            @error('is_public')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Upload Progress -->
                        <div id="upload-progress" class="hidden">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Uploading...</span>
                                <span class="text-sm text-gray-500" id="progress-text">0%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                     style="width: 0%" id="progress-bar"></div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('portals.files.index', $portal) }}" 
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </a>
                            <button type="submit" id="upload-btn"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="hidden animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" id="loading-spinner">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span id="upload-text">Upload Files</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for file upload functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('files');
            const fileDropZone = document.getElementById('file-drop-zone');
            const filePreview = document.getElementById('file-preview');
            const fileList = document.getElementById('file-list');
            const uploadForm = document.getElementById('file-upload-form');
            const uploadBtn = document.getElementById('upload-btn');
            const uploadText = document.getElementById('upload-text');
            const loadingSpinner = document.getElementById('loading-spinner');
            const uploadProgress = document.getElementById('upload-progress');
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');

            // Handle file input change
            fileInput.addEventListener('change', handleFiles);

            // Handle drag and drop
            fileDropZone.addEventListener('dragover', function(e) {
                e.preventDefault();
                fileDropZone.classList.add('border-blue-500', 'bg-blue-50');
            });

            fileDropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                fileDropZone.classList.remove('border-blue-500', 'bg-blue-50');
            });

            fileDropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                fileDropZone.classList.remove('border-blue-500', 'bg-blue-50');
                
                const files = e.dataTransfer.files;
                fileInput.files = files;
                handleFiles();
            });

            function handleFiles() {
                const files = fileInput.files;
                if (files.length > 0) {
                    displayFilePreview(files);
                } else {
                    filePreview.classList.add('hidden');
                }
            }

            function displayFilePreview(files) {
                fileList.innerHTML = '';
                
                Array.from(files).forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg';
                    
                    fileItem.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                ${getFileIcon(file.type)}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">${file.name}</p>
                                <p class="text-xs text-gray-500">${formatFileSize(file.size)}</p>
                            </div>
                        </div>
                        <button type="button" class="text-red-600 hover:text-red-700" onclick="removeFile(${index})">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    `;
                    
                    fileList.appendChild(fileItem);
                });
                
                filePreview.classList.remove('hidden');
            }

            function getFileIcon(mimeType) {
                if (mimeType.startsWith('image/')) {
                    return '<svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';
                } else if (mimeType === 'application/pdf') {
                    return '<svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
                } else {
                    return '<svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>';
                }
            }

            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Handle form submission
            uploadForm.addEventListener('submit', function(e) {
                uploadBtn.disabled = true;
                loadingSpinner.classList.remove('hidden');
                uploadText.textContent = 'Uploading...';
                uploadProgress.classList.remove('hidden');
                
                // Simulate progress (in a real app, you'd use XMLHttpRequest for actual progress)
                let progress = 0;
                const progressInterval = setInterval(() => {
                    progress += Math.random() * 15;
                    if (progress > 90) progress = 90;
                    
                    progressBar.style.width = progress + '%';
                    progressText.textContent = Math.round(progress) + '%';
                }, 200);
                
                // Clear interval when form actually submits
                setTimeout(() => {
                    clearInterval(progressInterval);
                    progressBar.style.width = '100%';
                    progressText.textContent = '100%';
                }, 2000);
            });

            // Global function to remove files
            window.removeFile = function(index) {
                const dt = new DataTransfer();
                const files = fileInput.files;
                
                for (let i = 0; i < files.length; i++) {
                    if (i !== index) {
                        dt.items.add(files[i]);
                    }
                }
                
                fileInput.files = dt.files;
                handleFiles();
            };
        });
    </script>
</x-app-layout>
