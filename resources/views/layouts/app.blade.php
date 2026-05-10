<!DOCTYPE html>
<html lang="en" class="dark">

<head>
    <title>Web3DShare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://ui-avatars.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    },
                    colors: {
                        neon: '#00ff88',
                        darkBg: '#09090b',
                        darkPanel: '#111113'
                    }
                }
            }
        }
    </script>

    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js"></script>

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

<body class="bg-gray-100 dark:bg-darkBg text-gray-800 dark:text-gray-200 font-sans antialiased transition-colors duration-300">

    <div class="flex flex-col min-h-screen">
        <nav class="flex items-center justify-between px-6 py-4 bg-white dark:bg-darkPanel border-b border-gray-200 dark:border-neon/10 shadow-sm sticky top-0 z-50 transition-colors duration-300">
            <div class="flex items-center gap-6">
                <button onclick="toggleSidebar()" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-neon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <a href="/">
                    <h1 class="text-green-600 dark:text-neon font-bold text-2xl tracking-wide">Web3DShare</h1>
                </a>
                <div class="ml-4 hidden sm:block">
                    <input type="text" placeholder="Search models..." class="bg-gray-50 dark:bg-gray-900/80 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-200 px-4 py-2 w-64 lg:w-80 rounded-xl focus:outline-none focus:border-green-500 dark:focus:border-neon focus:ring-1 focus:ring-green-500 dark:focus:ring-neon transition-all placeholder-gray-500">
                </div>
            </div>

            <div class="flex items-center gap-6">
                <button id="theme-toggle" class="relative inline-flex items-center h-7 w-14 rounded-full bg-gray-300 dark:bg-gray-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 dark:focus:ring-neon">
                    <span class="sr-only">Toggle dark mode</span>
                    <span id="theme-toggle-circle" class="inline-block w-5 h-5 transform bg-white dark:bg-darkBg rounded-full transition-transform duration-300 translate-x-1 dark:translate-x-8 flex items-center justify-center shadow-md">
                        <svg id="theme-toggle-light-icon" class="w-3.5 h-3.5 text-yellow-500 dark:hidden" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                        </svg>
                        <svg id="theme-toggle-dark-icon" class="hidden w-3.5 h-3.5 text-neon dark:block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                        </svg>
                    </span>
                </button>

                @auth
                <div class="relative">
                    @if(auth()->user()->profileImageUrl())
                    <img src="{{ auth()->user()->profileImageUrl() }}" onclick="toggleMenu()" class="w-11 h-11 rounded-full cursor-pointer object-cover ring-2 ring-green-500 dark:ring-neon ring-offset-2 ring-offset-white dark:ring-offset-darkPanel transition-all">
                    @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username) }}&background=f3f4f6&color=09090b" onclick="toggleMenu()" class="block dark:hidden w-11 h-11 rounded-full cursor-pointer object-cover ring-2 ring-green-500 ring-offset-2 ring-offset-white transition-all">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username) }}&background=111113&color=00ff88" onclick="toggleMenu()" class="hidden dark:block w-11 h-11 rounded-full cursor-pointer object-cover ring-2 ring-neon ring-offset-2 ring-offset-darkPanel transition-all">
                    @endif
                    <div id="menu" class="hidden absolute right-0 mt-3 w-48 bg-white dark:bg-darkPanel border border-gray-200 dark:border-neon/20 rounded-xl shadow-lg z-50 overflow-hidden">
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'moderator' || auth()->user()->can('admin'))
                        <a href="/panel" class="block px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-neon/10 hover:text-green-600 dark:hover:text-neon transition-colors border-b border-gray-100 dark:border-gray-800">Admin Panel</a>
                        @endif
                        <a href="/profile" class="block px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-neon/10 hover:text-green-600 dark:hover:text-neon transition-colors">Edit Profile</a>
                        <form method="POST" action="/logout">@csrf<button class="w-full text-left px-4 py-3 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">Logout</button></form>
                    </div>
                </div>
                @else
                <div class="flex items-center gap-4 text-sm font-medium">
                    <a href="/login" class="text-gray-600 dark:text-gray-300 hover:text-green-600 dark:hover:text-neon transition">Login</a>
                    <a href="/register" class="bg-green-500 dark:bg-neon text-white dark:text-black px-5 py-2.5 rounded-lg hover:bg-green-600 dark:hover:bg-[#00cc6a] shadow-sm transition-all font-semibold">Join Free</a>
                </div>
                @endauth
            </div>
        </nav>

        <div class="flex flex-1 overflow-hidden">
            @auth
            <aside id="sidebar" class="w-72 whitespace-nowrap overflow-hidden bg-white dark:bg-darkPanel border-r border-gray-200 dark:border-neon/10 py-6 px-6 flex-shrink-0 transition-all duration-300 flex flex-col shadow-sm dark:shadow-none">
                <div class="mb-8 p-3 bg-green-100 dark:bg-neon/5 border border-green-300 dark:border-neon/20 rounded-xl text-xs font-semibold text-green-700 dark:text-neon text-center uppercase tracking-widest shadow-inner">Tier: {{ auth()->check() ? strtoupper(auth()->user()->upload_tier) : 'GUEST' }}</div>
                <ul class="space-y-2 flex-1">
                    <li><a href="/upload" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 dark:text-gray-300 font-medium hover:bg-green-50 dark:hover:bg-neon/10 hover:text-green-600 dark:hover:text-neon transition-all group"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 group-hover:text-green-600 dark:group-hover:text-neon transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z" />
                            </svg>Upload</a></li>
                    <li><a href="/verify" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 dark:text-gray-300 font-medium hover:bg-green-50 dark:hover:bg-neon/10 hover:text-green-600 dark:hover:text-neon transition-all group"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 group-hover:text-green-600 dark:group-hover:text-neon transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                            </svg>Request Verify</a></li>
                    <li><a href="/?filter=my_models" class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-600 dark:text-gray-300 font-medium hover:bg-green-50 dark:hover:bg-neon/10 hover:text-green-600 dark:hover:text-neon transition-all group"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 group-hover:text-green-600 dark:group-hover:text-neon transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                            </svg>My Models</a></li>
                </ul>
            </aside>
            @endauth

            <main class="flex-1 p-6 lg:p-8 overflow-y-auto flex flex-col">
                <div class="flex-1">@yield('content')</div>

                <footer class="mt-auto pt-10 pb-4 border-t border-gray-200 dark:border-gray-800 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">&copy; 2026 Web3DShare. All rights reserved.</p>
                </footer>
            </main>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const s = document.getElementById('sidebar');
            if (s) s.classList.toggle('collapsed');
        }

        function toggleMenu() {
            document.getElementById('menu').classList.toggle('hidden');
        }
        window.addEventListener('click', function(e) {
            const menu = document.getElementById('menu');
            const isClickedOnProfileImg = e.target.closest('img[onclick="toggleMenu()"]');
            if (menu && !menu.contains(e.target) && !isClickedOnProfileImg) menu.classList.add('hidden');
        });

        const themeToggleBtn = document.getElementById('theme-toggle');

        function toggleThemeLogic() {
            document.documentElement.classList.toggle('dark');
            localStorage.setItem('color-theme', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        }
        themeToggleBtn.addEventListener('click', function() {
            if (!document.startViewTransition) {
                toggleThemeLogic();
                return;
            }
            const transition = document.startViewTransition(() => {
                toggleThemeLogic();
            });
            transition.ready.then(() => {
                const endRadius = Math.hypot(window.innerWidth, window.innerHeight);
                document.documentElement.animate({
                    clipPath: [`circle(0px at 100% 0%)`, `circle(${endRadius}px at 100% 0%)`]
                }, {
                    duration: 1000,
                    easing: 'ease-in-out',
                    pseudoElement: '::view-transition-new(root)'
                });
            });
        });

        let modalOpen = false;
        let isInternalNavigation = false;

        // 1. Fungsi Utama Buka Modal
        async function openModel(id, event) {
            if (event) event.preventDefault();
            const url = `/models/${id}`;

            // Jika buka model baru saat modal sudah ada (tumpukan)
            if (modalOpen && history.state) {
                const currentState = history.state;
                // Matikan status 'Final' di state sebelumnya agar saat Forward dari Home tidak ke sini
                history.replaceState({
                    ...currentState,
                    isFinal: false
                }, '', window.location.href);
            }
            loadModal(url, true);
        }

        // 2. Load Konten (Partial)
        function loadModal(url, pushState = true) {
            showLoadingState();

            document.body.style.cursor = 'wait';
            fetch(`${url}?partial=1`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    renderModal(html);
                    if (pushState) {
                        const currentDepth = (history.state && history.state.depth) ? history.state.depth : 0;
                        history.pushState({
                            isModal: true,
                            depth: currentDepth + 1,
                            isFinal: true // Ini adalah titik terbaru/terakhir
                        }, '', url);
                    }
                })
                .finally(() => {
                    document.body.style.cursor = 'default';
                });
        }

        function showLoadingState() {
            let wrapper = document.querySelector('.modal-wrapper');
            if (!wrapper) {
                wrapper = document.createElement('div');
                wrapper.className = "fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm modal-wrapper";

                // TAMBAHKAN BARIS INI:
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
                wrapper.className = "fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 lg:p-10 bg-black/70 backdrop-blur-sm modal-wrapper";
                wrapper.onclick = closeAll;
                document.body.appendChild(wrapper);
            }
            wrapper.innerHTML = `<div class="relative w-full max-w-[1400px] h-[90vh] sm:h-[85vh] bg-white dark:bg-darkPanel rounded-2xl border border-gray-200 dark:border-gray-700 shadow-2xl overflow-hidden animate-modal-up flex flex-col" onclick="event.stopPropagation()">${html}</div>`;
            document.body.style.overflow = 'hidden';
            modalOpen = true;
        }

        // 3. Tombol Silang (Back 1 Langkah)
        function closeTop() {
            if (!modalOpen) return;
            isInternalNavigation = true;

            const currentDepth = (history.state && history.state.depth) ? history.state.depth : 1;

            if (currentDepth > 1) {
                history.back();

                // Kita gunakan event 'popstate' atau timeout untuk me-replace state tujuan
                setTimeout(() => {
                    if (history.state) {
                        // Kunci state A sebagai Final yang baru
                        history.replaceState({
                            ...history.state,
                            isFinal: true
                        }, '', window.location.href);
                        // Pastikan flag dimatikan setelah replace selesai
                        isInternalNavigation = false;
                    }
                }, 100); // Naikkan sedikit ke 100ms agar lebih stabil di beberapa browser
            } else {
                closeAll();
            }
        }

        // 4. Klik Backdrop (Back ke Home)
        function closeAll(e) {
            if (e && e.target !== e.currentTarget) return;

            if (modalOpen) {
                // Hapus modal secara instan agar tidak menunggu proses history
                const wrapper = document.querySelector('.modal-wrapper');
                if (wrapper) wrapper.remove();

                document.body.style.overflow = '';
                modalOpen = false;

                const depth = (history.state && history.state.depth) ? history.state.depth : 1;
                isInternalNavigation = true;
                history.go(-depth);
            }
        }
        // 5. POPSTATE HANDLER
        window.addEventListener('popstate', function(event) {
            const path = window.location.pathname;
            const state = event.state;
            const isModelPath = path.startsWith('/models');
            const isFullPage = !!document.getElementById('model-root');

            // A. KEMBALI KE HOME
            if (!isModelPath || path === '/' || path === '') {
                const wrapper = document.querySelector('.modal-wrapper');
                if (wrapper) wrapper.remove();

                document.body.style.overflow = '';
                modalOpen = false;
                isInternalNavigation = false;

                const homeExists = document.querySelector('.home-grid') || document.getElementById('home-content');
                if (!homeExists) window.location.href = '/';
                return;
            }

            // B. NAVIGASI SAAT MODAL TERBUKA (Internal/Back Browser)
            if (modalOpen) {
                if (!isInternalNavigation) {
                    // Ini jika user tekan Back browser saat pop-up buka
                    isInternalNavigation = true;
                    const backDepth = (state && state.depth) ? state.depth : 1;
                    history.go(-backDepth);
                } else {
                    // Navigasi antar pop-up (hasil closeTop)
                    loadModal(path, false);
                    isInternalNavigation = false;
                }
                return;
            }

            // C. NAVIGASI SAAT DI HALAMAN FULL PAGE
            if (isFullPage) {
                if (state && state.depth >= 1) {
                    // Ambil alih status Final agar Forward dari Home nantinya berhenti di sini
                    if (state.isFinal === false) {
                        history.replaceState({
                            ...state,
                            isFinal: true
                        }, '', path);
                    }
                    loadModelContentSPA(path);
                } else {
                    window.location.href = path;
                }
                return;
            }

            // D. NAVIGASI DARI HOME (FORWARD KE MODEL)
            if (!modalOpen && !isFullPage) {
                if (state && state.isModal) {
                    if (state.isFinal === false) {
                        history.forward(); // Masih ada state Final di depan, lewati!
                    } else {
                        // Tentukan: Jadi Pop-up atau Full Page?
                        if (state.depth > 1) {
                            window.location.href = path; // Tumpukan -> Full Page
                        } else {
                            loadModal(path, false); // Tunggal -> Pop-up
                        }
                    }
                } else {
                    window.location.href = path;
                }
            }
        });

        function loadModelContentSPA(url) {
            fetch(`${url}?partial=1`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    const container = document.getElementById('model-root');
                    if (container) {
                        container.innerHTML = html;
                        window.scrollTo(0, 0);
                    }
                });
        }

        function handleModelClick(id, event) {
            // Cek apakah kita di halaman Full Page (mencari elemen model-root)
            const isFullPage = !!document.getElementById('model-root');
            const url = `/models/${id}`;

            if (isFullPage) {
                // JIKA DI FULL PAGE: Jangan buka modal, tapi navigasi antar halaman (AJAX)
                if (event) event.preventDefault();

                // Update URL di browser
                const currentDepth = (history.state && history.state.depth) ? history.state.depth : 1;
                history.pushState({
                    isModal: false,
                    depth: currentDepth + 1,
                    isFinal: true
                }, '', url);

                // Panggil fungsi loader Full Page yang sudah kita buat sebelumnya
                if (typeof loadModelContentSPA === 'function') {
                    loadModelContentSPA(url);
                } else {
                    window.location.href = url; // Fallback jika fungsi AJAX tidak ada
                }
            } else {
                showLoadingState();
                // JIKA DI HOME: Panggil fungsi openModel yang membuka Pop-up
                if (typeof openModel === 'function') {
                    openModel(id, event);
                } else {
                    window.location.href = url;
                }
            }
        }
    </script>
</body>

</html>