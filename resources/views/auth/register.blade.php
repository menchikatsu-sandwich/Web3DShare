@extends('layouts.app')

@section('content')
<div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-md p-4">
    <div class="relative w-full max-w-md bg-white dark:bg-darkPanel p-8 rounded-2xl border border-gray-200 dark:border-neon/20 shadow-xl dark:shadow-[0_0_40px_rgba(0,255,136,0.1)] transition-colors duration-300">
        
        <a href="/" class="absolute top-4 right-4 text-gray-400 hover:text-neon transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </a>

        <form method="POST" action="/register" class="flex flex-col gap-5">
            @csrf
            <div class="mb-2">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-wide">Regis<span class="text-neon">ter</span></h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Join the Web3DShare community</p>
            </div>

            <div class="space-y-4">
                <input type="text" name="username" placeholder="Username" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z0-9]+$" title="Username harus mengandung setidaknya satu huruf dan satu angka" required
                       class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-neon focus:ring-1 focus:ring-neon transition-all placeholder-gray-400 dark:placeholder-gray-600">
                <input type="email" name="email" placeholder="Email address" required
                       class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-neon focus:ring-1 focus:ring-neon transition-all placeholder-gray-400 dark:placeholder-gray-600">
                <input type="password" name="password" placeholder="Password" required
                       class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-neon focus:ring-1 focus:ring-neon transition-all placeholder-gray-400 dark:placeholder-gray-600">
            </div>

            <button type="submit" class="w-full bg-neon text-black font-semibold text-lg py-3 rounded-xl mt-4 hover:bg-[#00cc6a] hover:shadow-[0_0_15px_rgba(0,255,136,0.3)] transition-all">
                Join Now
            </button>
            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-2">
                Already have an account? <a href="/login" class="text-neon hover:underline">Log in</a>
            </p>
        </form>
    </div>
</div>
@endsection