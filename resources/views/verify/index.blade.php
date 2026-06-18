@extends('layouts.app')

@section('content')
<div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-md p-4">
    
    <div data-tour="verify-panel" class="relative w-full max-w-xl bg-white dark:bg-darkPanel p-8 rounded-2xl border border-gray-200 dark:border-neon/20 shadow-xl dark:shadow-[0_0_40px_rgba(0,255,136,0.1)] transition-colors duration-300">
        
        <a href="/" class="absolute top-4 right-4 text-gray-400 hover:text-neon transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </a>

        {{-- PENGECEKAN STATUS VERIFIED --}}
        @if(auth()->user()->upload_tier === 'verified')
            <div class="flex flex-col items-center text-center py-8">
                <div class="p-5 bg-green-100 dark:bg-neon/10 rounded-full text-green-600 dark:text-neon mb-6 animate-bounce">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-16 h-16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">You're Already <span class="text-neon">Verified</span>!</h2>
                <p class="text-gray-500 dark:text-gray-400 max-w-sm">Your account is already verified. The official creator badge is active on your profile.</p>
                
                <a href="/" class="mt-8 px-8 py-3 bg-gray-100 dark:bg-darkBg text-gray-700 dark:text-white font-semibold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-800 transition-all">
                    Back to Home
                </a>
            </div>
        @elseif($pendingRequest)
            <div class="flex flex-col items-center text-center py-8">
                <div class="p-5 bg-yellow-100 dark:bg-yellow-500/10 rounded-full text-yellow-600 dark:text-yellow-400 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-16 h-16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Verification <span class="text-yellow-600 dark:text-yellow-400">Pending</span></h2>
                <p class="text-gray-500 dark:text-gray-400 max-w-sm">
                    Your request is waiting for admin or moderator review. You can submit a new request after this one is approved or rejected.
                </p>

                <div class="mt-6 w-full text-left bg-yellow-50 dark:bg-yellow-500/10 border border-yellow-200 dark:border-yellow-500/20 rounded-xl p-4">
                    <p class="text-xs font-semibold uppercase tracking-wider text-yellow-700 dark:text-yellow-400 mb-2">Submitted note</p>
                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $pendingRequest->note }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-3">Submitted on {{ $pendingRequest->created_at->format('F j, Y H:i') }}</p>
                </div>
                
                <a href="/" class="mt-8 px-8 py-3 bg-gray-100 dark:bg-darkBg text-gray-700 dark:text-white font-semibold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-800 transition-all">
                    Back to Home
                </a>
            </div>
        @else
            {{-- FORM REQUEST JIKA BELUM VERIFIED --}}
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

                <div data-tour="verify-rules" class="bg-gray-50 dark:bg-darkBg border border-gray-200 dark:border-gray-800 rounded-xl p-4 text-sm">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-3">Current requirements</p>
                    <div class="grid gap-2 text-gray-700 dark:text-gray-300">
                        <p class="flex items-center justify-between gap-3">
                            <span>Published models</span>
                            <span class="font-semibold">{{ $verificationCheck['model_count'] }} / {{ $verificationCheck['rules']['min_models'] }}</span>
                        </p>
                        <p class="flex items-center justify-between gap-3">
                            <span>Downloads per model</span>
                            <span class="font-semibold">{{ $verificationCheck['rules']['min_downloads_per_model'] }} minimum</span>
                        </p>
                        <p class="flex items-center justify-between gap-3">
                            <span>Account age</span>
                            <span class="font-semibold">{{ $verificationCheck['account_age_days'] }} / {{ $verificationCheck['rules']['min_account_age_days'] }} day(s)</span>
                        </p>
                    </div>

                    @if(!$verificationCheck['eligible'])
                        <div class="mt-4 bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-700 dark:text-red-200 rounded-lg p-3">
                            <p class="font-semibold mb-2">You cannot request verification yet:</p>
                            <ul class="list-disc ml-5 space-y-1">
                                @foreach($verificationCheck['reasons'] as $reason)
                                    <li>{{ $reason }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2 ml-1">Your Reason / Portfolio Links</label>
                    <textarea name="note" placeholder="Explain why you should receive the verified badge, or include links to your portfolio..." rows="5" required {{ !$verificationCheck['eligible'] ? 'disabled' : '' }}
                              class="w-full bg-gray-50 dark:bg-darkBg border border-gray-300 dark:border-gray-800 text-gray-900 dark:text-gray-200 px-4 py-3 rounded-xl focus:outline-none focus:border-neon focus:ring-1 focus:ring-neon transition-all placeholder-gray-400 dark:placeholder-gray-600 resize-none"></textarea>
                </div>

                <button type="submit" {{ !$verificationCheck['eligible'] ? 'disabled' : '' }} class="w-full bg-neon text-black font-semibold text-lg py-3 rounded-xl mt-2 hover:bg-[#00cc6a] hover:shadow-[0_0_15px_rgba(0,255,136,0.3)] transition-all disabled:cursor-not-allowed disabled:bg-gray-300 disabled:text-gray-500 disabled:shadow-none dark:disabled:bg-gray-800">
                    Submit Request
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
