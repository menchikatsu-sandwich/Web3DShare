@extends('layouts.app')

@push('head')
<script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js"></script>
@endpush

@section('content')

<div class="fixed inset-0 z-[100] flex items-start justify-center bg-black/60 backdrop-blur-md p-4 overflow-y-auto pt-10 pb-10">
    <div data-tour="upload-panel" class="relative w-full max-w-2xl bg-white dark:bg-darkPanel p-8 rounded-2xl border border-gray-200 dark:border-neon/20 shadow-xl dark:shadow-[0_0_40px_rgba(0,255,136,0.1)] transition-colors duration-300">
        
        <a href="/" class="absolute top-4 right-4 text-gray-400 hover:text-green-600 dark:hover:text-neon transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </a>

        <form method="POST" action="/models" enctype="multipart/form-data" class="flex flex-col gap-5" id="uploadForm">
            @csrf
            <div class="mb-4">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-wide">Upload <span class="text-green-600 dark:text-neon">Model</span></h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Share your 3D creation with the world</p>
            </div>

            @if($isVerifiedUploader)
                <div data-tour="upload-limit" class="bg-green-50 dark:bg-neon/10 border border-green-200 dark:border-neon/20 text-green-800 dark:text-neon px-4 py-3 rounded-xl flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold">Verified uploader</p>
                        <p class="text-xs text-green-700 dark:text-neon/80 mt-0.5">Unlimited uploads are active for your account.</p>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider bg-green-100 dark:bg-neon/20 px-3 py-1 rounded-lg">Unlimited</span>
                </div>
            @else
                <div data-tour="upload-limit" class="{{ $remainingUploads > 0 ? 'bg-green-50 dark:bg-neon/10 border-green-200 dark:border-neon/20 text-green-800 dark:text-neon' : 'bg-red-50 dark:bg-red-900/30 border-red-300 dark:border-red-700 text-red-700 dark:text-red-200' }} border px-4 py-3 rounded-xl">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="text-sm font-semibold">Monthly upload limit</p>
                            <p class="text-xs mt-0.5 {{ $remainingUploads > 0 ? 'text-green-700 dark:text-neon/80' : 'text-red-600 dark:text-red-200/80' }}">
                                {{ $monthlyUploads }} of {{ $monthlyLimit }} uploads used this month. {{ $remainingUploads }} remaining.
                            </p>
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-lg {{ $remainingUploads > 0 ? 'bg-green-100 dark:bg-neon/20' : 'bg-red-100 dark:bg-red-800/50' }}">
                            {{ $remainingUploads }} left
                        </span>
                    </div>

                    @if($remainingUploads <= 0)
                        <div class="mt-3 pt-3 border-t border-red-200 dark:border-red-700/60 text-sm">
                            <p>You have reached this month&apos;s upload limit. Try again on {{ $nextUploadReset->format('F j, Y') }} or request verified uploader access.</p>
                            <a href="/verify" class="inline-flex mt-2 text-sm font-semibold underline underline-offset-4 hover:text-red-800 dark:hover:text-white">Request verified access</a>
                        </div>
                    @endif
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded-xl">
                    <strong>Error:</strong>
                    <ul class="list-disc ml-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div id="uploadError" class="hidden bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded-xl"></div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1 ml-1">Title</label>
                <input name="title" placeholder="Name your model..." required
                       class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-green-500 dark:focus:border-neon focus:ring-1 focus:ring-green-500 dark:focus:ring-neon transition-all">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1 ml-1">Description</label>
                <textarea name="description" placeholder="Tell us about this model..." rows="3" required
                          class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-green-500 dark:focus:border-neon focus:ring-1 focus:ring-green-500 dark:focus:ring-neon transition-all resize-none"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1 ml-1">Category</label>
                    <select name="category_id" required
                            class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-green-500 dark:focus:border-neon focus:ring-1 focus:ring-green-500 dark:focus:ring-neon transition-all appearance-none">
                        <option value="" disabled selected>Select category...</option>
                        @foreach($categories as $c)
                            @continue(!is_object($c) || !isset($c->id, $c->name))
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1 ml-1">Tags</label>
                    <input name="tags" placeholder="e.g. car, city, lowpoly"
                           class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-green-500 dark:focus:border-neon focus:ring-1 focus:ring-green-500 dark:focus:ring-neon transition-all">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1 ml-1">3D Model (.glb)</label>
                <input type="file" name="model" id="modelInput" accept=".glb" required
                       class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-neon/10 file:text-green-700 dark:file:text-neon hover:file:bg-green-200 dark:hover:file:bg-neon/20 transition-all bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 rounded-xl cursor-pointer">
                
                <div id="modelPreviewContainer" class="hidden mt-3 w-full h-64 bg-gray-100 dark:bg-black/50 border border-gray-300 dark:border-neon/20 rounded-xl overflow-hidden relative">
                    <model-viewer id="modelViewer" alt="3D Preview" auto-rotate camera-controls shadow-intensity="1" class="w-full h-full"></model-viewer>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1 ml-1">Thumbnail Image</label>
                <input type="file" name="thumbnail" id="thumbInput" accept="image/*" required
                       class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-green-100 dark:file:bg-neon/10 file:text-green-700 dark:file:text-neon hover:file:bg-green-200 dark:hover:file:bg-neon/20 transition-all bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 rounded-xl cursor-pointer">
                
                <div id="thumbPreviewContainer" class="hidden mt-3 w-full h-40 bg-gray-100 dark:bg-black/50 border border-gray-300 dark:border-gray-800 rounded-xl overflow-hidden flex items-center justify-center">
                    <img id="thumbPreview" class="max-w-full max-h-full object-contain">
                </div>
            </div>

            <button type="submit" @if(!$isVerifiedUploader && $remainingUploads <= 0) disabled @endif
                    class="w-full bg-green-500 dark:bg-neon text-white dark:text-black font-semibold text-lg py-3 rounded-xl mt-4 hover:bg-green-600 dark:hover:bg-[#00cc6a] shadow-md dark:shadow-[0_0_15px_rgba(0,255,136,0.3)] transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-green-500 dark:disabled:hover:bg-neon">
                {{ !$isVerifiedUploader && $remainingUploads <= 0 ? 'Upload Limit Reached' : 'Upload Model' }}
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modelInput = document.getElementById('modelInput');
        const modelViewer = document.getElementById('modelViewer');
        const modelContainer = document.getElementById('modelPreviewContainer');
        modelInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                modelViewer.src = URL.createObjectURL(file);
                modelContainer.classList.remove('hidden');
            }
        });
        const thumbInput = document.getElementById('thumbInput');
        const thumbPreview = document.getElementById('thumbPreview');
        const thumbContainer = document.getElementById('thumbPreviewContainer');
        thumbInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                thumbPreview.src = URL.createObjectURL(file);
                thumbContainer.classList.remove('hidden');
            }
        });

        const form = document.getElementById('uploadForm');
        const submitBtn = form.querySelector('button[type="submit"]');
        const uploadError = document.getElementById('uploadError');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            uploadError.classList.add('hidden');

            const formData = new FormData(form);
            submitBtn.disabled = true;
            submitBtn.textContent = 'Uploading...';

            try {
                const response = await fetch('/models', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: formData
                });
                const data = await response.json().catch(() => null);

                if (!response.ok) {
                    console.error('Upload failed', {
                        status: response.status,
                        statusText: response.statusText,
                        response: data
                    });

                    const validationErrors = data?.errors
                        ? Object.values(data.errors).flat().join(' ')
                        : null;
                    throw new Error(validationErrors || data?.message || data?.error || `Upload failed (${response.status})`);
                }

                window.location.href = '/';
            } catch (err) {
                uploadError.textContent = err.message;
                uploadError.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.textContent = 'Upload Model';
            }
        });
    });
</script>
@endsection
