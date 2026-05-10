<div class="flex flex-col lg:flex-row h-full w-full bg-white dark:bg-darkPanel text-gray-800 dark:text-gray-200">

    <div class="flex-1 flex flex-col overflow-y-auto overflow-x-hidden">

        <div class="w-full h-[50vh] lg:h-[60vh] bg-gray-100 dark:bg-black relative group flex-shrink-0">
            <model-viewer src="{{ $model->modelUrl() }}" loading="lazy" camera-controls auto-rotate shadow-intensity="1" class="w-full h-full outline-none"></model-viewer>
        </div>

        <div class="p-6 lg:p-8 flex flex-col gap-8">

            <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white leading-tight flex-1">{{ $model->title }}</h1>

                <div class="flex items-center gap-3 flex-wrap lg:justify-end">
                    <form method="POST" action="/models/{{ $model->id }}/report">
                        @csrf
                        <button class="flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-800 text-sm font-medium text-gray-500 hover:text-red-500 hover:border-red-200 dark:hover:border-red-500/30 transition-colors" title="Report Model">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span class="hidden sm:inline">Report</span>
                        </button>
                    </form>

                    <div class="flex items-center gap-1.5 px-3 py-2 rounded-lg bg-gray-50 dark:bg-darkBg border border-gray-200 dark:border-gray-800 text-sm font-medium text-gray-600 dark:text-gray-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $model->view_count }}
                    </div>

                    <form method="POST" action="/models/{{ $model->id }}/star">
                        @csrf
                        <button class="flex items-center gap-1.5 px-3 py-2 rounded-lg border border-gray-200 dark:border-gray-800 text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-yellow-500 hover:border-yellow-300 dark:hover:text-yellow-400 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg>
                            {{ $model->stars_count }}
                        </button>
                    </form>

                    <a href="/models/{{ $model->id }}/download" class="flex items-center gap-2 px-4 py-2 bg-green-500 dark:bg-neon text-white dark:text-black font-semibold rounded-lg hover:bg-green-600 dark:hover:bg-[#00cc6a] transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Download ({{ $model->download_count }})
                    </a>

                    @can('moderator')
                    <form method="POST" action="/admin/delete-model/{{ $model->id }}" onsubmit="return confirm('Yakin ingin menghapus model ini secara permanen?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-red-100 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-600 dark:text-red-500 hover:bg-red-600 dark:hover:bg-red-600 hover:text-white dark:hover:text-white hover:border-red-600 dark:hover:border-red-600 rounded-xl font-bold transition-all duration-300 shadow-sm hover:shadow-red-500/20 active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                            Delete
                        </button>
                    </form>
                    @endcan
                </div>
            </div>

            <div class="flex items-center gap-4 py-2">
                <img loading="lazy" src="{{ $model->user->profileImageUrl() ?? 'https://ui-avatars.com/api/?name='.urlencode($model->user->nickname ?? $model->user->username).'&background=e5e7eb&color=1f2937' }}"
                    class="w-12 h-12 rounded-full object-cover ring-2 ring-green-600 dark:ring-neon">
                <div>
                    <p class="font-bold text-gray-900 dark:text-white leading-none">{{ $model->user->nickname ?? $model->user->username }}</p>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1.5">{{ $model->user->models_count ?? 0 }} Models Published</p>
                </div>
                @if($model->user->upload_tier === 'verified')
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-8 h-8 flex-shrink-0 text-green-500 dark:text-neon" title="Verified Creator">
                    <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                </svg>
                @endif
            </div>

            <div class="space-y-3">
                <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-400 ml-1">About this model</h3>
                <div class="bg-gray-50 dark:bg-darkBg/50 border border-gray-100 dark:border-gray-800 p-5 rounded-2xl text-sm leading-relaxed text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                    {{ $model->description ?? 'No description available for this model.' }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-2 ml-1">Category</span>
                    <span class="inline-block px-3 py-1.5 bg-green-50 dark:bg-neon/10 text-green-700 dark:text-neon text-xs font-semibold rounded-md border border-green-200 dark:border-neon/20">
                        {{ $model->category->name }}
                    </span>
                </div>

                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-2 ml-1">Tags</span>
                    <div class="flex flex-wrap gap-2">
                        @forelse($model->tags as $tag)
                        <span class="px-3 py-1.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-md border border-gray-200 dark:border-gray-700 shadow-sm">
                            {{ $tag->name }}
                        </span>
                        @empty
                        <span class="text-xs text-gray-500 italic">No tags</span>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="mt-4 pt-8 border-t border-gray-100 dark:border-gray-800">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Comments</h3>
                    <span class="text-xs text-gray-500 font-medium">0 Comments</span>
                </div>
                <div class="text-center py-12 text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-darkBg/30 rounded-2xl border border-dashed border-gray-200 dark:border-gray-800">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 mx-auto mb-3 opacity-20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.172 0 0012 3c-3.992 0-7.342.233-8.914.467-1.584.233-2.707 1.626-2.707 3.228v4.02z" />
                    </svg>
                    <p class="text-sm">Comments are coming soon...</p>
                </div>
            </div>

        </div>
    </div>

    @php
    $isFromPanel = Str::contains(request()->header('referer'), 'panel');
    @endphp

    @if(!$isFromPanel)
    <div class="w-full lg:w-80 flex-shrink-0 bg-gray-50 dark:bg-[#0c0c0e] border-l border-gray-200 dark:border-gray-800 flex flex-col relative">

        <div class="p-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between bg-gray-50 dark:bg-[#0c0c0e] z-10">
            <h3 class="font-bold text-xs text-gray-500 uppercase tracking-widest">More Like This</h3>

            <div class="flex items-center gap-2">
                <a href="/" id="back-to-explore-btn" class="hidden text-xs font-semibold text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-neon items-center gap-1.5 transition-colors bg-white dark:bg-darkBg border border-gray-200 dark:border-gray-800 px-3 py-1.5 rounded-lg shadow-sm hover:border-green-300 dark:hover:border-neon/30">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Explore
                </a>

                <button id="close-modal-btn" onclick="closeTop()" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:text-red-500 dark:hover:bg-red-500/10 transition-colors" title="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <div class="p-4 overflow-y-auto flex-1 space-y-4">
            @forelse($recommendations as $rec)
            <a href="/models/{{ $rec->id }}" onclick="handleModelClick('{{ $rec->id }}', event)" class="group flex flex-col bg-white dark:bg-darkPanel rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden hover:border-green-400 dark:hover:border-neon/40 shadow-sm hover:shadow-md transition-all">
                <div class="w-full h-32 bg-gray-200 dark:bg-black relative overflow-hidden">
                    <img src="{{ $rec->thumbnailUrl() }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
                <div class="p-3 text-center">
                    <p class="font-semibold text-sm text-gray-800 dark:text-gray-200 truncate group-hover:text-green-600 dark:group-hover:text-neon transition-colors">{{ $rec->title }}</p>
                    <p class="text-[10px] uppercase font-bold tracking-wider text-gray-400 mt-1 truncate">{{ $rec->category->name ?? 'Category' }}</p>
                </div>
            </a>
            @empty
            <p class="text-center text-xs text-gray-400 py-10">No similar models found.</p>
            @endforelse
        </div>

    </div>
    @endif
</div>