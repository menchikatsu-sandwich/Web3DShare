@extends ('layouts.app')

@section ('content')
    <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 p-4 backdrop-blur-md">
        <div
            data-tour="verify-panel"
            class="relative w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-8 shadow-xl transition-colors duration-300 dark:border-neon/20 dark:bg-darkPanel dark:shadow-[0_0_40px_rgba(0,255,136,0.1)]"
        >
            <a href="/" class="absolute top-4 right-4 text-gray-400 transition-colors hover:text-neon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>

            @if (auth()->user()->isStaff())
                <div class="flex flex-col items-center py-8 text-center">
                    <div class="mb-6 rounded-full bg-blue-100 p-5 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-16 w-16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9A2.25 2.25 0 0 1 5.25 16.5v-9A2.25 2.25 0 0 1 7.5 5.25h9a2.25 2.25 0 0 1 2.25 2.25v9a2.25 2.25 0 0 1-2.25 2.25ZM9 9.75h6m-6 3h6" />
                        </svg>
                    </div>
                    <h2 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">
                        Staff Access Is <span class="text-blue-600 dark:text-blue-400">Already Active</span>
                    </h2>
                    <p class="max-w-sm text-gray-500 dark:text-gray-400">Your {{ auth()->user()->role }} account already has unlimited uploads and staff access. A verified creator request is not needed.</p>

                    <a
                        href="/"
                        class="mt-8 rounded-xl bg-gray-100 px-8 py-3 font-semibold text-gray-700 transition-all hover:bg-gray-200 dark:bg-darkBg dark:text-white dark:hover:bg-gray-800"
                    >
                        Back to Home
                    </a>
                </div>
            @elseif (auth()->user()->upload_tier === 'verified')
                <div class="flex flex-col items-center py-8 text-center">
                    <div
                        class="mb-6 animate-bounce rounded-full bg-green-100 p-5 text-green-600 dark:bg-neon/10 dark:text-neon"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-16 w-16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                        </svg>
                    </div>
                    <h2 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">
                        You're Already <span class="text-neon">Verified</span>!
                    </h2>
                    <p class="max-w-sm text-gray-500 dark:text-gray-400">Your account is already verified. The official creator badge is active on your profile.</p>

                    <a
                        href="/"
                        class="mt-8 rounded-xl bg-gray-100 px-8 py-3 font-semibold text-gray-700 transition-all hover:bg-gray-200 dark:bg-darkBg dark:text-white dark:hover:bg-gray-800"
                    >
                        Back to Home
                    </a>
                </div>
            @elseif ($pendingRequest)
                <div class="flex flex-col items-center py-8 text-center">
                    <div
                        class="mb-6 rounded-full bg-yellow-100 p-5 text-yellow-600 dark:bg-yellow-500/10 dark:text-yellow-400"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-16 w-16">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                        </svg>
                    </div>
                    <h2 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">
                        Verification <span class="text-yellow-600 dark:text-yellow-400">Pending</span>
                    </h2>
                    <p class="max-w-sm text-gray-500 dark:text-gray-400">Your request is waiting for admin or moderator review. You can submit a new request after this one is approved or rejected.</p>

                    <div
                        class="mt-6 w-full rounded-xl border border-yellow-200 bg-yellow-50 p-4 text-left dark:border-yellow-500/20 dark:bg-yellow-500/10"
                    >
                        <p class="mb-2 text-xs font-semibold tracking-wider text-yellow-700 uppercase dark:text-yellow-400">Submitted note</p>
                        <p class="text-sm whitespace-pre-wrap text-gray-700 dark:text-gray-300">{{ $pendingRequest->note }}</p>
                        <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">Submitted on {{ $pendingRequest->created_at?->format('F j, Y H:i') ?? 'Unknown' }}</p>
                    </div>

                    <a
                        href="/"
                        class="mt-8 rounded-xl bg-gray-100 px-8 py-3 font-semibold text-gray-700 transition-all hover:bg-gray-200 dark:bg-darkBg dark:text-white dark:hover:bg-gray-800"
                    >
                        Back to Home
                    </a>
                </div>
            @else
                {{-- FORM REQUEST JIKA BELUM VERIFIED --}}
                <form method="POST" action="/verify-request" class="flex flex-col gap-6">
                    @csrf

                    <div class="mb-2 flex items-center gap-4">
                        <div class="rounded-full bg-green-100 p-3 text-green-600 dark:bg-neon/10 dark:text-neon">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold tracking-wide text-gray-900 dark:text-white">
                                Request <span class="text-neon">Verified</span>
                            </h2>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get the official badge for your profile</p>
                        </div>
                    </div>

                    <div
                        class="rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-800 dark:border-blue-800/50 dark:bg-blue-900/20 dark:text-blue-300"
                    >
                        <strong>Note:</strong> Verified badges are given to authentic creators. Please provide links to
                        your portfolio, ArtStation, or explain why you should be verified.
                    </div>

                    <div
                        data-tour="verify-rules"
                        class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm dark:border-gray-800 dark:bg-darkBg"
                    >
                        <p class="mb-3 text-xs font-bold tracking-widest text-gray-500 uppercase dark:text-gray-400">Current requirements</p>
                        <div class="grid gap-2 text-gray-700 dark:text-gray-300">
                            <p class="flex items-center justify-between gap-3">
                                <span>Published models</span>
                                <span class="font-semibold"
                                    >{{ $verificationCheck['model_count'] }} / {{ $verificationCheck['rules']['min_models'] }}</span
                                >
                            </p>
                            <p class="flex items-center justify-between gap-3">
                                <span>Total downloads</span>
                                <span class="font-semibold"
                                    >{{ $verificationCheck['total_download_count'] }} / {{ $verificationCheck['rules']['min_total_downloads'] }}</span
                                >
                            </p>
                            <p class="flex items-center justify-between gap-3">
                                <span>Account age</span>
                                <span class="font-semibold"
                                    >{{ $verificationCheck['account_age_days'] }} / {{ $verificationCheck['rules']['min_account_age_days'] }} day(s)</span
                                >
                            </p>
                        </div>

                        @if (!$verificationCheck['eligible'])
                            <div
                                class="mt-4 rounded-lg border border-red-200 bg-red-50 p-3 text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-200"
                            >
                                <p class="mb-2 font-semibold">You cannot request verification yet:</p>
                                <ul class="ml-5 list-disc space-y-1">
                                    @foreach ($verificationCheck['reasons'] as $reason)
                                        <li>{{ $reason }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    <div>
                        <label
                            class="mb-2 ml-1 block text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Your Reason / Portfolio Links</label
                        >
                        <textarea
                            name="note"
                            placeholder="Explain why you should receive the verified badge, or include links to your portfolio..."
                            rows="5"
                            required
                            {{ !$verificationCheck['eligible'] ? 'disabled' : '' }}
                            class="w-full resize-none rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all focus:border-neon focus:ring-1 focus:ring-neon focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200 dark:placeholder-gray-600"
                        ></textarea>
                    </div>

                    <x-ui.button
                        type="submit"
                        size="lg"
                        block
                        class="mt-2 disabled:bg-gray-300 disabled:text-gray-500 dark:shadow-[0_0_15px_rgba(0,255,136,0.3)] dark:disabled:bg-gray-800"
                        :disabled="!$verificationCheck['eligible']"
                    >
                        Submit Request
                    </x-ui.button>
                </form>
            @endif
        </div>
    </div>
@endsection
