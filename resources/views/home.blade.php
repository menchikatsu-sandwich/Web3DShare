@extends('layouts.app')

@section('content')
<div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
    <div>
        @if(request('filter') == 'my_models')
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-wide">My <span class="text-green-600 dark:text-neon">Models</span></h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">Manage and view all your uploaded 3D creations</p>
        @else
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-wide">Explore <span class="text-green-600 dark:text-neon">Models</span></h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">Discover and download the latest 3D creations from the community</p>
        @endif
    </div>
    
    @if(request('filter') == 'my_models')
    <div>
        <a href="/" class="text-sm text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-neon flex items-center gap-2 transition-colors bg-white dark:bg-darkBg border border-gray-200 dark:border-gray-800 px-4 py-2 rounded-lg shadow-sm hover:border-green-300 dark:hover:border-neon/30">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
            Back to Explore
        </a>
    </div>
    @endif
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($models as $model)
    <div class="bg-white dark:bg-darkBg border border-gray-200 dark:border-gray-800 rounded-2xl overflow-hidden hover:border-green-400 dark:hover:border-neon/40 shadow-sm hover:shadow-md dark:hover:shadow-[0_0_20px_rgba(0,255,136,0.1)] transition-all duration-300 group flex flex-col">
        
        <a href="/models/{{ $model->id }}" onclick="openModel('{{ $model->id }}', event)" class="relative w-full h-48 overflow-hidden block bg-gray-100 dark:bg-black">
            <img src="{{ $model->thumbnailUrl() }}" class="w-full h-full object-cover opacity-90 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500">
            @if(isset($model->category))
            <div class="absolute top-3 right-3 bg-white/90 dark:bg-black/70 backdrop-blur-md border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-[10px] uppercase font-bold tracking-widest px-2.5 py-1 rounded-md shadow-sm">
                {{ $model->category->name }}
            </div>
            @endif
        </a>

        <div class="p-5 flex flex-col flex-1">
            <a href="/models/{{ $model->id }}" class="block mb-3">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 group-hover:text-green-600 dark:group-hover:text-neon transition-colors truncate" title="{{ $model->title }}">
                    {{ $model->title }}
                </h3>
            </a>

            <div class="flex items-center gap-3 mt-auto mb-4">
                <img src="{{ $model->user->profileImageUrl() ?? 'https://ui-avatars.com/api/?name=' . urlencode($model->user->nickname ?? $model->user->username) . '&background=e5e7eb&color=1f2937' }}"
                     class="w-7 h-7 rounded-full object-cover ring-2 ring-transparent group-hover:ring-green-300 dark:group-hover:ring-neon/30 transition-all">
                <span class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-gray-200 transition-colors truncate cursor-pointer">
                    {{ $model->user->nickname ?? $model->user->username }}
                </span>
            </div>

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
        @if(request('filter') == 'my_models')
            <a href="/upload" class="mt-4 text-green-600 dark:text-neon font-medium hover:underline">Upload your first model now!</a>
        @endif
    </div>
    @endforelse
</div>
@endsection