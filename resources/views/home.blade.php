@extends('layouts.app')

@section('content')
@php
    $isMyModels = request('filter') == 'my_models';
@endphp
<div class="mb-5 flex flex-col lg:flex-row lg:items-end justify-between gap-4">
    <div>
        @if($isMyModels)
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-wide">My <span class="text-green-600 dark:text-neon">Models</span></h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">Manage and view all your uploaded 3D creations</p>
        @else
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-wide">Explore <span class="text-green-600 dark:text-neon">Models</span></h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">Discover and download the latest 3D creations from the community</p>
        @endif
    </div>
    
    <div class="flex flex-col sm:flex-row gap-3 lg:items-center">
        <form action="{{ url()->current() }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            @if($isMyModels)
                <input type="hidden" name="filter" value="my_models">
            @endif
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            @if(request('tag'))
                <input type="hidden" name="tag" value="{{ request('tag') }}">
            @endif

            <select name="sort" onchange="this.form.submit()" 
                class="w-full sm:w-44 px-3 py-2 text-sm bg-white dark:bg-darkBg border border-gray-200 dark:border-gray-800 text-gray-700 dark:text-gray-300 rounded-xl focus:outline-none focus:border-green-500 dark:focus:border-neon cursor-pointer transition-all shadow-sm">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Uploads</option>
                <option value="top_downloads" {{ request('sort') == 'top_downloads' ? 'selected' : '' }}>Top Downloads</option>
                <option value="top_views" {{ request('sort') == 'top_views' ? 'selected' : '' }}>Top Views</option>
                <option value="top_stars" {{ request('sort') == 'top_stars' ? 'selected' : '' }}>Top Stars</option>
            </select>

            <select name="timeframe" onchange="this.form.submit()" 
                class="w-full sm:w-40 px-3 py-2 text-sm bg-white dark:bg-darkBg border border-gray-200 dark:border-gray-800 text-gray-700 dark:text-gray-300 rounded-xl focus:outline-none focus:border-green-500 dark:focus:border-neon cursor-pointer transition-all shadow-sm">
                <option value="all_time" {{ request('timeframe') == 'all_time' ? 'selected' : '' }}>All Time</option>
                <option value="this_month" {{ request('timeframe') == 'this_month' ? 'selected' : '' }}>This Month</option>
                <option value="this_week" {{ request('timeframe') == 'this_week' ? 'selected' : '' }}>This Week</option>
            </select>
        </form>

        @if($isMyModels)
        <a href="/" class="text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-neon flex items-center gap-2 transition-colors bg-white dark:bg-darkBg border border-gray-200 dark:border-gray-800 px-4 py-2 rounded-lg shadow-sm hover:border-green-300 dark:hover:border-neon/30">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Back to Explore
        </a>
        @endif
    </div>
</div>

