<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <title>Web3DShare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="preconnect" href="https://ui-avatars.com" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />

    @vite (['resources/css/app.css', 'resources/js/app.js'])
    @stack ('head')

    <script>
        if (localStorage.getItem('color-theme') === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>

    <style>
        ::view-transition-old(root),
        ::view-transition-new(root) {
            animation: none;
            mix-blend-mode: normal;
        }

        ::view-transition-old(root) {
            z-index: 1;
        }

        ::view-transition-new(root) {
            z-index: 2;
        }

        #sidebar.collapsed {
            width: 0 !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            opacity: 0;
            border-right-width: 0 !important;
        }

        @keyframes fadeInUpModal {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.98);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .animate-modal-up {
            animation: fadeInUpModal 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        /* LOGIKA HIDE TOMBOL X SAAT FULL PAGE */
        .is-full-page #close-modal-btn {
            display: none !important;
        }
    </style>
</head>

<body
    class="bg-gray-100 font-sans text-gray-800 antialiased transition-colors duration-300 dark:bg-darkBg dark:text-gray-200"
>
    @if (session('success') || session('error') || $errors->any())
        <div
            class="pointer-events-none fixed right-4 bottom-4 w-[calc(100vw-2rem)] max-w-sm space-y-3 sm:right-6 sm:bottom-6"
            style="z-index: 400"
        >
            @if (session('success'))
                <div
                    data-flash-toast
                    class="pointer-events-auto rounded-xl border border-green-300 bg-green-50 px-4 py-3 text-sm font-medium text-green-800 shadow-xl transition-all dark:border-neon/40 dark:bg-green-950/95 dark:text-neon"
                >
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div
                    data-flash-toast
                    class="pointer-events-auto rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm font-medium text-red-700 shadow-xl transition-all dark:border-red-700 dark:bg-red-950/95 dark:text-red-200"
                >
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div
                    data-flash-toast
                    class="pointer-events-auto rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700 shadow-xl transition-all dark:border-red-700 dark:bg-red-950/95 dark:text-red-200"
                >
                    <p class="mb-1 font-semibold">Please fix the following:</p>
                    <ul class="ml-5 list-disc space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <div class="flex min-h-screen flex-col">
        <nav
            class="sticky top-0 z-50 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 shadow-sm transition-colors duration-300 dark:border-neon/10 dark:bg-darkPanel"
        >
            <div class="flex items-center gap-6">
                <button
                    onclick="toggleSidebar()"
                    class="rounded-lg p-2 text-gray-600 transition hover:bg-gray-100 hover:text-green-600 dark:text-gray-300 dark:hover:bg-gray-800 dark:hover:text-neon"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <a href="/" data-tour="brand">
                    <h1 class="text-2xl font-bold tracking-wide text-green-600 dark:text-neon">Web3DShare</h1>
                </a>
                <div class="ml-4 hidden sm:block">
                    <form action="/" method="GET" class="relative hidden max-w-md flex-1 sm:block" data-tour="search">
                        @if (request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}" />
                        @endif
                        @if (request('tag'))
                            <input type="hidden" name="tag" value="{{ request('tag') }}" />
                        @endif
                        @if (request('sort'))
                            <input type="hidden" name="sort" value="{{ request('sort') }}" />
                        @endif
                        @if (request('timeframe'))
                            <input type="hidden" name="timeframe" value="{{ request('timeframe') }}" />
                        @endif
                        @if (request('filter'))
                            <input type="hidden" name="filter" value="{{ request('filter') }}" />
                        @endif

                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search models, creators, or tags..."
                            class="block w-full rounded-lg border border-gray-200 bg-gray-50 py-2 pr-3 pl-10 text-sm text-gray-900 placeholder-gray-400 transition-all focus:border-green-500 focus:ring-2 focus:ring-green-500/20 focus:outline-none dark:border-gray-800 dark:bg-darkPanel dark:text-gray-100 dark:focus:border-neon"
                        />
                    </form>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <button
                    id="theme-toggle"
                    class="relative inline-flex h-7 w-14 items-center rounded-full bg-gray-300 transition-colors duration-300 focus:ring-2 focus:ring-green-500 focus:outline-none dark:bg-gray-700 dark:focus:ring-neon"
                >
                    <span class="sr-only">Toggle dark mode</span>
                    <span
                        id="theme-toggle-circle"
                        class="flex inline-block h-5 w-5 translate-x-1 transform items-center justify-center rounded-full bg-white shadow-md transition-transform duration-300 dark:translate-x-8 dark:bg-darkBg"
                    >
                        <svg id="theme-toggle-light-icon" class="h-3.5 w-3.5 text-yellow-500 dark:hidden" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                        <svg id="theme-toggle-dark-icon" class="hidden h-3.5 w-3.5 text-neon dark:block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                    </span>
                </button>

                @auth
                    <div class="relative">
                        @if (auth()->user()->profileImageUrl())
                            <img
                                src="{{ auth()->user()->profileImageUrl() }}"
                                onclick="toggleMenu()"
                                class="h-11 w-11 cursor-pointer rounded-full object-cover ring-2 ring-green-500 ring-offset-2 ring-offset-white transition-all dark:ring-neon dark:ring-offset-darkPanel"
                            />
                        @else
                            <img
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username) }}&background=f3f4f6&color=09090b"
                                onclick="toggleMenu()"
                                class="block h-11 w-11 cursor-pointer rounded-full object-cover ring-2 ring-green-500 ring-offset-2 ring-offset-white transition-all dark:hidden"
                            />
                            <img
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username) }}&background=111113&color=00ff88"
                                onclick="toggleMenu()"
                                class="hidden h-11 w-11 cursor-pointer rounded-full object-cover ring-2 ring-neon ring-offset-2 ring-offset-darkPanel transition-all dark:block"
                            />
                        @endif
                        <div
                            id="menu"
                            class="absolute right-0 z-50 mt-3 hidden w-48 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg dark:border-neon/20 dark:bg-darkPanel"
                        >
                            @if (auth()->user()->role === 'admin' || auth()->user()->role === 'moderator' || auth()->user()->can('admin'))
                                <a
                                    href="/panel"
                                    class="block border-b border-gray-100 px-4 py-3 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 hover:text-green-600 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-neon/10 dark:hover:text-neon"
                                    >Admin Panel</a
                                >
                            @endif
                            <a
                                href="/creators/{{ auth()->user()->username }}"
                                class="block border-b border-gray-100 px-4 py-3 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 hover:text-green-600 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-neon/10 dark:hover:text-neon"
                                >View Profile</a
                            >
                            <a
                                href="/profile"
                                class="block px-4 py-3 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-100 hover:text-green-600 dark:text-gray-300 dark:hover:bg-neon/10 dark:hover:text-neon"
                                >Edit Profile</a
                            >
                            <form method="POST" action="/logout">
                                @csrf
                                <button
                                    class="w-full px-4 py-3 text-left text-sm font-medium text-red-600 transition-colors hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-500/10"
                                >
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center gap-4 text-sm font-medium" data-tour="auth-actions">
                        <a
                            href="/login"
                            class="text-gray-600 transition hover:text-green-600 dark:text-gray-300 dark:hover:text-neon"
                            >Login</a
                        >
                        <a
                            href="/register"
                            class="rounded-lg bg-green-500 px-5 py-2.5 font-semibold text-white shadow-sm transition-all hover:bg-green-600 dark:bg-neon dark:text-black dark:hover:bg-[#00cc6a]"
                            >Join Free</a
                        >
                    </div>
                @endauth
            </div>
        </nav>

        <div class="flex flex-1 overflow-hidden">
            @auth
                <aside
                    id="sidebar"
                    class="flex w-72 flex-shrink-0 flex-col overflow-hidden border-r border-gray-200 bg-white px-6 py-6 whitespace-nowrap shadow-sm transition-all duration-300 dark:border-neon/10 dark:bg-darkPanel dark:shadow-none"
                >
                    <div
                        class="mb-8 rounded-xl border border-green-300 bg-green-100 p-3 text-center text-xs font-semibold tracking-widest text-green-700 uppercase shadow-inner dark:border-neon/20 dark:bg-neon/5 dark:text-neon"
                    >
                        {{ auth()->user()->isStaff() ? 'Staff: '.strtoupper(auth()->user()->role) : 'Tier: '.strtoupper(auth()->user()->upload_tier) }}
                    </div>
                    <ul class="flex-1 space-y-2">
                        <li>
                            <a
                                href="/upload"
                                data-tour="sidebar-upload"
                                class="group flex items-center gap-3 rounded-xl px-4 py-3 font-medium text-gray-600 transition-all hover:bg-green-50 hover:text-green-600 dark:text-gray-300 dark:hover:bg-neon/10 dark:hover:text-neon"
                                ><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-gray-500 transition-colors group-hover:text-green-600 dark:group-hover:text-neon">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" /></svg
                                >Upload</a
                            >
                        </li>
                        <li>
                            <a
                                href="/verify"
                                data-tour="sidebar-verify"
                                class="group flex items-center gap-3 rounded-xl px-4 py-3 font-medium text-gray-600 transition-all hover:bg-green-50 hover:text-green-600 dark:text-gray-300 dark:hover:bg-neon/10 dark:hover:text-neon"
                                ><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-gray-500 transition-colors group-hover:text-green-600 dark:group-hover:text-neon">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" /></svg
                                >Request Verify</a
                            >
                        </li>
                        <li>
                            <a
                                href="/?filter=my_models"
                                data-tour="sidebar-my-models"
                                class="group flex items-center gap-3 rounded-xl px-4 py-3 font-medium text-gray-600 transition-all hover:bg-green-50 hover:text-green-600 dark:text-gray-300 dark:hover:bg-neon/10 dark:hover:text-neon"
                                ><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-gray-500 transition-colors group-hover:text-green-600 dark:group-hover:text-neon">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg
                                >My Models</a
                            >
                        </li>
                    </ul>
                </aside>
            @endauth

            <main class="flex flex-1 flex-col overflow-y-auto p-6 lg:p-8">
                <div class="flex-1">
                    @yield ('content')
                </div>

                @unless (request()->is('models/*'))
                    <footer class="mt-auto border-t border-gray-200 pt-10 pb-4 text-center dark:border-gray-800">
                        <p class="text-sm text-gray-500 dark:text-gray-400">&copy; 2026 Web3DShare. All rights reserved.</p>
                    </footer>
                @endunless
            </main>
        </div>
    </div>

    <x-ui.confirmation-dialog />

    <script>
        function toggleSidebar() {
            const s = document.getElementById('sidebar');
            if (s) s.classList.toggle('collapsed');
        }

        function toggleMenu() {
            document.getElementById('menu').classList.toggle('hidden');
        }
        window.addEventListener('click', function (e) {
            const menu = document.getElementById('menu');
            const isClickedOnProfileImg = e.target.closest('img[onclick="toggleMenu()"]');
            if (menu && !menu.contains(e.target) && !isClickedOnProfileImg) menu.classList.add('hidden');
        });

        const themeToggleBtn = document.getElementById('theme-toggle');

        function toggleThemeLogic() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('color-theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        }
        themeToggleBtn.addEventListener('click', function () {
            if (!document.startViewTransition) {
                toggleThemeLogic();
                return;
            }
            const transition = document.startViewTransition(() => {
                toggleThemeLogic();
            });
            transition.ready.then(() => {
                const endRadius = Math.hypot(window.innerWidth, window.innerHeight);
                document.documentElement.animate(
                    {
                        clipPath: [`circle(0px at 100% 0%)`, `circle(${endRadius}px at 100% 0%)`],
                    },
                    {
                        duration: 1000,
                        easing: 'ease-in-out',
                        pseudoElement: '::view-transition-new(root)',
                    },
                );
            });
        });
    </script>

    <script>
        let modalOpen = false;
        let isInternalNavigation = false;
        // Store the active home filters while navigating through model modals.
        let activeFilters = window.location.search || '';

        function appendQuery(url, params) {
            const separator = url.includes('?') ? '&' : '?';
            return `${url}${separator}${params}`;
        }

        function isMyModelsContext() {
            return new URLSearchParams(activeFilters || window.location.search).get('filter') === 'my_models';
        }

        function modelUrl(id) {
            return `/models/${id}${isMyModelsContext() ? '?from=my_models' : ''}`;
        }

        // 1. Main model modal opener.
        async function openModel(id, event) {
            if (event) event.preventDefault();

            // Capture the current filter state when opening from Home.
            if (!modalOpen) {
                activeFilters = window.location.search || '';
            }

            const url = modelUrl(id);

            // Opening another model while a modal is already stacked.
            if (modalOpen && history.state) {
                const currentState = history.state;
                // Mark the previous state as non-final so browser Forward can skip it.
                history.replaceState(
                    {
                        ...currentState,
                        isFinal: false,
                    },
                    '',
                    window.location.href,
                );
            }
            loadModal(url, true);
        }

        // 2. Load partial content.
        function loadModal(url, pushState = true) {
            showLoadingState();

            document.body.style.cursor = 'wait';
            ensureModelViewerLoaded()
                .then(() =>
                    fetch(appendQuery(url, 'partial=1'), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    }),
                )
                .then((res) => res.text())
                .then((html) => {
                    renderModal(html);
                    if (pushState) {
                        const currentDepth = history.state && history.state.depth ? history.state.depth : 0;
                        history.pushState(
                            {
                                isModal: true,
                                depth: currentDepth + 1,
                                isFinal: true, // Latest/final state in the modal stack.
                            },
                            '',
                            url,
                        );
                    }
                })
                .finally(() => {
                    document.body.style.cursor = 'default';
                });
        }

        function ensureModelViewerLoaded() {
            if (customElements.get('model-viewer')) {
                return Promise.resolve();
            }

            const existing = document.querySelector('script[data-model-viewer]');
            if (existing) {
                return new Promise((resolve) => existing.addEventListener('load', resolve, { once: true }));
            }

            return new Promise((resolve) => {
                const script = document.createElement('script');
                script.type = 'module';
                script.src = 'https://ajax.googleapis.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js';
                script.dataset.modelViewer = 'true';
                script.addEventListener('load', resolve, { once: true });
                document.head.appendChild(script);
            });
        }

        function showLoadingState() {
            let wrapper = document.querySelector('.modal-wrapper');
            if (!wrapper) {
                wrapper = document.createElement('div');
                wrapper.className =
                    'fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm modal-wrapper';

                // Close when clicking the backdrop.
                wrapper.onclick = closeAll;

                document.body.appendChild(wrapper);
            }
            wrapper.innerHTML = `
        <div class="relative w-full max-w-[1400px] h-[90vh] bg-white dark:bg-darkPanel rounded-2xl flex items-center justify-center" 
             onclick="event.stopPropagation()">
            <div class="w-12 h-12 border-4 border-neon border-t-transparent rounded-full animate-spin"></div>
        </div>`;
            document.body.style.overflow = 'hidden';
        }

        function renderModal(html) {
            let wrapper = document.querySelector('.modal-wrapper');
            if (!wrapper) {
                wrapper = document.createElement('div');
                wrapper.className =
                    'fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 lg:p-10 bg-black/70 backdrop-blur-sm modal-wrapper';
                wrapper.onclick = closeAll;
                document.body.appendChild(wrapper);
            }
            wrapper.innerHTML = `<div class="relative w-full max-w-[1400px] h-[90vh] sm:h-[85vh] bg-white dark:bg-darkPanel rounded-2xl border border-gray-200 dark:border-gray-700 shadow-2xl overflow-hidden animate-modal-up flex flex-col" onclick="event.stopPropagation()">${html}</div>`;
            document.body.style.overflow = 'hidden';
            modalOpen = true;
        }

        // 3. Close button, one step back.
        function closeTop() {
            if (!modalOpen) return;
            isInternalNavigation = true;

            const currentDepth = history.state && history.state.depth ? history.state.depth : 1;

            if (currentDepth > 1) {
                history.back();

                // Use popstate timing to replace the destination state.
                setTimeout(() => {
                    if (history.state) {
                        // Mark the new current state as final.
                        history.replaceState(
                            {
                                ...history.state,
                                isFinal: true,
                            },
                            '',
                            window.location.href,
                        );
                        // Reset the flag after replaceState finishes.
                        isInternalNavigation = false;
                    }
                }, 100); // Slight delay keeps this stable across browsers.
            } else {
                closeAll();
            }
        }

        // 4. Backdrop click, back to Home.
        function closeAll(e) {
            if (e && e.target !== e.currentTarget) return;

            if (modalOpen) {
                // Remove the modal immediately instead of waiting for history.
                const wrapper = document.querySelector('.modal-wrapper');
                if (wrapper) wrapper.remove();

                document.body.style.overflow = '';
                modalOpen = false;

                const depth = history.state && history.state.depth ? history.state.depth : 1;
                isInternalNavigation = true;

                // Add active filters to the Home state before moving back through history.
                // This lets popstate recognize the correct query string.
                const targetUrl = '/' + activeFilters;
                history.replaceState(null, '', targetUrl);

                history.go(-depth);
            }
        }

        // 5. POPSTATE HANDLER
        window.addEventListener('popstate', function (event) {
            const path = window.location.pathname;
            const pathWithSearch = window.location.pathname + window.location.search;
            const state = event.state;
            const isModelPath = path.startsWith('/models');
            const isFullPage = !!document.getElementById('model-root');

            // A. Back to Home.
            if (!isModelPath || path === '/' || path === '') {
                const wrapper = document.querySelector('.modal-wrapper');
                if (wrapper) wrapper.remove();

                document.body.style.overflow = '';
                modalOpen = false;
                isInternalNavigation = false;

                const homeExists = document.querySelector('.home-grid') || document.getElementById('home-content');

                // Preserve active filters during fallback redirects.
                if (!homeExists) {
                    window.location.href = '/' + activeFilters;
                } else {
                    // Keep the address bar in sync when the home grid is already behind the modal.
                    if (window.location.search !== activeFilters) {
                        history.replaceState(null, '', '/' + activeFilters);
                    }
                }
                return;
            }

            // B. Navigation while the modal is open.
            if (modalOpen) {
                if (!isInternalNavigation) {
                    // Browser Back while the modal is open.
                    isInternalNavigation = true;
                    const backDepth = state && state.depth ? state.depth : 1;
                    history.go(-backDepth);
                } else {
                    // Navigation between stacked modals after closeTop.
                    loadModal(pathWithSearch, false);
                    isInternalNavigation = false;
                }
                return;
            }

            // C. Navigation on the full-page model view.
            if (isFullPage) {
                if (state && state.depth >= 1) {
                    // Claim final-state status so Forward from Home stops here.
                    if (state.isFinal === false) {
                        history.replaceState(
                            {
                                ...state,
                                isFinal: true,
                            },
                            '',
                            pathWithSearch,
                        );
                    }
                    loadModelContentSPA(pathWithSearch);
                } else {
                    window.location.href = pathWithSearch;
                }
                return;
            }

            // D. Navigation from Home to a model.
            if (!modalOpen && !isFullPage) {
                if (state && state.isModal) {
                    if (state.isFinal === false) {
                        history.forward(); // Skip ahead to the final state.
                    } else {
                        // Decide between modal and full-page navigation.
                        if (state.depth > 1) {
                            window.location.href = pathWithSearch; // Stacked navigation becomes a full page.
                        } else {
                            loadModal(pathWithSearch, false); // Single navigation opens a modal.
                        }
                    }
                } else {
                    window.location.href = pathWithSearch;
                }
            }
        });

        function loadModelContentSPA(url) {
            ensureModelViewerLoaded()
                .then(() =>
                    fetch(appendQuery(url, 'partial=1'), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    }),
                )
                .then((res) => res.text())
                .then((html) => {
                    const container = document.getElementById('model-root');
                    if (container) {
                        container.innerHTML = html;
                        window.scrollTo(0, 0);
                    }
                });
        }

        function handleModelClick(id, event) {
            // Check whether we are on the full-page model view.
            const isFullPage = !!document.getElementById('model-root');
            const url = modelUrl(id);

            if (isFullPage) {
                // Full page: navigate between models with AJAX instead of opening a modal.
                if (event) event.preventDefault();

                // Update the browser URL.
                const currentDepth = history.state && history.state.depth ? history.state.depth : 1;
                history.pushState(
                    {
                        isModal: false,
                        depth: currentDepth + 1,
                        isFinal: true,
                    },
                    '',
                    url,
                );

                // Use the full-page loader when available.
                if (typeof loadModelContentSPA === 'function') {
                    loadModelContentSPA(url);
                } else {
                    window.location.href = url; // Fallback when the AJAX loader is unavailable.
                }
            } else {
                // Capture the active filters before the modal loading animation appears.
                activeFilters = window.location.search || '';

                showLoadingState();
                // Home page: open the model modal.
                if (typeof openModel === 'function') {
                    openModel(id, event);
                } else {
                    window.location.href = url;
                }
            }
        }
    </script>
    <script>
        function modelScopeFrom(element) {
            return element.closest('[data-model-shell]') || document;
        }

        function modelIdFromScope(scope) {
            return scope && scope.dataset ? scope.dataset.modelShell : null;
        }

        function setElementBusy(element, busy) {
            if (!element) return;
            element.disabled = busy;
            element.classList.toggle('opacity-60', busy);
            element.classList.toggle('pointer-events-none', busy);
        }

        function flattenAjaxErrors(errors) {
            if (!errors || typeof errors !== 'object') return '';
            return Object.values(errors).flat().filter(Boolean).join(' ');
        }

        async function parseAjaxResponse(response) {
            const contentType = response.headers.get('content-type') || '';

            if (!contentType.includes('application/json')) {
                if (response.redirected) {
                    window.location.href = response.url;
                    throw new Error('Redirecting...');
                }

                throw new Error('The server returned a page instead of JSON. Please refresh and try again.');
            }

            const payload = await response.json();

            if (!response.ok) {
                const fieldErrors = flattenAjaxErrors(payload.errors);
                throw new Error(fieldErrors || payload.message || 'Request failed.');
            }

            return payload;
        }

        async function submitAjaxForm(form) {
            const response = await fetch(form.action, {
                method: (form.method || 'POST').toUpperCase(),
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new FormData(form),
                credentials: 'same-origin',
            });

            return parseAjaxResponse(response);
        }

        window.showToast = function (message, type = 'success') {
            if (!message) return;

            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.className =
                    'fixed bottom-4 right-4 w-[calc(100vw-2rem)] max-w-sm space-y-3 pointer-events-none sm:bottom-6 sm:right-6';
                container.style.zIndex = '400';
                document.body.appendChild(container);
            }

            const toast = document.createElement('div');
            const successClasses =
                'pointer-events-auto bg-green-50 dark:bg-green-950/95 border border-green-300 dark:border-neon/40 text-green-800 dark:text-neon px-4 py-3 rounded-xl text-sm font-medium shadow-xl transition-all';
            const errorClasses =
                'pointer-events-auto bg-red-50 dark:bg-red-950/95 border border-red-300 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded-xl text-sm font-medium shadow-xl transition-all';
            toast.className = type === 'error' ? errorClasses : successClasses;
            toast.textContent = message;
            container.appendChild(toast);

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-3');
                setTimeout(() => toast.remove(), 250);
            }, 3200);
        };

        document.querySelectorAll('[data-flash-toast]').forEach((toast) => {
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-3');
                setTimeout(() => toast.remove(), 250);
            }, 3200);
        });

        function updateHomeCardMetric(modelId, metric, value) {
            if (!modelId && modelId !== 0) return;
            const card = document.querySelector(`[data-model-card="${modelId}"]`);
            if (!card) return;

            const selector = metric === 'stars' ? '[data-card-star-count]' : '[data-card-download-count]';
            const target = card.querySelector(selector);
            if (target) target.textContent = value;
        }

        function updateStarUi(scope, data) {
            const button = scope.querySelector('[data-star-button]');
            const count = scope.querySelector('[data-star-count]');
            const label = scope.querySelector('[data-star-label]');
            const icon = scope.querySelector('[data-star-icon]');
            const starred = !!data.starred;

            if (count) count.textContent = data.stars;
            if (label) label.textContent = starred ? 'Starred' : 'Star';
            if (icon) icon.setAttribute('fill', starred ? 'currentColor' : 'none');

            if (button) {
                button.dataset.starred = starred ? 'true' : 'false';
                button.classList.toggle('text-yellow-500', starred);
                button.classList.toggle('border-yellow-300', starred);
                button.classList.toggle('dark:text-yellow-400', starred);
                button.classList.toggle('text-gray-700', !starred);
                button.classList.toggle('dark:text-gray-300', !starred);
            }

            updateHomeCardMetric(modelIdFromScope(scope), 'stars', data.stars);
        }

        function updateDownloadUi(scope, data) {
            const count = scope.querySelector('[data-download-count]');
            if (count) count.textContent = data.download_count;
            updateHomeCardMetric(modelIdFromScope(scope), 'downloads', data.download_count);
        }

        function updateCommentsCount(scope, count) {
            const target = scope.querySelector('[data-comments-count]');
            if (target) target.textContent = count;
        }

        function updateEmptyCommentsState(scope) {
            const list = scope.querySelector('[data-comments-list]');
            if (!list) return;

            const hasComments = !!list.querySelector('[data-comment-node]');
            const empty = list.querySelector('[data-comments-empty]');

            if (hasComments && empty) {
                empty.remove();
            } else if (!hasComments && !empty) {
                list.insertAdjacentHTML(
                    'beforeend',
                    '<div class="text-center py-8 text-gray-400 dark:text-gray-600 text-sm" data-comments-empty>No comments yet. Be the first to share your thoughts!</div>',
                );
            }
        }

        function syncReplyToggle(scope, parentId, count, expanded = null) {
            const actions = scope.querySelector(`[data-comment-actions="${parentId}"]`);
            const container = scope.querySelector(`#replies-container-${parentId}`);
            let toggle = scope.querySelector(`#toggle-btn-${parentId}`);

            if (!actions || !container) return;

            if (count <= 0) {
                if (toggle) toggle.remove();
                container.classList.add('hidden');
                return;
            }

            if (!toggle) {
                actions.insertAdjacentHTML(
                    'beforeend',
                    `
            <button type="button"
                    onclick="toggleRepliesDisplay('${parentId}')"
                    id="toggle-btn-${parentId}"
                    data-reply-toggle
                    data-reply-count="${count}"
                    class="text-[11px] font-bold text-green-600 dark:text-neon hover:underline flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3" style="transition: transform 0.2s;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                <span><span data-reply-label>Show Replies</span> (<span data-reply-count-text>${count}</span>)</span>
            </button>
        `,
                );
                toggle = scope.querySelector(`#toggle-btn-${parentId}`);
            }

            toggle.dataset.replyCount = count;
            const countText = toggle.querySelector('[data-reply-count-text]');
            if (countText) countText.textContent = count;

            if (expanded !== null) {
                const label = toggle.querySelector('[data-reply-label]');
                const svg = toggle.querySelector('svg');
                if (label) label.textContent = expanded ? 'Hide Replies' : 'Show Replies';
                if (svg) svg.style.transform = expanded ? 'rotate(180deg)' : 'rotate(0deg)';
                container.classList.toggle('hidden', !expanded);
            }
        }

        async function handleAjaxStar(form) {
            const button = form.querySelector('[data-star-button]');
            const scope = modelScopeFrom(form);
            setElementBusy(button, true);

            try {
                const payload = await submitAjaxForm(form);
                updateStarUi(scope, payload.data);
                showToast(payload.message || 'Star updated.');
            } catch (error) {
                showToast(error.message, 'error');
            } finally {
                setElementBusy(button, false);
            }
        }

        async function handleAjaxComment(form) {
            const button = form.querySelector('button[type="submit"]');
            const scope = modelScopeFrom(form);
            setElementBusy(button, true);

            try {
                const payload = await submitAjaxForm(form);
                const data = payload.data || {};
                const parentId = data.parent_id;

                if (parentId) {
                    const replies = scope.querySelector(`#replies-container-${parentId}`);
                    if (replies && data.html) {
                        replies.insertAdjacentHTML('beforeend', data.html);
                        syncReplyToggle(
                            scope,
                            parentId,
                            data.parent_replies_count || replies.querySelectorAll('[data-comment-node]').length,
                            true,
                        );
                    }

                    const replyForm = form.closest('[id^="reply-form-"]');
                    if (replyForm) replyForm.classList.add('hidden');
                } else {
                    const list = scope.querySelector('[data-comments-list]');
                    if (list && data.html) {
                        const empty = list.querySelector('[data-comments-empty]');
                        if (empty) empty.remove();
                        list.insertAdjacentHTML('afterbegin', data.html);
                        list.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                }

                updateCommentsCount(scope, data.comments_count);
                updateEmptyCommentsState(scope);
                form.reset();
                showToast(payload.message || 'Comment posted.');
            } catch (error) {
                showToast(error.message, 'error');
            } finally {
                setElementBusy(button, false);
            }
        }

        async function handleAjaxCommentDelete(form) {
            const button = form.querySelector('button[type="submit"]');
            const scope = modelScopeFrom(form);
            setElementBusy(button, true);

            try {
                const payload = await submitAjaxForm(form);
                const data = payload.data || {};
                const node =
                    scope.querySelector(`[data-comment-node="${data.comment_id}"]`) ||
                    form.closest('[data-comment-node]');
                if (node) node.remove();

                if (data.parent_id) {
                    syncReplyToggle(scope, data.parent_id, data.parent_replies_count || 0);
                }

                updateCommentsCount(scope, data.comments_count);
                updateEmptyCommentsState(scope);
                showToast(payload.message || 'Comment deleted.');
            } catch (error) {
                showToast(error.message, 'error');
                setElementBusy(button, false);
            }
        }

        async function handleAjaxReport(form) {
            const button = form.querySelector('button[type="submit"]');
            setElementBusy(button, true);

            try {
                const payload = await submitAjaxForm(form);
                form.reset();
                const details = form.closest('details');
                if (details) details.open = false;
                showToast(payload.message || 'Report submitted.');
            } catch (error) {
                showToast(error.message, 'error');
            } finally {
                setElementBusy(button, false);
            }
        }

        async function handleAjaxDownload(link) {
            const scope = modelScopeFrom(link);
            setElementBusy(link, true);

            try {
                const response = await fetch(link.href, {
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin',
                });
                const payload = await parseAjaxResponse(response);
                const data = payload.data || {};
                updateDownloadUi(scope, data);

                if (data.download_url) {
                    window.location.href = data.download_url;
                }
            } catch (error) {
                showToast(error.message, 'error');
            } finally {
                setElementBusy(link, false);
            }
        }

        document.addEventListener('submit', function (event) {
            const form = event.target.closest(
                'form[data-ajax-star], form[data-ajax-comment], form[data-ajax-comment-delete], form[data-ajax-report]',
            );
            if (!form) return;
            if (event.defaultPrevented) return;

            event.preventDefault();
            if (form.dataset.ajaxBusy === 'true') return;

            form.dataset.ajaxBusy = 'true';
            const cleanup = () => {
                form.dataset.ajaxBusy = 'false';
            };

            if (form.matches('[data-ajax-star]')) {
                handleAjaxStar(form).finally(cleanup);
            } else if (form.matches('[data-ajax-comment]')) {
                handleAjaxComment(form).finally(cleanup);
            } else if (form.matches('[data-ajax-comment-delete]')) {
                handleAjaxCommentDelete(form).finally(cleanup);
            } else if (form.matches('[data-ajax-report]')) {
                handleAjaxReport(form).finally(cleanup);
            }
        });

        document.addEventListener('click', function (event) {
            const link = event.target.closest('a[data-ajax-download]');
            if (!link) return;

            event.preventDefault();
            if (link.dataset.ajaxBusy === 'true') return;

            link.dataset.ajaxBusy = 'true';
            handleAjaxDownload(link).finally(() => {
                link.dataset.ajaxBusy = 'false';
            });
        });

        // 1. Safe copy URL helper.
        window.copyModelUrl = function (url, buttonEl) {
            navigator.clipboard
                .writeText(url)
                .then(() => {
                    const textSpan = buttonEl.querySelector('.share-text');
                    const originalText = textSpan ? textSpan.innerText : 'Share';

                    if (textSpan) textSpan.innerText = 'Copied!';
                    buttonEl.classList.remove('border-gray-200', 'dark:border-gray-800');
                    buttonEl.classList.add('border-green-500', 'text-green-500', 'dark:text-neon', 'dark:border-neon');

                    setTimeout(() => {
                        if (textSpan) textSpan.innerText = originalText;
                        buttonEl.classList.add('border-gray-200', 'dark:border-gray-800');
                        buttonEl.classList.remove(
                            'border-green-500',
                            'text-green-500',
                            'dark:text-neon',
                            'dark:border-neon',
                        );
                    }, 2000);
                })
                .catch((err) => {
                    console.error('Failed to copy text: ', err);
                });
        };

        // 2. Safe reply form toggle helper.
        window.toggleReplyForm = function (commentId) {
            const form = document.getElementById(`reply-form-${commentId}`);
            if (form) {
                form.classList.toggle('hidden');
                if (!form.classList.contains('hidden')) {
                    const textarea = form.querySelector('textarea');
                    if (textarea) textarea.focus();
                }
            }
        };

        // 3. Safe replies list toggle helper.
        window.toggleRepliesDisplay = function (commentId) {
            const container = document.getElementById(`replies-container-${commentId}`);
            const btn = document.getElementById(`toggle-btn-${commentId}`);

            if (container && btn) {
                const label = btn.querySelector('[data-reply-label]');
                const svgIcon = btn.querySelector('svg');

                container.classList.toggle('hidden');

                if (container.classList.contains('hidden')) {
                    if (label) label.innerText = 'Show Replies';
                    if (svgIcon) svgIcon.style.transform = 'rotate(0deg)';
                } else {
                    if (label) label.innerText = 'Hide Replies';
                    if (svgIcon) {
                        svgIcon.style.transform = 'rotate(180deg)';
                        svgIcon.style.transition = 'transform 0.2s';
                    }
                }
            }
        };
    </script>
    @include ('components.onboarding-tour')
</body>
</html>
