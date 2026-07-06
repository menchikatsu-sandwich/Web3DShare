@extends ('layouts.app')

@section ('content')
    @php
    $displayName = $user->nickname ?? $user->username;
@endphp
    <div class="-m-3 min-h-[calc(100dvh-65px)] bg-gray-100 sm:-m-6 lg:-m-8 dark:bg-darkBg">
        <section
            class="relative overflow-hidden border-b border-gray-200 bg-white dark:border-gray-800 dark:bg-[#07120d]"
        >
            <div class="creator-hero-pattern absolute inset-0"></div>
            <div
                class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/70 to-green-50/80 dark:from-black/45 dark:via-black/15 dark:to-green-950/30"
            ></div>
            <div
                class="relative flex flex-col gap-4 px-4 py-7 sm:flex-row sm:items-center sm:gap-7 sm:px-6 sm:py-10 lg:px-10 lg:py-12"
            >
                <img
                    src="{{ $user->profileImageUrl() ?? 'https://ui-avatars.com/api/?name=' . urlencode($displayName) . '&background=111113&color=00ff88' }}"
                    class="h-20 w-20 rounded-xl border border-gray-200 object-cover shadow-xl sm:h-28 sm:w-28 dark:border-white/10"
                />

                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-3">
                        <h1
                            class="max-w-full text-2xl font-semibold break-words text-gray-950 sm:text-4xl dark:text-white"
                        >
                            {{ $displayName }}
                        </h1>
                        @if ($user->upload_tier === 'verified')
                            <span
                                class="rounded-lg bg-green-500 px-3 py-1 text-xs font-bold tracking-wider text-white uppercase dark:bg-neon dark:text-black"
                                >Verified</span
                            >
                        @endif
                    </div>
                    <p class="mt-2 text-gray-600 dark:text-gray-300">{{ '@' . $user->username }}</p>
                    <p class="mt-3 max-w-xl text-sm text-gray-700 sm:mt-4 sm:text-base dark:text-gray-300">Creator on Web3DShare sharing downloadable 3D models with the community.</p>

                    <div class="mt-4 flex flex-wrap gap-2 text-xs sm:mt-5 sm:gap-3 sm:text-sm">
                        <span
                            class="rounded-lg border border-green-200 bg-white/80 px-3 py-1.5 text-gray-900 shadow-sm dark:border-white/10 dark:bg-white/10 dark:text-white"
                            >{{ $stats['models'] }} Models</span
                        >
                        <span
                            class="rounded-lg border border-green-200 bg-white/80 px-3 py-1.5 text-gray-900 shadow-sm dark:border-white/10 dark:bg-white/10 dark:text-white"
                            >{{ number_format($stats['views']) }} Views</span
                        >
                        <span
                            class="rounded-lg border border-green-200 bg-white/80 px-3 py-1.5 text-gray-900 shadow-sm dark:border-white/10 dark:bg-white/10 dark:text-white"
                            >{{ number_format($stats['stars']) }} Stars</span
                        >
                    </div>
                </div>
            </div>
        </section>

        <section class="px-3 py-5 sm:px-6 sm:py-8 lg:px-10">
            <div class="grid grid-cols-1 items-start gap-8 xl:grid-cols-[1fr_320px]">
                <div>
                    <div class="mb-4 flex items-center justify-between gap-4 sm:mb-5">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white">Models</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $models->total() }} published models</p>
                        </div>
                    </div>

                    <div class="creator-model-grid grid grid-cols-1 gap-4 sm:grid-cols-2 sm:gap-6 lg:grid-cols-3">
                        @forelse ($models as $model)
                            <div
                                class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-all hover:border-green-400 hover:shadow-md dark:border-gray-800 dark:bg-darkPanel dark:hover:border-neon/40"
                            >
                                <a
                                    href="/models/{{ $model->id }}"
                                    onclick="openModel('{{ $model->id }}', event)"
                                    class="relative block aspect-[4/3] w-full overflow-hidden bg-gray-100 sm:aspect-auto sm:h-44 dark:bg-black"
                                >
                                    <img
                                        loading="lazy"
                                        src="{{ $model->thumbnailUrl() }}"
                                        class="h-full w-full object-cover opacity-90 transition-all duration-500 group-hover:scale-105 group-hover:opacity-100"
                                    />
                                    @if ($model->category)
                                        <div
                                            class="absolute top-3 right-3 rounded-md border border-gray-200 bg-white/90 px-2.5 py-1 text-[10px] font-bold tracking-widest text-gray-700 uppercase shadow-sm backdrop-blur-md dark:border-gray-700 dark:bg-black/70 dark:text-gray-300"
                                        >
                                            {{ $model->category->name }}
                                        </div>
                                    @endif
                                </a>

                                <div class="flex flex-1 flex-col p-3 sm:p-4">
                                    <a
                                        href="/models/{{ $model->id }}"
                                        class="truncate font-bold text-gray-800 transition-colors hover:text-green-600 dark:text-gray-100 dark:hover:text-neon"
                                        title="{{ $model->title }}"
                                    >
                                        {{ $model->title }}
                                    </a>

                                    <div
                                        class="mt-3 flex items-center justify-between border-t border-gray-100 pt-3 text-[11px] font-semibold text-gray-500 sm:mt-4 sm:pt-4 sm:text-xs dark:border-gray-800"
                                    >
                                        <span class="flex items-center gap-1.5" title="{{ $model->view_count }} Views">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $model->view_count }}
                                        </span>
                                        <span class="flex items-center gap-1.5" title="{{ $model->stars_count }} Stars">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                                            {{ $model->stars_count }}
                                        </span>
                                        <span
                                            class="flex items-center gap-1.5"
                                            title="{{ $model->download_count }} Downloads"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                            {{ $model->download_count }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div
                                class="col-span-full rounded-xl border border-gray-200 bg-white py-16 text-center text-gray-500 dark:border-gray-800 dark:bg-darkPanel dark:text-gray-400"
                            >
                                No models published yet.
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-8">{{ $models->links() }}</div>
                </div>

                <aside class="space-y-4 sm:space-y-6">
                    <div
                        class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-darkPanel"
                    >
                        <h3 class="mb-4 text-xs font-bold tracking-widest text-gray-400 uppercase">About</h3>
                        <div class="space-y-4 text-sm">
                            <div>
                                <p class="mb-1 text-xs font-semibold tracking-wider text-gray-400 uppercase">Category</p>
                                <p class="text-gray-800 dark:text-gray-200">{{ $user->isStaff() ? ucfirst($user->role).' Staff' : ucfirst($user->upload_tier).' Creator' }}</p>
                            </div>
                            <div>
                                <p class="mb-1 text-xs font-semibold tracking-wider text-gray-400 uppercase">Role</p>
                                <p class="text-gray-800 dark:text-gray-200">{{ ucfirst($user->role) }}</p>
                            </div>
                            <div>
                                <p class="mb-1 text-xs font-semibold tracking-wider text-gray-400 uppercase">Member since</p>
                                <p class="text-gray-800 dark:text-gray-200">{{ $user->created_at?->format('F j, Y') ?? 'Unknown' }}</p>
                            </div>
                        </div>
                    </div>

                    <div
                        class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6 dark:border-gray-800 dark:bg-darkPanel"
                    >
                        <h3 class="mb-4 text-xs font-bold tracking-widest text-gray-400 uppercase">Stats</h3>
                        <div class="space-y-3 text-sm font-semibold text-gray-800 dark:text-gray-200">
                            <p>{{ number_format($stats['views']) }} views</p>
                            <p>{{ number_format($stats['stars']) }} stars</p>
                            <p>{{ number_format($stats['downloads']) }} downloads</p>
                            <p>{{ number_format($stats['models']) }} models</p>
                        </div>
                    </div>
                </aside>
            </div>
        </section>
    </div>
@endsection
