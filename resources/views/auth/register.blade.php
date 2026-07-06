@extends ('layouts.app')

@section ('content')
    <div
        class="fixed inset-0 z-[100] flex items-start justify-center overflow-y-auto bg-black/60 p-2 pt-4 pb-4 backdrop-blur-md sm:items-center sm:p-4"
    >
        <div
            class="relative max-h-[calc(100dvh-2rem)] w-full max-w-md overflow-y-auto rounded-xl border border-gray-200 bg-white p-5 shadow-xl transition-colors duration-300 sm:rounded-2xl sm:p-8 dark:border-neon/20 dark:bg-darkPanel dark:shadow-[0_0_40px_rgba(0,255,136,0.1)]"
        >
            <a href="/" class="absolute top-4 right-4 text-gray-400 transition-colors hover:text-neon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </a>

            <form method="POST" action="/register" class="flex flex-col gap-5" id="register-form">
                @csrf
                <div class="mb-2">
                    <h2 class="text-2xl font-bold tracking-wide text-gray-900 sm:text-3xl dark:text-white">
                        Regis<span class="text-neon">ter</span>
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Join the Web3DShare community</p>
                </div>

                <div class="space-y-4">
                    <input
                        type="text"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Username"
                        pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z0-9]+$"
                        title="Username must contain at least one letter and one number"
                        required
                        class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all focus:border-neon focus:ring-1 focus:ring-neon focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200 dark:placeholder-gray-600"
                    />
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Email address"
                        required
                        class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all focus:border-neon focus:ring-1 focus:ring-neon focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200 dark:placeholder-gray-600"
                    />
                    <input
                        type="password"
                        name="password"
                        placeholder="Password"
                        required
                        class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 placeholder-gray-400 transition-all focus:border-neon focus:ring-1 focus:ring-neon focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200 dark:placeholder-gray-600"
                    />
                </div>

                <label class="flex items-start gap-3 text-xs leading-relaxed text-gray-600 dark:text-gray-400">
                    <input
                        id="terms_accepted"
                        type="checkbox"
                        name="terms_accepted"
                        value="1"
                        {{ old('terms_accepted') ? 'checked' : '' }}
                        required
                        class="mt-1 h-4 w-4 rounded border-gray-300 bg-gray-50 text-neon focus:ring-neon dark:border-gray-700 dark:bg-darkBg"
                    />
                    <span>
                        I have read and agree to the
                        <a
                            href="/terms"
                            target="_blank"
                            rel="noopener"
                            class="font-semibold text-green-600 hover:underline dark:text-neon"
                            >EULA, Rules of Access, and Terms of Agreement</a
                        >.
                    </span>
                </label>

                <x-ui.button
                    id="register-submit"
                    type="submit"
                    size="lg"
                    block
                    disabled
                    class="mt-2 disabled:bg-gray-300 disabled:text-gray-500 dark:shadow-[0_0_15px_rgba(0,255,136,0.3)] dark:disabled:bg-gray-800 dark:disabled:text-gray-500"
                >
                    Join Now
                </x-ui.button>
                <p class="mt-2 text-center text-sm text-gray-500 dark:text-gray-400">
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
