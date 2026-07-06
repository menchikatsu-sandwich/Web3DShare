@extends ('layouts.app')

@section ('content')
    <div
        class="fixed inset-0 z-[100] flex items-start justify-center overflow-y-auto bg-black/60 p-2 pt-4 pb-4 backdrop-blur-md sm:items-center sm:p-4"
    >
        <div
            class="relative max-h-[calc(100dvh-2rem)] w-full max-w-xl overflow-y-auto rounded-xl border border-gray-200 bg-white p-5 shadow-xl transition-colors duration-300 sm:rounded-2xl sm:p-8 dark:border-neon/20 dark:bg-darkPanel dark:shadow-[0_0_40px_rgba(0,255,136,0.1)]"
        >
            <a
                href="/"
                class="absolute top-4 right-4 text-gray-400 transition-colors hover:text-green-600 dark:hover:text-neon"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </a>

            <form method="POST" action="/profile" enctype="multipart/form-data" class="flex flex-col gap-6">
                @csrf
                <div class="mb-2">
                    <h2 class="text-2xl font-bold tracking-wide text-gray-900 sm:text-3xl dark:text-white">
                        Edit <span class="text-green-600 dark:text-neon">Profile</span>
                    </h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update your personal information</p>
                </div>

                <div class="my-1 flex justify-center sm:my-2">
                    <label class="group relative cursor-pointer">
                        <img
                            id="profileImagePreview"
                            src="{{ $user->profileImageUrl() ?? 'https://ui-avatars.com/api/?name=User&background=e5e7eb&color=1f2937' }}"
                            class="h-24 w-24 rounded-full object-cover ring-2 ring-green-500 ring-offset-4 ring-offset-white transition-all sm:h-28 sm:w-28 dark:ring-neon dark:ring-offset-darkPanel"
                        />
                        <div
                            class="absolute inset-0 flex items-center justify-center rounded-full bg-black/60 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" />
                            </svg>
                        </div>
                        <input type="file" name="image" id="profileImageInput" class="hidden" accept="image/*" />
                    </label>
                </div>

                <div class="space-y-4">
                    <div>
                        <label
                            class="mb-1 ml-1 block text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Nickname</label
                        >
                        <input
                            name="nickname"
                            value="{{ $user->nickname }}"
                            placeholder="Nickname"
                            class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-all focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200 dark:focus:border-neon dark:focus:ring-neon"
                        />
                    </div>
                    <div>
                        <label
                            class="mb-1 ml-1 block text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Username</label
                        >
                        <input
                            value="{{ $user->username }}"
                            disabled
                            class="w-full cursor-not-allowed rounded-xl border border-gray-300 bg-gray-100 px-4 py-3 text-gray-500 opacity-70 dark:border-gray-800 dark:bg-gray-900/50"
                        />
                    </div>
                    <div>
                        <label
                            class="mb-1 ml-1 block text-xs font-semibold tracking-wider text-gray-500 uppercase dark:text-gray-400"
                            >Email</label
                        >
                        <input
                            value="{{ $user->email }}"
                            disabled
                            class="w-full cursor-not-allowed rounded-xl border border-gray-300 bg-gray-100 px-4 py-3 text-gray-500 opacity-70 dark:border-gray-800 dark:bg-gray-900/50"
                        />
                    </div>
                </div>

                <x-ui.button
                    type="submit"
                    size="lg"
                    block
                    class="mt-2 shadow-md dark:shadow-[0_0_15px_rgba(0,255,136,0.3)]"
                >
                    Save Changes
                </x-ui.button>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const imageInput = document.getElementById('profileImageInput');
            const imagePreview = document.getElementById('profileImagePreview');
            imageInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (file) {
                    imagePreview.src = URL.createObjectURL(file);
                }
            });
        });
    </script>
@endsection
