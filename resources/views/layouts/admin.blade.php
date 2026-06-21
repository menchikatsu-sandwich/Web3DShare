<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <title>Admin Panel - Web3DShare</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
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
        /* Animasi Transisi Tema */
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

        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }

        /* Modal fade-in animation for admin pages. */
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
                <a href="/" class="group flex items-center gap-2">
                    <h1
                        class="text-2xl font-bold tracking-wide text-green-600 transition-opacity group-hover:opacity-80 dark:text-neon"
                    >
                        Web3DShare
                    </h1>
                    <span
                        class="ml-2 hidden rounded-md border border-red-200 bg-red-100 px-2 py-0.5 text-[10px] font-bold tracking-widest text-red-600 uppercase sm:block dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-500"
                        >Admin Mode</span
                    >
                </a>
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
                        <svg id="theme-toggle-light-icon" class="h-3.5 w-3.5 text-yellow-500 dark:hidden" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                        <svg id="theme-toggle-dark-icon" class="hidden h-3.5 w-3.5 text-neon dark:block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                    </span>
                </button>

                @auth
                    <div class="relative">
                        @if (auth()->user()->profileImageUrl())
                            <img
                                src="{{ auth()->user()->profileImageUrl() }}"
                                onclick="toggleMenu()"
                                class="h-11 w-11 cursor-pointer rounded-full object-cover ring-2 ring-red-500 ring-offset-2 ring-offset-white transition-all dark:ring-offset-darkPanel"
                            />
                        @else
                            <img
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username) }}&background=e5e7eb&color=1f2937"
                                onclick="toggleMenu()"
                                class="block h-11 w-11 cursor-pointer rounded-full object-cover ring-2 ring-red-500 ring-offset-2 ring-offset-white transition-all dark:hidden"
                            />
                            <img
                                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username) }}&background=111113&color=00ff88"
                                onclick="toggleMenu()"
                                class="hidden h-11 w-11 cursor-pointer rounded-full object-cover ring-2 ring-red-500 ring-offset-2 ring-offset-darkPanel transition-all dark:block"
                            />
                        @endif

                        <div
                            id="menu"
                            class="absolute right-0 z-50 mt-3 hidden w-48 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg transition-colors duration-300 dark:border-neon/20 dark:bg-darkPanel"
                        >
                            <a
                                href="/"
                                class="block px-4 py-3 text-sm font-medium text-gray-700 transition-colors hover:bg-green-50 hover:text-green-600 dark:text-gray-300 dark:hover:bg-neon/10 dark:hover:text-neon"
                                >Back to Main App</a
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
                @endauth
            </div>
        </nav>

        <main class="flex-1 p-6 lg:p-8">
            @yield ('content')
        </main>
    </div>

    <x-ui.confirmation-dialog />

    <script>
        // --- Profile Menu Logic ---
        function toggleMenu() {
            document.getElementById('menu').classList.toggle('hidden');
        }

        // Detect clicks outside the profile menu.
        window.addEventListener('click', function (e) {
            const menu = document.getElementById('menu');
            const isClickedOnProfileImg = e.target.closest('img[onclick="toggleMenu()"]');

            if (menu && !menu.contains(e.target) && !isClickedOnProfileImg) {
                menu.classList.add('hidden');
            }
        });

        // --- Theme Transition Effect ---
        const themeToggleBtn = document.getElementById('theme-toggle');

        function toggleThemeLogic() {
            document.documentElement.classList.toggle('dark');
            if (document.documentElement.classList.contains('dark')) {
                localStorage.setItem('color-theme', 'dark');
            } else {
                localStorage.setItem('color-theme', 'light');
            }
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

        let modalOpen = false;
        let isInternalNavigation = false;

        // 1. Main model modal opener.
        async function openModel(id, event) {
            if (event) event.preventDefault();
            const url = `/models/${id}`;

            // Opening another model while a modal is already stacked.
            if (modalOpen && history.state) {
                const currentState = history.state;
                // Mark the previous state as non-final so browser Forward can skip it.
                history.replaceState({ ...currentState, isFinal: false }, '', window.location.href);
            }
            loadModal(url, true);
        }

        // 2. Load Konten (Partial)
        function loadModal(url, pushState = true) {
            showLoadingState();

            document.body.style.cursor = 'wait';
            ensureModelViewerLoaded()
                .then(() => fetch(`${url}?partial=1`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } }))
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
                        history.replaceState({ ...history.state, isFinal: true }, '', window.location.href);
                        // Reset the flag after replaceState finishes.
                        isInternalNavigation = false;
                    }
                }, 100); // Slight delay keeps this stable across browsers.
            } else {
                closeAll();
            }
        }

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
                history.go(-depth);
            }
        }

        document.querySelectorAll('[data-flash-toast]').forEach((toast) => {
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-3');
                setTimeout(() => toast.remove(), 250);
            }, 3200);
        });
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
</body>
</html>
