@extends ('layouts.app')

@push ('head')
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js"></script>
@endpush

@section ('content')
    <div
        class="fixed inset-0 z-[100] flex items-start justify-center overflow-y-auto bg-black/60 p-4 pt-10 pb-10 backdrop-blur-md"
    >
        <div
            data-tour="upload-panel"
            class="relative w-full max-w-2xl rounded-2xl border border-gray-200 bg-white p-8 shadow-xl transition-colors duration-300 dark:border-neon/20 dark:bg-darkPanel dark:shadow-[0_0_40px_rgba(0,255,136,0.1)]"
        >
            <a
                href="/"
                class="absolute top-4 right-4 text-gray-400 transition-colors hover:text-green-600 dark:hover:text-neon"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </a>

            <form
                method="POST"
                action="/models"
                enctype="multipart/form-data"
                class="flex flex-col gap-5"
                id="uploadForm"
            >
                @csrf
                <div class="mb-4">
                    <h2 class="text-3xl font-bold tracking-wide text-gray-900 dark:text-white">
                        Upload <span class="text-green-600 dark:text-neon">Model</span>
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Share your 3D creation with the world</p>
                </div>

                @if ($isVerifiedUploader)
                    <div
                        data-tour="upload-limit"
                        class="flex items-center justify-between gap-3 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-green-800 dark:border-neon/20 dark:bg-neon/10 dark:text-neon"
                    >
                        <div>
                            <p class="text-sm font-semibold">Verified uploader</p>
                            <p class="mt-0.5 text-xs text-green-700 dark:text-neon/80">Unlimited uploads are active for your account.</p>
                        </div>
                        <span
                            class="rounded-lg bg-green-100 px-3 py-1 text-xs font-bold tracking-wider uppercase dark:bg-neon/20"
                            >Unlimited</span
                        >
                    </div>
                @else
                    <div
                        data-tour="upload-limit"
                        class="{{ $remainingUploads > 0 ? 'bg-green-50 dark:bg-neon/10 border-green-200 dark:border-neon/20 text-green-800 dark:text-neon' : 'bg-red-50 dark:bg-red-900/30 border-red-300 dark:border-red-700 text-red-700 dark:text-red-200' }} border px-4 py-3 rounded-xl"
                    >
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm font-semibold">Monthly upload limit</p>
                                <p
                                    class="text-xs mt-0.5 {{ $remainingUploads > 0 ? 'text-green-700 dark:text-neon/80' : 'text-red-600 dark:text-red-200/80' }}"
                                >
                                    {{ $monthlyUploads }} of {{ $monthlyLimit }} uploads used this month. {{ $remainingUploads }} remaining.
                                </p>
                            </div>
                            <span
                                class="text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-lg {{ $remainingUploads > 0 ? 'bg-green-100 dark:bg-neon/20' : 'bg-red-100 dark:bg-red-800/50' }}"
                            >
                                {{ $remainingUploads }} left
                            </span>
                        </div>

                        @if ($remainingUploads <= 0)
                            <div class="mt-3 border-t border-red-200 pt-3 text-sm dark:border-red-700/60">
                                <p>You have reached this month&apos;s upload limit. Try again on {{ $nextUploadReset->format('F j, Y') }} or request verified uploader access.</p>
                                <a
                                    href="/verify"
                                    class="mt-2 inline-flex text-sm font-semibold underline underline-offset-4 hover:text-red-800 dark:hover:text-white"
                                    >Request verified access</a
                                >
                            </div>
                        @endif
                    </div>
                @endif

                @if ($errors->any())
                    <div
                        class="rounded-xl border border-red-400 bg-red-100 px-4 py-3 text-red-700 dark:border-red-700 dark:bg-red-900/30 dark:text-red-200"
                    >
                        <strong>Error:</strong>
                        <ul class="ml-5 list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div
                    id="uploadError"
                    class="hidden rounded-xl border border-red-400 bg-red-100 px-4 py-3 text-red-700 dark:border-red-700 dark:bg-red-900/30 dark:text-red-200"
                ></div>

                <div>
                    <label
                        class="mb-1 ml-1 block text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                        >Title</label
                    >
                    <input
                        name="title"
                        placeholder="Name your model..."
                        required
                        class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-all focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200 dark:focus:border-neon dark:focus:ring-neon"
                    />
                </div>

                <div>
                    <label
                        class="mb-1 ml-1 block text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                        >Description</label
                    >
                    <textarea
                        name="description"
                        placeholder="Tell us about this model..."
                        rows="3"
                        required
                        class="w-full resize-none rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-all focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200 dark:focus:border-neon dark:focus:ring-neon"
                    ></textarea>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label
                            class="mb-1 ml-1 block text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Category</label
                        >
                        <select
                            name="category_id"
                            required
                            class="w-full appearance-none rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-all focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200 dark:focus:border-neon dark:focus:ring-neon"
                        >
                            <option value="" disabled selected>Select category...</option>
                            @foreach ($categories as $c)
                                @continue (!is_object($c) || !isset($c->id, $c->name))
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label
                            class="mb-1 ml-1 block text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Tags</label
                        >
                        <input
                            name="tags"
                            placeholder="e.g. car, city, lowpoly"
                            class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-all focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200 dark:focus:border-neon dark:focus:ring-neon"
                        />
                    </div>
                </div>

                <div>
                    <label
                        class="mb-1 ml-1 block text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                        >3D Model (.glb)</label
                    >
                    <input
                        type="file"
                        name="model"
                        id="modelInput"
                        accept=".glb"
                        required
                        class="block w-full cursor-pointer rounded-xl border border-gray-300 bg-gray-50 text-sm text-gray-500 transition-all file:mr-4 file:rounded-xl file:border-0 file:bg-green-100 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-green-700 hover:file:bg-green-200 dark:border-gray-800 dark:bg-darkBg dark:text-gray-400 dark:file:bg-neon/10 dark:file:text-neon dark:hover:file:bg-neon/20"
                    />

                    <div
                        id="modelPreviewContainer"
                        class="relative mt-3 hidden h-64 w-full overflow-hidden rounded-xl border border-gray-300 bg-gray-100 dark:border-neon/20 dark:bg-black/50"
                    >
                        <model-viewer
                            id="modelViewer"
                            alt="3D Preview"
                            auto-rotate
                            camera-controls
                            shadow-intensity="1"
                            class="h-full w-full"
                        ></model-viewer>
                    </div>
                </div>

                <div>
                    <label
                        class="mb-1 ml-1 block text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                        >Thumbnail Image</label
                    >
                    <input
                        type="file"
                        name="thumbnail"
                        id="thumbInput"
                        accept="image/*"
                        required
                        class="block w-full cursor-pointer rounded-xl border border-gray-300 bg-gray-50 text-sm text-gray-500 transition-all file:mr-4 file:rounded-xl file:border-0 file:bg-green-100 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-green-700 hover:file:bg-green-200 dark:border-gray-800 dark:bg-darkBg dark:text-gray-400 dark:file:bg-neon/10 dark:file:text-neon dark:hover:file:bg-neon/20"
                    />

                    <div
                        id="thumbPreviewContainer"
                        class="mt-3 flex hidden h-40 w-full items-center justify-center overflow-hidden rounded-xl border border-gray-300 bg-gray-100 dark:border-gray-800 dark:bg-black/50"
                    >
                        <img id="thumbPreview" class="max-h-full max-w-full object-contain" />
                    </div>
                </div>

                <x-ui.button
                    type="submit"
                    size="lg"
                    block
                    class="mt-4 shadow-md dark:shadow-[0_0_15px_rgba(0,255,136,0.3)]"
                    :disabled="!$isVerifiedUploader && $remainingUploads <= 0"
                >
                    {{ !$isVerifiedUploader && $remainingUploads <= 0 ? 'Upload Limit Reached' : 'Upload Model' }}
                </x-ui.button>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modelInput = document.getElementById('modelInput');
            const modelViewer = document.getElementById('modelViewer');
            const modelContainer = document.getElementById('modelPreviewContainer');
            modelInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (file) {
                    modelViewer.src = URL.createObjectURL(file);
                    modelContainer.classList.remove('hidden');
                }
            });
            const thumbInput = document.getElementById('thumbInput');
            const thumbPreview = document.getElementById('thumbPreview');
            const thumbContainer = document.getElementById('thumbPreviewContainer');
            thumbInput.addEventListener('change', function (event) {
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
                            Accept: 'application/json',
                        },
                        body: formData,
                    });
                    const data = await response.json().catch(() => null);

                    if (!response.ok) {
                        console.error('Upload failed', {
                            status: response.status,
                            statusText: response.statusText,
                            response: data,
                        });

                        const validationErrors = data?.errors ? Object.values(data.errors).flat().join(' ') : null;
                        throw new Error(
                            validationErrors || data?.message || data?.error || `Upload failed (${response.status})`,
                        );
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