<div class="mb-6 space-y-3">
    <div class="flex items-center gap-2 overflow-x-auto pb-2 no-scrollbar" style="scrollbar-width: none; -ms-overflow-style: none;">
        <style>
            .no-scrollbar::-webkit-scrollbar { display: none; }
        </style>
        
        <a href="{{ request()->fullUrlWithQuery(['category' => null]) }}" 
           class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ !request('category') ? 'bg-green-500 text-white dark:bg-neon dark:text-black shadow-md' : 'bg-gray-100 dark:bg-darkPanel/60 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800 border border-gray-200/50 dark:border-gray-800/50' }}">
            All Categories
        </a>
        
        @foreach($categories as $cat)
        <a href="{{ request()->fullUrlWithQuery(['category' => $cat->id]) }}" 
           class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ request('category') == $cat->id ? 'bg-green-500 text-white dark:bg-neon dark:text-black shadow-md' : 'bg-gray-100 dark:bg-darkPanel/60 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800 border border-gray-200/50 dark:border-gray-800/50' }}">
            {{ $cat->name }}
        </a>
        @endforeach
    </div>

    @if(isset($tags) && $tags->count() > 0)
    <div class="flex flex-wrap items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
        <span class="font-medium mr-1 flex items-center gap-1 text-gray-400">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a1.43 1.43 0 0 0 2.022 0l4.318-4.318a1.43 1.43 0 0 0 0-2.022L10.15 3.659a2.25 2.25 0 0 0-1.591-.659Zm-2.318 5.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
            Popular Tags:
        </span>
        
        @foreach($tags as $t)
        <a href="{{ request()->fullUrlWithQuery(['tag' => $t->slug]) }}" 
           class="px-2.5 py-0.5 rounded-md border text-[11px] font-medium transition-all {{ request('tag') == $t->slug ? 'border-green-500 bg-green-50/50 text-green-600 dark:border-neon dark:bg-neon/10 dark:text-neon' : 'border-gray-200 dark:border-gray-800 hover:border-gray-300 dark:hover:border-gray-700 bg-gray-50/30 dark:bg-darkPanel/30' }}">
            #{{ $t->name }}
        </a>
        @endforeach
        
        @if(request('tag') || request('category') || request('search'))
        <a href="/" class="ml-2 text-[11px] font-bold text-red-500 hover:text-red-600 dark:hover:text-red-400 transition-colors flex items-center gap-0.5">
            ✕ Clear Filters
        </a>
        @endif
    </div>
    @endif
</div>

