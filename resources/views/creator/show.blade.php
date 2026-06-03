@extends('layouts.app')

@section('content')
@php
    $displayName = $user->nickname ?? $user->username;
@endphp

<div class="-m-6 lg:-m-8 bg-gray-100 dark:bg-darkBg min-h-[calc(100vh-73px)]">
    <section class="relative overflow-hidden bg-white dark:bg-[#07120d] border-b border-gray-200 dark:border-gray-800">
        <div class="creator-hero-pattern absolute inset-0"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-white/95 via-white/70 to-green-50/80 dark:from-black/45 dark:via-black/15 dark:to-green-950/30"></div>
        <div class="relative px-6 lg:px-10 py-10 lg:py-12 flex flex-col sm:flex-row sm:items-center gap-7">
            <img src="{{ $user->profileImageUrl() ?? 'https://ui-avatars.com/api/?name=' . urlencode($displayName) . '&background=111113&color=00ff88' }}"
                 class="w-28 h-28 rounded-xl object-cover border border-gray-200 dark:border-white/10 shadow-xl">

            <div class="min-w-0">
                <div class="flex items-center gap-3 flex-wrap">
                    <h1 class="text-4xl font-semibold text-gray-950 dark:text-white truncate">{{ $displayName }}</h1>
                    @if($user->upload_tier === 'verified')
                        <span class="text-xs font-bold uppercase tracking-wider bg-green-500 text-white dark:bg-neon dark:text-black px-3 py-1 rounded-lg">Verified</span>
                    @endif
                </div>
                <p class="text-gray-600 dark:text-gray-300 mt-2">{{ '@' . $user->username }}</p>
                <p class="text-gray-700 dark:text-gray-300 mt-4 max-w-xl">Creator on Web3DShare sharing downloadable 3D models with the community.</p>

                <div class="flex flex-wrap gap-3 mt-5 text-sm">
                    <span class="bg-white/80 dark:bg-white/10 border border-green-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-1.5 rounded-lg shadow-sm">{{ $stats['models'] }} Models</span>
                    <span class="bg-white/80 dark:bg-white/10 border border-green-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-1.5 rounded-lg shadow-sm">{{ number_format($stats['views']) }} Views</span>
                    <span class="bg-white/80 dark:bg-white/10 border border-green-200 dark:border-white/10 text-gray-900 dark:text-white px-3 py-1.5 rounded-lg shadow-sm">{{ number_format($stats['stars']) }} Stars</span>
                </div>
            </div>
        </div>
    </section>

    <section class="px-6 lg:px-10 py-8">
        <div class="grid grid-cols-1 xl:grid-cols-[1fr_320px] gap-8 items-start">
            <div>
                <div class="flex items-center justify-between gap-4 mb-5">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white">Models</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $models->total() }} published models</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($models as $model)
                        <div class="bg-white dark:bg-darkPanel border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden hover:border-green-400 dark:hover:border-neon/40 shadow-sm hover:shadow-md transition-all group flex flex-col">
                            <a href="/models/{{ $model->id }}" onclick="openModel('{{ $model->id }}', event)" class="relative w-full h-44 overflow-hidden block bg-gray-100 dark:bg-black">
                                <img loading="lazy" src="{{ $model->thumbnailUrl() }}" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                                @if($model->category)
                                    <div class="absolute top-3 right-3 bg-white/90 dark:bg-black/70 backdrop-blur-md border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-[10px] uppercase font-bold tracking-widest px-2.5 py-1 rounded-md shadow-sm">
                                        {{ $model->category->name }}
                                    </div>
                                @endif
                            </a>

                            <div class="p-4 flex flex-col flex-1">
                                <a href="/models/{{ $model->id }}" class="font-bold text-gray-800 dark:text-gray-100 hover:text-green-600 dark:hover:text-neon transition-colors truncate" title="{{ $model->title }}">
                                    {{ $model->title }}
                                </a>

                                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-100 dark:border-gray-800 text-gray-500 text-xs font-semibold">
                                    <span class="flex items-center gap-1.5" title="{{ $model->view_count }} Views">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        {{ $model->view_count }}
                                    </span>
                                    <span class="flex items-center gap-1.5" title="{{ $model->stars_count }} Stars">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" /></svg>
                                        {{ $model->stars_count }}
                                    </span>
                                    <span class="flex items-center gap-1.5" title="{{ $model->download_count }} Downloads">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                                        {{ $model->download_count }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-16 text-center bg-white dark:bg-darkPanel border border-gray-200 dark:border-gray-800 rounded-xl text-gray-500 dark:text-gray-400">
                            No models published yet.
                        </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $models->links() }}
                </div>
            </div>

            <aside class="space-y-6">
                <div class="bg-white dark:bg-darkPanel border border-gray-200 dark:border-gray-800 rounded-xl p-6 shadow-sm">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">About</h3>
                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-gray-400 uppercase tracking-wider text-xs font-semibold mb-1">Category</p>
                            <p class="text-gray-800 dark:text-gray-200">{{ ucfirst($user->upload_tier) }} Creator</p>
                        </div>
                        <div>
                            <p class="text-gray-400 uppercase tracking-wider text-xs font-semibold mb-1">Role</p>
                            <p class="text-gray-800 dark:text-gray-200">{{ ucfirst($user->role) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 uppercase tracking-wider text-xs font-semibold mb-1">Member since</p>
                            <p class="text-gray-800 dark:text-gray-200">{{ $user->created_at->format('F j, Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-darkPanel border border-gray-200 dark:border-gray-800 rounded-xl p-6 shadow-sm">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Stats</h3>
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
