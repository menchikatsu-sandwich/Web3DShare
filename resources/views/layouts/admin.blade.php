<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <title>Admin Panel - Web3DShare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/3.5.0/model-viewer.min.js"></script>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        neon: '#00ff88',
                        darkBg: '#09090b',
                        darkPanel: '#111113'
                    }
                }
            }
        }
    </script>
    
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
        ::view-transition-new(root) { animation: none; mix-blend-mode: normal; }
        ::view-transition-old(root) { z-index: 1; }
        ::view-transition-new(root) { z-index: 2; }
        
        /* Smooth Scrolling */
        html { scroll-behavior: smooth; }

        /* Animasi Modal Fade-in (Jika dipakai di halaman admin) */
        @keyframes fadeInUpModal {
            from { opacity: 0; transform: translateY(30px) scale(0.98); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-modal-up { animation: fadeInUpModal 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body class="bg-gray-100 dark:bg-darkBg text-gray-800 dark:text-gray-200 font-sans antialiased transition-colors duration-300">

<div class="flex flex-col min-h-screen">

    <nav class="flex items-center justify-between px-6 py-4 bg-white dark:bg-darkPanel border-b border-gray-200 dark:border-neon/10 shadow-sm sticky top-0 z-50 transition-colors duration-300">
        <div class="flex items-center gap-6">
            <a href="/" class="flex items-center gap-2 group">
                <h1 class="text-green-600 dark:text-neon font-bold text-2xl tracking-wide group-hover:opacity-80 transition-opacity">Web3DShare</h1>
                <span class="bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-500 border border-red-200 dark:border-red-500/20 text-[10px] uppercase font-bold tracking-widest px-2 py-0.5 rounded-md ml-2 hidden sm:block">Admin Mode</span>
            </a>
        </div>

        <div class="flex items-center gap-6">
            <button id="theme-toggle" class="relative inline-flex items-center h-7 w-14 rounded-full bg-gray-300 dark:bg-gray-700 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 dark:focus:ring-neon">
                <span class="sr-only">Toggle dark mode</span>
                <span id="theme-toggle-circle" class="inline-block w-5 h-5 transform bg-white dark:bg-darkBg rounded-full transition-transform duration-300 translate-x-1 dark:translate-x-8 flex items-center justify-center shadow-md">
                    <svg id="theme-toggle-light-icon" class="w-3.5 h-3.5 text-yellow-500 dark:hidden" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path></svg>
                    <svg id="theme-toggle-dark-icon" class="hidden w-3.5 h-3.5 text-neon dark:block" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                </span>
            </button>

            @auth
                <div class="relative">
                    @if(auth()->user()->profileImageUrl())
                        <img src="{{ auth()->user()->profileImageUrl() }}" 
                             onclick="toggleMenu()" 
                             class="w-11 h-11 rounded-full cursor-pointer object-cover ring-2 ring-red-500 ring-offset-2 ring-offset-white dark:ring-offset-darkPanel transition-all">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username) }}&background=e5e7eb&color=1f2937" 
                             onclick="toggleMenu()" 
                             class="block dark:hidden w-11 h-11 rounded-full cursor-pointer object-cover ring-2 ring-red-500 ring-offset-2 ring-offset-white transition-all">
                        
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->username) }}&background=111113&color=00ff88" 
                             onclick="toggleMenu()" 
                             class="hidden dark:block w-11 h-11 rounded-full cursor-pointer object-cover ring-2 ring-red-500 ring-offset-2 ring-offset-darkPanel transition-all">
                    @endif

                    <div id="menu" class="hidden absolute right-0 mt-3 w-48 bg-white dark:bg-darkPanel border border-gray-200 dark:border-neon/20 rounded-xl shadow-lg z-50 overflow-hidden transition-colors duration-300">
                        <a href="/" class="block px-4 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-neon/10 hover:text-green-600 dark:hover:text-neon transition-colors">Back to Main App</a>
                        <form method="POST" action="/logout">
                            @csrf
                            <button class="w-full text-left px-4 py-3 text-sm font-medium text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">Logout</button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </nav>

    <main class="flex-1 p-6 lg:p-8">
        @yield('content')
    </main>

</div>

<script>
// --- LOGIKA MENU PROFIL (FIXED) ---
function toggleMenu(){
    document.getElementById('menu').classList.toggle('hidden');
}

// Deteksi klik di luar menu profil (Mendukung trik 2 gambar profile)
window.addEventListener('click', function(e) {
    const menu = document.getElementById('menu');
    const isClickedOnProfileImg = e.target.closest('img[onclick="toggleMenu()"]');
    
    if (menu && !menu.contains(e.target) && !isClickedOnProfileImg) {
        menu.classList.add('hidden');
    }
});

// --- LOGIKA EFEK WAVES (TRANSISI TEMA) ---
const themeToggleBtn = document.getElementById('theme-toggle');

function toggleThemeLogic() {
    document.documentElement.classList.toggle('dark');
    if (document.documentElement.classList.contains('dark')) {
        localStorage.setItem('color-theme', 'dark');
    } else {
        localStorage.setItem('color-theme', 'light');
    }
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

        document.documentElement.animate(
            {
                clipPath: [
                    `circle(0px at 100% 0%)`,
                    `circle(${endRadius}px at 100% 0%)`
                ],
            },
            {
                duration: 1000,
                easing: 'ease-in-out',
                pseudoElement: '::view-transition-new(root)',
            }
        );
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
        history.replaceState({ ...currentState, isFinal: false }, '', window.location.href);
    }
    loadModal(url, true);
}

// 2. Load Konten (Partial)
function loadModal(url, pushState = true) {
    showLoadingState();

    document.body.style.cursor = 'wait';
    fetch(`${url}?partial=1`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
    .then(res => res.text())
    .then(html => {
        renderModal(html);
        if (pushState) {
            const currentDepth = (history.state && history.state.depth) ? history.state.depth : 0;
            history.pushState({ 
                isModal: true, 
                depth: currentDepth + 1,
                isFinal: true  // Ini adalah titik terbaru/terakhir
            }, '', url);
        }
    })
    .finally(() => { document.body.style.cursor = 'default'; });
}

function showLoadingState() {
    let wrapper = document.querySelector('.modal-wrapper');
    if(!wrapper) {
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
    if(!wrapper) {
        wrapper = document.createElement('div');
        wrapper.className = "fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 lg:p-10 bg-black/70 backdrop-blur-sm modal-wrapper";
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

    const currentDepth = (history.state && history.state.depth) ? history.state.depth : 1;

    if (currentDepth > 1) {
        history.back();
        
        // Kita gunakan event 'popstate' atau timeout untuk me-replace state tujuan
        setTimeout(() => {
            if (history.state) {
                // Kunci state A sebagai Final yang baru
                history.replaceState({ ...history.state, isFinal: true }, '', window.location.href);
                // Pastikan flag dimatikan setelah replace selesai
                isInternalNavigation = false;
            }
        }, 100); // Naikkan sedikit ke 100ms agar lebih stabil di beberapa browser
    } else {
        closeAll();
    }
}

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

</script>

</body>
</html>