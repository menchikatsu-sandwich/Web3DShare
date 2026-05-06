@extends('layouts.app')

@section('content')
<div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-md p-4">
    <div class="relative w-full max-w-xl bg-white dark:bg-darkPanel p-8 rounded-2xl border border-gray-200 dark:border-neon/20 shadow-xl dark:shadow-[0_0_40px_rgba(0,255,136,0.1)] transition-colors duration-300">
        
        <a href="/" class="absolute top-4 right-4 text-gray-400 hover:text-green-600 dark:hover:text-neon transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </a>

        <form method="POST" action="/profile" enctype="multipart/form-data" class="flex flex-col gap-6">
            @csrf
            <div class="mb-2">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-wide">Edit <span class="text-green-600 dark:text-neon">Profile</span></h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Update your personal information</p>
            </div>

            <div class="flex justify-center my-2">
                <label class="relative group cursor-pointer">
                    <img id="profileImagePreview" src="{{ $user->profileImageUrl() ?? 'https://ui-avatars.com/api/?name=User&background=e5e7eb&color=1f2937' }}"
                         class="w-28 h-28 rounded-full object-cover ring-2 ring-green-500 dark:ring-neon ring-offset-4 ring-offset-white dark:ring-offset-darkPanel transition-all">
                    <div class="absolute inset-0 bg-black/60 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" /></svg>
                    </div>
                    <input type="file" name="image" id="profileImageInput" class="hidden" accept="image/*">
                </label>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1 ml-1">Nickname</label>
                    <input name="nickname" value="{{ $user->nickname }}" placeholder="Nickname"
                           class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-green-500 dark:focus:border-neon focus:ring-1 focus:ring-green-500 dark:focus:ring-neon transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1 ml-1">Username</label>
                    <input value="{{ $user->username }}" disabled
                           class="w-full bg-gray-100 dark:bg-gray-900/50 border border-gray-300 dark:border-gray-800 text-gray-500 px-4 py-3 rounded-xl cursor-not-allowed opacity-70">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1 ml-1">Email</label>
                    <input value="{{ $user->email }}" disabled
                           class="w-full bg-gray-100 dark:bg-gray-900/50 border border-gray-300 dark:border-gray-800 text-gray-500 px-4 py-3 rounded-xl cursor-not-allowed opacity-70">
                </div>
            </div>

            <button type="submit" class="w-full bg-green-500 dark:bg-neon text-white dark:text-black font-semibold text-lg py-3 rounded-xl mt-2 hover:bg-green-600 dark:hover:bg-[#00cc6a] shadow-md dark:shadow-[0_0_15px_rgba(0,255,136,0.3)] transition-all">
                Save Changes
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const imageInput = document.getElementById('profileImageInput');
        const imagePreview = document.getElementById('profileImagePreview');
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                imagePreview.src = URL.createObjectURL(file);
            }
        });
    });
</script>
@endsection