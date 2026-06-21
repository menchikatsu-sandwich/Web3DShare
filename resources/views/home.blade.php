@extends ('layouts.app')

@section ('content')
    @php
    $isMyModels = request('filter') == 'my_models';
@endphp
    <div class="mb-5 flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
        <div data-tour="home-title">
            @if ($isMyModels)
                <h2 class="text-3xl font-bold tracking-wide text-gray-900 dark:text-white">
                    My <span class="text-green-600 dark:text-neon">Models</span>
                </h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Manage and view all your uploaded 3D creations</p>
            @else
                <h2 class="text-3xl font-bold tracking-wide text-gray-900 dark:text-white">
                    Explore <span class="text-green-600 dark:text-neon">Models</span>
                </h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Discover and download the latest 3D creations from the community</p>
            @endif
        </div>

        <div class="flex flex-col gap-3 sm:flex-row lg:items-center">
            <form action="{{ url()->current() }}" method="GET" class="flex flex-col gap-3 sm:flex-row">
                @if ($isMyModels)
                    <input type="hidden" name="filter" value="my_models" />
                @endif
                @if (request('search'))
                    <input type="hidden" name="search" value="{{ request('search') }}" />
                @endif
                @if (request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}" />
                @endif
                @if (request('tag'))
                    <input type="hidden" name="tag" value="{{ request('tag') }}" />
                @endif

                <select
                    name="sort"
                    onchange="this.form.submit()"
                    class="w-full cursor-pointer rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm transition-all focus:border-green-500 focus:outline-none sm:w-44 dark:border-gray-800 dark:bg-darkBg dark:text-gray-300 dark:focus:border-neon"
                >
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest Uploads</option>
                    <option value="top_downloads" {{ request('sort') == 'top_downloads' ? 'selected' : '' }}
                        >Top Downloads
                    </option>
                    <option value="top_views" {{ request('sort') == 'top_views' ? 'selected' : '' }}>Top Views</option>
                    <option value="top_stars" {{ request('sort') == 'top_stars' ? 'selected' : '' }}>Top Stars</option>
                </select>

                <select
                    name="timeframe"
                    onchange="this.form.submit()"
                    class="w-full cursor-pointer rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 shadow-sm transition-all focus:border-green-500 focus:outline-none sm:w-40 dark:border-gray-800 dark:bg-darkBg dark:text-gray-300 dark:focus:border-neon"
                >
                    <option value="all_time" {{ request('timeframe') == 'all_time' ? 'selected' : '' }}
                        >All Time
                    </option>
                    <option value="this_month" {{ request('timeframe') == 'this_month' ? 'selected' : '' }}
                        >This Month
                    </option>
                    <option value="this_week" {{ request('timeframe') == 'this_week' ? 'selected' : '' }}
                        >This Week
                    </option>
                </select>
            </form>

            @if ($isMyModels)
                <a
                    href="/"
                    class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm text-gray-600 shadow-sm transition-colors hover:border-green-300 hover:text-green-600 dark:border-gray-800 dark:bg-darkBg dark:text-gray-400 dark:hover:border-neon/30 dark:hover:text-neon"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                    Back to Explore
                </a>
            @endif
        </div>
    </div>
    <div class="mb-6 space-y-3" data-tour="filters">
        <div
            class="no-scrollbar flex items-center gap-2 overflow-x-auto pb-2"
            style="scrollbar-width: none; -ms-overflow-style: none"
        >
            <style>
                .no-scrollbar::-webkit-scrollbar {
                    display: none;
                }
            </style>

            <a
                href="{{ request()->fullUrlWithQuery(['category' => null]) }}"
                class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ !request('category') ? 'bg-green-500 text-white dark:bg-neon dark:text-black shadow-md' : 'bg-gray-100 dark:bg-darkPanel/60 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800 border border-gray-200/50 dark:border-gray-800/50' }}"
            >
                All Categories
            </a>

            @foreach ($categories as $cat)
                @continue (!is_object($cat) || !isset($cat->id, $cat->name))
                <a
                    href="{{ request()->fullUrlWithQuery(['category' => $cat->id]) }}"
                    class="px-4 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition-all duration-200 {{ request('category') == $cat->id ? 'bg-green-500 text-white dark:bg-neon dark:text-black shadow-md' : 'bg-gray-100 dark:bg-darkPanel/60 text-gray-600 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-800 border border-gray-200/50 dark:border-gray-800/50' }}"
                >
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>

        @if (isset($tags) && $tags->count() > 0)
            <div class="flex flex-wrap items-center gap-1.5 text-xs text-gray-500 dark:text-gray-400">
                <span class="mr-1 flex items-center gap-1 font-medium text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-3 w-3"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581a1.43 1.43 0 0 0 2.022 0l4.318-4.318a1.43 1.43 0 0 0 0-2.022L10.15 3.659a2.25 2.25 0 0 0-1.591-.659Zm-2.318 5.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" /></svg>
                    Popular Tags:
                </span>

                @foreach ($tags as $t)
                    @continue (!is_object($t) || !isset($t->slug, $t->name))
                    <a
                        href="{{ request()->fullUrlWithQuery(['tag' => $t->slug]) }}"
                        class="px-2.5 py-0.5 rounded-md border text-[11px] font-medium transition-all {{ request('tag') == $t->slug ? 'border-green-500 bg-green-50/50 text-green-600 dark:border-neon dark:bg-neon/10 dark:text-neon' : 'border-gray-200 dark:border-gray-800 hover:border-gray-300 dark:hover:border-gray-700 bg-gray-50/30 dark:bg-darkPanel/30' }}"
                    >
                        #{{ $t->name }}
                    </a>
                @endforeach

                @if (request('tag') || request('category') || request('search'))
                    <a
                        href="/"
                        class="ml-2 flex items-center gap-0.5 text-[11px] font-bold text-red-500 transition-colors hover:text-red-600 dark:hover:text-red-400"
                    >
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
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse ($models as $model)
            <div
                @if ($loop->first) data-tour="model-card" @endif
                data-model-card="{{ $model->id }}"
                class="group flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:border-green-400 hover:shadow-md dark:border-gray-800 dark:bg-darkBg dark:hover:border-neon/40 dark:hover:shadow-[0_0_20px_rgba(0,255,136,0.1)]"
            >
                <a
                    @if ($loop->first) data-tour="model-open" @endif
                    href="/models/{{ $model->id }}{{ $isMyModels ? '?from=my_models' : '' }}"
                    onclick="openModel('{{ $model->id }}', event)"
                    class="relative block h-48 w-full overflow-hidden bg-gray-100 dark:bg-black"
                >
                    <img
                        src="{{ $model->thumbnailUrl() }}"
                        class="h-full w-full object-cover opacity-90 transition-all duration-500 group-hover:scale-110 group-hover:opacity-100"
                    />
                    @if (isset($model->category))
                        <div
                            class="absolute top-3 right-3 rounded-md border border-gray-200 bg-white/90 px-2.5 py-1 text-[10px] font-bold tracking-widest text-gray-700 uppercase shadow-sm backdrop-blur-md dark:border-gray-700 dark:bg-black/70 dark:text-gray-300"
                        >
                            {{ $model->category->name }}
                        </div>
                    @endif
                </a>

                <div class="flex flex-1 flex-col p-5">
                    <a href="/models/{{ $model->id }}{{ $isMyModels ? '?from=my_models' : '' }}" class="mb-3 block">
                        <h3
                            class="truncate text-lg font-bold text-gray-800 transition-colors group-hover:text-green-600 dark:text-gray-100 dark:group-hover:text-neon"
                            title="{{ $model->title }}"
                        >
                            {{ $model->title }}
                        </h3>
                    </a>

                    <div class="mt-auto mb-4 flex items-center gap-3">
                        <a href="/creators/{{ $model->user->username }}" class="flex-shrink-0">
                            <img
                                loading="lazy"
                                src="{{ $model->user->profileImageUrl() ?? 'https://ui-avatars.com/api/?name=' . urlencode($model->user->nickname ?? $model->user->username) . '&background=e5e7eb&color=1f2937' }}"
                                class="h-7 w-7 rounded-full object-cover ring-2 ring-transparent transition-all group-hover:ring-green-300 dark:group-hover:ring-neon/30"
                            />
                        </a>

                        <div class="flex min-w-0 items-center gap-1.5">
                            <a
                                href="/creators/{{ $model->user->username }}"
                                class="truncate text-sm font-medium text-gray-600 transition-colors hover:text-green-600 dark:text-gray-400 dark:hover:text-gray-200"
                                title="{{ $model->user->nickname ?? $model->user->username }}"
                            >
                                {{ $model->user->nickname ?? $model->user->username }}
                            </a>

                            @if ($model->user->upload_tier === 'verified')
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-4 w-4 flex-shrink-0 text-green-500 dark:text-neon" title="Verified Creator">
                                    <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                    </div>

                    @if ($isMyModels && auth()->id() === $model->user_id)
                        <div class="mb-4 grid grid-cols-2 gap-3">
                            <button
                                type="button"
                                onclick="openModelEditModal('edit-model-{{ $model->id }}')"
                                class="flex items-center justify-center gap-2 rounded-lg border border-yellow-300 bg-yellow-400 px-3 py-2 text-sm font-bold text-black shadow-sm transition-all hover:bg-yellow-300"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125 16.875 4.5" />
                                </svg>
                                Edit
                            </button>

                            <form
                                method="POST"
                                action="/models/{{ $model->id }}"
                                onsubmit="return confirm('Delete this model permanently from your list?');"
                            >
                                @csrf
                                @method ('DELETE')
                                <button
                                    type="submit"
                                    class="flex w-full items-center justify-center gap-2 rounded-lg border border-red-700 bg-red-600 px-3 py-2 text-sm font-bold text-white shadow-sm transition-all hover:bg-red-700"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21H8.084a2.25 2.25 0 0 1-2.244-1.327L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                        <div
                            id="edit-model-{{ $model->id }}"
                            class="fixed inset-0 z-[120] hidden items-center justify-center bg-black/70 p-4 backdrop-blur-md"
                        >
                            <div
                                class="relative w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6 shadow-xl sm:p-8 dark:border-neon/20 dark:bg-darkPanel dark:shadow-[0_0_40px_rgba(0,255,136,0.1)]"
                            >
                                <button
                                    type="button"
                                    onclick="closeModelEditModal('edit-model-{{ $model->id }}')"
                                    class="absolute top-4 right-4 text-gray-400 transition-colors hover:text-red-500"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                                </button>

                                <form method="POST" action="/models/{{ $model->id }}" class="flex flex-col gap-5">
                                    @csrf
                                    @method ('PATCH')
                                    <div>
                                        <h2 class="text-3xl font-bold tracking-wide text-gray-900 dark:text-white">
                                            Edit <span class="text-green-600 dark:text-neon">Model</span>
                                        </h2>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Only title, description, category, and tags can be changed.</p>
                                    </div>

                                    <input
                                        type="text"
                                        name="title"
                                        value="{{ $model->title }}"
                                        required
                                        maxlength="255"
                                        class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-all focus:border-neon focus:ring-1 focus:ring-neon focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200"
                                    />

                                    <textarea
                                        name="description"
                                        rows="4"
                                        maxlength="5000"
                                        placeholder="Description"
                                        class="w-full resize-none rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-all focus:border-neon focus:ring-1 focus:ring-neon focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200"
                                        >{{ $model->description }}</textarea
                                    >

                                    <select
                                        name="category_id"
                                        required
                                        class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-all focus:border-neon focus:ring-1 focus:ring-neon focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200"
                                    >
                                        @foreach ($categories as $cat)
                                            @continue (!is_object($cat) || !isset($cat->id, $cat->name))
                                            <option
                                                value="{{ $cat->id }}"
                                                {{ $model->category_id == $cat->id ? 'selected' : '' }}
                                                >{{ $cat->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <input
                                        type="text"
                                        name="tags"
                                        value="{{ $model->tags->pluck('name')->implode(', ') }}"
                                        placeholder="Tags separated by comma"
                                        class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-all focus:border-neon focus:ring-1 focus:ring-neon focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200"
                                    />

                                    <button
                                        type="submit"
                                        class="w-full rounded-xl bg-green-500 py-3 text-lg font-semibold text-white transition-all hover:bg-green-600 dark:bg-neon dark:text-black dark:hover:bg-[#00cc6a]"
                                    >
                                        Save Changes
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <div
                        class="flex items-center justify-between border-t border-gray-100 pt-4 text-xs font-semibold text-gray-500 dark:border-gray-800/80"
                    >
                        <div
                            @if ($loop->first) data-tour="model-views" @endif
                            class="flex items-center gap-1.5 transition-colors hover:text-green-600 dark:hover:text-neon"
                            title="{{ $model->view_count }} Views"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $model->view_count }}
                        </div>
                        <div
                            @if ($loop->first) data-tour="model-stars" @endif
                            class="flex items-center gap-1.5 transition-colors hover:text-yellow-500 dark:hover:text-yellow-400"
                            title="{{ $model->stars_count }} Stars"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                            <span data-card-star-count>{{ $model->stars_count }}</span>
                        </div>
                        <div
                            @if ($loop->first) data-tour="model-downloads" @endif
                            class="flex items-center gap-1.5 transition-colors hover:text-blue-500 dark:hover:text-blue-400"
                            title="{{ $model->download_count }} Downloads"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                            <span data-card-download-count>{{ $model->download_count }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div
                class="col-span-full flex flex-col items-center justify-center rounded-2xl border border-gray-200 bg-white py-20 text-center shadow-sm dark:border-gray-800 dark:bg-darkPanel"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="mb-4 h-16 w-16 text-gray-400 dark:text-gray-600"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                <p class="text-lg text-gray-500 dark:text-gray-400">No models found.</p>
                @if ($isMyModels)
                    <a href="/upload" class="mt-4 font-medium text-green-600 hover:underline dark:text-neon"
                        >Upload your first model now!</a
                    >
                @endif
            </div>
        @endforelse
    </div>
    <div class="mt-10">{{ $models->appends(request()->query())->links() }}</div>
    @if ($isMyModels)
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

            document.addEventListener('keydown', function (event) {
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
