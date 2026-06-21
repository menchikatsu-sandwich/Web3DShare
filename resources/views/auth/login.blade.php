@extends ('layouts.app')

@section ('content')
    <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 p-4 backdrop-blur-md">
        <div
            class="relative w-full max-w-md rounded-2xl border border-gray-200 bg-white p-8 shadow-xl transition-colors duration-300 dark:border-neon/20 dark:bg-darkPanel dark:shadow-[0_0_40px_rgba(0,255,136,0.1)]"
        >
            <a href="/" class="absolute top-4 right-4 text-gray-400 transition-colors hover:text-neon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </a>

            <form method="POST" action="/login" class="flex flex-col gap-5">
                @csrf
                <div class="mb-2">
                    <h2 class="text-3xl font-bold tracking-wide text-gray-900 dark:text-white">
                        Log<span class="text-neon">in</span>
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Welcome back to Web3DShare</p>
                </div>

                <div class="space-y-4">
                    <input
                        type="text"
                        name="email"
                        placeholder="Email or Username"
                        required
                        class="w-full rounded-xl border border-gray-800 bg-darkBg px-4 py-3 text-gray-200 placeholder-gray-600 transition-all focus:border-neon focus:ring-1 focus:ring-neon focus:outline-none"
                    />
                    <input
                        type="password"
                        name="password"
                        placeholder="Password"
                        required
                        class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all focus:border-neon focus:ring-1 focus:ring-neon focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200 dark:placeholder-gray-600"
                    />
                </div>

                <x-ui.button type="submit" size="lg" block class="mt-4 dark:shadow-[0_0_15px_rgba(0,255,136,0.3)]">
                    Sign In
                </x-ui.button>
            </form>
        </div>
    </div>
@endsection