{{-- Tampilan Clear Lama --}}
{{-- @if(request('category') || request('tag') || request('search'))
<div class="mb-4 flex flex-wrap gap-2 items-center">
    <span class="text-xs text-gray-400 font-medium">Active filters:</span>
    
    @if(request('search'))
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
            Search: "{{ request('search') }}"
        </span>
    @endif

    @if(request('category'))
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium bg-green-50 dark:bg-neon/10 text-green-700 dark:text-neon border border-green-200 dark:border-neon/20">
            Category Filter
        </span>
    @endif

    @if(request('tag'))
        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-md text-xs font-medium bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20">
            Tag: #{{ request('tag') }}
        </span>
    @endif

    <a href="/" class="text-xs font-bold text-red-500 hover:text-red-600 dark:hover:text-red-400 transition-colors ml-2">
        Clear All X
    </a>
</div>
@endif --}}

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($models as $model)
    <div class="bg-white dark:bg-darkBg border border-gray-200 dark:border-gray-800 rounded-2xl overflow-hidden hover:border-green-400 dark:hover:border-neon/40 shadow-sm hover:shadow-md dark:hover:shadow-[0_0_20px_rgba(0,255,136,0.1)] transition-all duration-300 group flex flex-col">
        
        <a href="/models/{{ $model->id }}{{ $isMyModels ? '?from=my_models' : '' }}" onclick="openModel('{{ $model->id }}', event)" class="relative w-full h-48 overflow-hidden block bg-gray-100 dark:bg-black">
            <img src="{{ $model->thumbnailUrl() }}" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
            @if(isset($model->category))
            <div class="absolute top-3 right-3 bg-white/90 dark:bg-black/70 backdrop-blur-md border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-[10px] uppercase font-bold tracking-widest px-2.5 py-1 rounded-md shadow-sm">
                {{ $model->category->name }}
            </div>
            @endif
        </a>

        <div class="p-5 flex flex-col flex-1">
            <a href="/models/{{ $model->id }}{{ $isMyModels ? '?from=my_models' : '' }}" class="block mb-3">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 group-hover:text-green-600 dark:group-hover:text-neon transition-colors truncate" title="{{ $model->title }}">
                    {{ $model->title }}
                </h3>
            </a>

            <div class="flex items-center gap-3 mt-auto mb-4">
                <a href="/creators/{{ $model->user->username }}" class="flex-shrink-0">
                    <img loading="lazy" src="{{ $model->user->profileImageUrl() ?? 'https://ui-avatars.com/api/?name=' . urlencode($model->user->nickname ?? $model->user->username) . '&background=e5e7eb&color=1f2937' }}"
                         class="w-7 h-7 rounded-full object-cover ring-2 ring-transparent group-hover:ring-green-300 dark:group-hover:ring-neon/30 transition-all">
                </a>
                
                <div class="flex items-center gap-1.5 min-w-0">
                    <a href="/creators/{{ $model->user->username }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-gray-200 transition-colors truncate" title="{{ $model->user->nickname ?? $model->user->username }}">
                        {{ $model->user->nickname ?? $model->user->username }}
                    </a>
                    
                    @if($model->user->upload_tier === 'verified')
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 flex-shrink-0 text-green-500 dark:text-neon" title="Verified Creator">
                            <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </div>
            </div>

            @if($isMyModels && auth()->id() === $model->user_id)
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <button type="button" onclick="openModelEditModal('edit-model-{{ $model->id }}')" class="flex items-center justify-center gap-2 bg-yellow-400 text-black border border-yellow-300 hover:bg-yellow-300 px-3 py-2 rounded-lg text-sm font-bold transition-all shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125 16.875 4.5" />
                        </svg>
                        Edit
                    </button>

                    <form method="POST" action="/models/{{ $model->id }}" onsubmit="return confirm('Delete this model permanently from your list?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full flex items-center justify-center gap-2 bg-red-600 text-white border border-red-700 hover:bg-red-700 px-3 py-2 rounded-lg text-sm font-bold transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21H8.084a2.25 2.25 0 0 1-2.244-1.327L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>

                <div id="edit-model-{{ $model->id }}" class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/70 backdrop-blur-md p-4">
                    <div class="relative w-full max-w-xl bg-white dark:bg-darkPanel p-6 sm:p-8 rounded-2xl border border-gray-200 dark:border-neon/20 shadow-xl dark:shadow-[0_0_40px_rgba(0,255,136,0.1)]">
                        <button type="button" onclick="closeModelEditModal('edit-model-{{ $model->id }}')" class="absolute top-4 right-4 text-gray-400 hover:text-red-500 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                        </button>

                        <form method="POST" action="/models/{{ $model->id }}" class="flex flex-col gap-5">
                            @csrf
                            @method('PATCH')
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-wide">Edit <span class="text-green-600 dark:text-neon">Model</span></h2>
                                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Only title, description, category, and tags can be changed.</p>
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
            @endif

            <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-800/80 text-gray-500 text-xs font-semibold">
                <div class="flex items-center gap-1.5 hover:text-green-600 dark:hover:text-neon transition-colors" title="{{ $model->view_count }} Views">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    {{ $model->view_count }}
                </div>
                <div class="flex items-center gap-1.5 hover:text-yellow-500 dark:hover:text-yellow-400 transition-colors" title="{{ $model->stars_count }} Stars">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                    {{ $model->stars_count }}
                </div>
                <div class="flex items-center gap-1.5 hover:text-blue-500 dark:hover:text-blue-400 transition-colors" title="{{ $model->download_count }} Downloads">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                    {{ $model->download_count }}
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 text-center flex flex-col items-center justify-center bg-white dark:bg-darkPanel border border-gray-200 dark:border-gray-800 rounded-2xl shadow-sm">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-gray-400 dark:text-gray-600 mb-4"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
        <p class="text-gray-500 dark:text-gray-400 text-lg">No models found.</p>
        @if($isMyModels)
            <a href="/upload" class="mt-4 text-green-600 dark:text-neon font-medium hover:underline">Upload your first model now!</a>
        @endif
    </div>
    @endforelse
</div>

<div class="mt-10">
    {{ $models->appends(request()->query())->links() }}
</div>

@if($isMyModels)
<script>
    function openModelEditModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModelEditModal(id) {
        const modal = document.getElementById(id);
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function(event) {
        if (event.key !== 'Escape') return;
        document.querySelectorAll('[id^="edit-model-"]').forEach((modal) => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });
        document.body.style.overflow = '';
    });
</script>
@endif
@endsection
