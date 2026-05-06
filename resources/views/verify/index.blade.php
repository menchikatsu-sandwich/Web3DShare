@extends('layouts.app')

@section('content')
<div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-md p-4">
    
    <div class="relative w-full max-w-xl bg-white dark:bg-darkPanel p-8 rounded-2xl border border-gray-200 dark:border-neon/20 shadow-xl dark:shadow-[0_0_40px_rgba(0,255,136,0.1)] transition-colors duration-300">
        
        <a href="/" class="absolute top-4 right-4 text-gray-400 hover:text-neon transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </a>

        <form method="POST" action="/verify-request" class="flex flex-col gap-6">
            @csrf

            <div class="flex items-center gap-4 mb-2">
                <div class="p-3 bg-green-100 dark:bg-neon/10 rounded-full text-green-600 dark:text-neon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-wide">Request <span class="text-neon">Verified</span></h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Get the official badge for your profile</p>
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 text-blue-800 dark:text-blue-300 p-4 rounded-xl text-sm">
                <strong>Note:</strong> Verified badges are given to authentic creators. Please provide links to your portfolio, ArtStation, or explain why you should be verified.
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 ml-1">Your Reason / Portfolio Links</label>
                <textarea name="note" placeholder="Tuliskan alasan kenapa kamu layak dapet badge verified (atau cantumkan link portofoliomu)..." rows="5" required
                          class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-neon focus:ring-1 focus:ring-neon transition-all placeholder-gray-400 dark:placeholder-gray-600 resize-none"></textarea>
            </div>

            <button type="submit" class="w-full bg-neon text-black font-semibold text-lg py-3 rounded-xl mt-2 hover:bg-[#00cc6a] hover:shadow-[0_0_15px_rgba(0,255,136,0.3)] transition-all">
                Submit Request
            </button>

        </form>
    </div>
</div>
@endsection