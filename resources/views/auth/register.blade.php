@extends('layouts.app')

@section('content')
<div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-md p-4">
    <div class="relative w-full max-w-md bg-white dark:bg-darkPanel p-8 rounded-2xl border border-gray-200 dark:border-neon/20 shadow-xl dark:shadow-[0_0_40px_rgba(0,255,136,0.1)] transition-colors duration-300">
        
        <a href="/" class="absolute top-4 right-4 text-gray-400 hover:text-neon transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
        </a>

        <form method="POST" action="/register" class="flex flex-col gap-5" id="register-form">
            @csrf
            <div class="mb-2">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-wide">Regis<span class="text-neon">ter</span></h2>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Join the Web3DShare community</p>
            </div>

            <div class="space-y-4">
                <input type="text" name="username" value="{{ old('username') }}" placeholder="Username" pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z0-9]+$" title="Username must contain at least one letter and one number" required
                       class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-neon focus:ring-1 focus:ring-neon transition-all placeholder-gray-400 dark:placeholder-gray-600">
                <input type="email" name="email" value="{{ old('email') }}" placeholder="Email address" required
                       class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-neon focus:ring-1 focus:ring-neon transition-all placeholder-gray-400 dark:placeholder-gray-600">
                <input type="password" name="password" placeholder="Password" required
                       class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-neon focus:ring-1 focus:ring-neon transition-all placeholder-gray-400 dark:placeholder-gray-600">
            </div>

            <label class="flex items-start gap-3 text-xs leading-relaxed text-gray-600 dark:text-gray-400">
                <input id="terms_accepted" type="checkbox" name="terms_accepted" value="1" {{ old('terms_accepted') ? 'checked' : '' }} required
                    class="mt-1 h-4 w-4 rounded border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-darkBg text-neon focus:ring-neon">
                <span>
                    I have read and agree to the
                    <a href="/terms" target="_blank" rel="noopener" class="font-semibold text-green-600 dark:text-neon hover:underline">EULA, Rules of Access, and Terms of Agreement</a>.
                </span>
            </label>

            <button id="register-submit" type="submit" disabled class="w-full bg-neon text-black font-semibold text-lg py-3 rounded-xl mt-2 hover:bg-[#00cc6a] hover:shadow-[0_0_15px_rgba(0,255,136,0.3)] transition-all disabled:cursor-not-allowed disabled:bg-gray-300 disabled:text-gray-500 disabled:shadow-none dark:disabled:bg-gray-800 dark:disabled:text-gray-500">
                Join Now
            </button>
            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-2">
                Already have an account? <a href="/login" class="text-neon hover:underline">Log in</a>
            </p>
        </form>
    </div>
</div>

<script>
    const termsAccepted = document.getElementById('terms_accepted');
    const registerSubmit = document.getElementById('register-submit');

    function syncRegisterSubmit() {
        registerSubmit.disabled = !termsAccepted.checked;
    }

    termsAccepted.addEventListener('change', syncRegisterSubmit);
    syncRegisterSubmit();
</script>
@endsection
