@php
    $isManageContext = $isManageContext ?? false;
@endphp

<div class="flex flex-col lg:flex-row h-full min-h-0 w-full bg-white dark:bg-darkPanel text-gray-800 dark:text-gray-200">

    <div class="flex-1 min-h-0 flex flex-col overflow-y-auto overflow-x-hidden">

        <div class="px-4 sm:px-6 lg:px-8 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between gap-3 bg-white dark:bg-darkPanel flex-shrink-0">
            <a href="/" id="back-to-explore-btn" class="hidden text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-neon items-center gap-2 transition-colors bg-gray-50 dark:bg-darkBg border border-gray-200 dark:border-gray-800 px-4 py-2 rounded-lg shadow-sm hover:border-green-300 dark:hover:border-neon/30">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Explore
            </a>

            <div class="ml-auto flex items-center gap-2">
                <details class="relative">
                    <summary class="list-none cursor-pointer flex items-center gap-1.5 px-3 py-2 rounded-lg bg-gray-50 dark:bg-darkBg border border-gray-200 dark:border-gray-800 text-sm font-semibold text-gray-500 dark:text-gray-400 hover:border-red-300 hover:text-red-500 transition-all shadow-sm">
                        Report
                    </summary>
                    <div class="absolute right-0 mt-2 w-80 max-w-[calc(100vw-2rem)] z-30 bg-white dark:bg-darkPanel border border-gray-200 dark:border-gray-800 rounded-xl shadow-xl p-4">
                        @auth
                            <form method="POST" action="/models/{{ $model->id }}/report" class="space-y-3">
                                @csrf
                                <select name="reason" required class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-3 py-2 rounded-lg text-sm focus:outline-none focus:border-red-500">
                                    <option value="" disabled selected>Select reason...</option>
                                    <option value="stolen_content">Stolen content</option>
                                    <option value="inappropriate_content">Inappropriate content</option>
                                    <option value="spam">Spam or misleading</option>
                                    <option value="broken_file">Broken or unsafe file</option>
                                    <option value="wrong_category">Wrong category</option>
                                    <option value="other">Other</option>
                                </select>
                                <textarea name="description" rows="3" maxlength="1000" placeholder="Add context for moderators..."
                                    class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-3 py-2 rounded-lg text-sm resize-none focus:outline-none focus:border-red-500"></textarea>
                                <button type="submit" class="w-full bg-red-600 text-white font-semibold text-sm py-2 rounded-lg hover:bg-red-700 transition-colors">Submit Report</button>
                            </form>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">Please <a href="/login" class="text-green-600 dark:text-neon font-semibold hover:underline">login</a> to report this model.</p>
                        @endauth
                    </div>
                </details>

                <button type="button" 
                        onclick="copyModelUrl('{{ url('/models/' . $model->id) }}', this)"
                        class="flex items-center gap-2 px-4 py-2 bg-green-500 dark:bg-neon text-white dark:text-black font-semibold rounded-lg hover:bg-green-600 dark:hover:bg-[#00cc6a] transition-all shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 19c1.5-5.5 5.5-8.5 11-8.5V5l6 6-6 6v-5.5C10.5 11.5 6.8 14 4 19z" />
                    </svg>
                    <span class="share-text">Share</span>
                </button>
            </div>
        </div>

        <div class="w-full h-[46vh] min-h-[320px] lg:h-[58vh] bg-gray-100 dark:bg-black relative group flex-shrink-0">
            <model-viewer src="{{ $model->modelUrl() }}" loading="lazy" camera-controls auto-rotate shadow-intensity="1" class="w-full h-full outline-none"></model-viewer>
        </div>

        <div class="p-6 lg:p-8 flex flex-col gap-6">

            <div class="flex flex-col gap-5">
                <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-5">
                    <div class="min-w-0">
                        <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-gray-50 dark:bg-darkBg border border-gray-200 dark:border-gray-800 text-sm font-semibold text-gray-600 dark:text-gray-300 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $model->view_count }}
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white leading-tight">{{ $model->title }}</h1>
                        <div class="flex items-center gap-3 mt-3">
                            <a href="/creators/{{ $model->user->username }}" class="flex-shrink-0">
                                <img loading="lazy" src="{{ $model->user->profileImageUrl() ?? 'https://ui-avatars.com/api/?name='.urlencode($model->user->nickname ?? $model->user->username).'&background=e5e7eb&color=1f2937' }}"
                                    class="w-10 h-10 rounded-full object-cover ring-2 ring-green-600 dark:ring-neon">
                            </a>
                            <div class="min-w-0">
                                <a href="/creators/{{ $model->user->username }}" class="block font-bold text-gray-900 dark:text-white hover:text-green-600 dark:hover:text-neon leading-none truncate">{{ $model->user->nickname ?? $model->user->username }}</a>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1.5">{{ $model->user->models_count ?? 0 }} Models Published</p>
                            </div>
                            @if($model->user->upload_tier === 'verified')
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 flex-shrink-0 text-green-500 dark:text-neon" title="Verified Creator">
                                <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                            </svg>
                            @endif
                        </div>
                    </div>

                <div class="flex items-center gap-3 flex-wrap lg:justify-end">
                    <form method="POST" action="/models/{{ $model->id }}/star">
                        @csrf
                        <button class="flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-darkBg border border-gray-200 dark:border-gray-800 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:text-yellow-500 hover:border-yellow-300 dark:hover:text-yellow-400 transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                            </svg>
                            Star ({{ $model->stars_count }})
                        </button>
                    </form>

                    <a href="/models/{{ $model->id }}/download" class="flex items-center gap-2 px-4 py-2 bg-green-500 dark:bg-neon text-white dark:text-black font-semibold rounded-lg hover:bg-green-600 dark:hover:bg-[#00cc6a] transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Download ({{ $model->download_count }})
                    </a>

                    @if($isManageContext)
                    <button type="button" onclick="openViewerEditModal()" class="flex items-center gap-2 px-4 py-2 bg-yellow-400 text-black border border-yellow-300 font-semibold rounded-lg hover:bg-yellow-300 transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125 16.875 4.5" />
                        </svg>
                        Edit
                    </button>

                    <form method="POST" action="/models/{{ $model->id }}" onsubmit="return confirm('Delete this model permanently from your list?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="flex items-center gap-2 px-4 py-2 bg-red-600 text-white border border-red-700 font-semibold rounded-lg hover:bg-red-700 transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21H8.084a2.25 2.25 0 0 1-2.244-1.327L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                            Delete
                        </button>
                    </form>
                    @endif

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

            <div class="bg-gray-50 dark:bg-darkBg/50 border border-gray-100 dark:border-gray-800 rounded-2xl p-5 space-y-4">
                <div>
                    <h3 class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-2">About</h3>
                    <p class="text-sm leading-relaxed text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $model->description ?? 'No description available for this model.' }}</p>
                </div>

                <div class="flex flex-wrap gap-2">
                    @if(isset($model->category))
                        <a href="/?category={{ $model->category_id }}" 
                        class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-white dark:bg-gray-900/60 text-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-800 hover:bg-green-100 dark:hover:bg-neon/20 hover:text-green-600 dark:hover:text-neon transition-colors shadow-sm">
                            {{ $model->category->name }}
                        </a>
                    @endif

                    @foreach($model->tags as $tag)
                        <a href="/?tag={{ $tag->slug }}" 
                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium bg-white dark:bg-gray-900/60 text-gray-600 dark:text-gray-400 border border-gray-200 dark:border-gray-800 hover:border-green-400 dark:hover:border-neon/40 hover:text-green-600 dark:hover:text-neon transition-all shadow-sm">
                            <span class="text-gray-400">#</span>{{ $tag->name }}
                        </a>
                    @endforeach

                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-100 dark:border-gray-800/60">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-green-500 dark:text-neon"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.222 3.419.169A1.751 1.751 0 0 1 10.5 18v2.25a2.25 2.25 0 0 0 3.935 1.507l2.812-2.812A1.75 1.75 0 0 0 18.5 17.75c1.002-.012 1.996-.143 2.96-.39a1.75 1.75 0 0 0 1.29-1.666V8.25a1.75 1.75 0 0 0-1.75-1.75h-3.536a4.466 4.466 0 0 1-.52-.805 4.75 4.75 0 0 0-7.38 0c-.15.244-.325.513-.52.805H3.75A1.75 1.75 0 0 0 2 8.25v3.76Z" /></svg>
                    Comments ({{ $model->comments ? $model->comments->count() : 0 }})
                </h3>

                @auth
                <!-- Form Utama: Untuk Komentar Level Paling Atas (Parent Null) -->
                <form action="/models/{{ $model->id }}/comment" method="POST" class="mb-8">
                    @csrf
                    <div class="w-full bg-gray-50 dark:bg-black/30 border border-gray-200 dark:border-gray-800 rounded-xl focus-within:border-green-500 dark:focus-within:border-neon transition-all p-2">
                        <textarea name="body" rows="3" required placeholder="Write a constructive comment..." 
                            class="w-full bg-transparent border-0 resize-none outline-none focus:ring-0 px-3 py-2 text-sm text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"></textarea>
                        <div class="flex justify-end pt-2 border-t border-gray-200/60 dark:border-gray-800/50">
                            <button type="submit" class="bg-green-500 dark:bg-neon hover:bg-green-600 dark:hover:bg-[#00cc6a] text-white dark:text-black font-semibold text-xs px-4 py-2 rounded-lg shadow-sm transition-all">
                                Post Comment
                            </button>
                        </div>
                    </div>
                </form>
                @else
                <div class="mb-8 p-4 bg-gray-50 dark:bg-black/20 border border-gray-200 dark:border-gray-800 rounded-xl text-center text-sm text-gray-500">
                    Please <a href="/login" class="text-green-600 dark:text-neon font-semibold hover:underline">login</a> to participate in the discussion.
                </div>
                @endauth

                <!-- Daftar List Komentar Terstruktur -->
                <div class="space-y-4 max-h-[550px] overflow-y-auto pr-2">
                    @php
                        $allComments = $model->comments ?? collect();
                    @endphp

                    @forelse($allComments->where('parent_id', null) as $comment)
                        <div class="space-y-2 border-b border-gray-100 dark:border-gray-800/40 pb-4 last:border-0">
                            {{-- Memanggil komponen sub-view dengan melemparkan data yang dibutuhkan secara berantai --}}
                            @include('model.comment-item', ['comment' => $comment, 'allComments' => $allComments, 'modelId' => $model->id])
                        </div>
                    @empty
                    <div class="text-center py-8 text-gray-400 dark:text-gray-600 text-sm">
                        No comments yet. Be the first to share your thoughts!
                    </div>
                    @endforelse
                </div>
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

    @if($isManageContext)
    <div id="viewer-edit-model-modal" class="fixed inset-0 z-[140] hidden items-center justify-center bg-black/70 backdrop-blur-md p-4">
        <div class="relative w-full max-w-xl bg-white dark:bg-darkPanel p-6 sm:p-8 rounded-2xl border border-gray-200 dark:border-neon/20 shadow-xl dark:shadow-[0_0_40px_rgba(0,255,136,0.1)]" onclick="event.stopPropagation()">
            <button type="button" onclick="closeViewerEditModal()" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>

            <form method="POST" action="/models/{{ $model->id }}" class="flex flex-col gap-5">
                @csrf
                @method('PATCH')
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-wide">Edit <span class="text-green-600 dark:text-neon">Model</span></h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">The GLB file cannot be replaced here. Delete and upload again if the model file changes.</p>
                </div>

                <input type="text" name="title" value="{{ $model->title }}" required maxlength="255"
                    class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-neon focus:ring-1 focus:ring-neon transition-all">

                <textarea name="description" rows="4" maxlength="5000" placeholder="Description"
                    class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-neon focus:ring-1 focus:ring-neon transition-all resize-none">{{ $model->description }}</textarea>

                <select name="category_id" required
                    class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-neon focus:ring-1 focus:ring-neon transition-all">
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $model->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <input type="text" name="tags" value="{{ $model->tags->pluck('name')->implode(', ') }}" placeholder="Tags separated by comma"
                    class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-neon focus:ring-1 focus:ring-neon transition-all">

                <button type="submit" class="w-full bg-green-500 dark:bg-neon text-white dark:text-black font-semibold text-lg py-3 rounded-xl hover:bg-green-600 dark:hover:bg-[#00cc6a] transition-all">
                    Save Changes
                </button>
            </form>
        </div>
    </div>

    <script>
        function openViewerEditModal() {
            const modal = document.getElementById('viewer-edit-model-modal');
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeViewerEditModal() {
            const modal = document.getElementById('viewer-edit-model-modal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>
    @endif
</div>
