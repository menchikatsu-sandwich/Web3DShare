<div id="web3d-onboarding"
     data-auth="{{ auth()->check() ? '1' : '0' }}"
     data-user-id="{{ auth()->id() ?? 'guest' }}"
     data-home-url="{{ url('/') }}"
     class="hidden">
    <div data-tour-welcome class="fixed inset-0 z-[300] hidden items-center justify-center bg-black/80 backdrop-blur-md p-4">
        <div class="w-full max-w-2xl border border-green-400/40 bg-white dark:bg-darkPanel rounded-2xl p-8 sm:p-10 shadow-2xl dark:shadow-[0_0_60px_rgba(0,255,136,0.16)]">
            <p class="text-xs font-bold uppercase tracking-[0.22em] text-green-600 dark:text-neon mb-4">Web3DShare Tour</p>
            <h2 class="text-4xl sm:text-5xl font-bold text-gray-950 dark:text-white leading-tight">
                Welcome to <span class="text-green-600 dark:text-neon">Web3DShare</span>
            </h2>
            <p class="mt-5 text-base sm:text-lg leading-relaxed text-gray-600 dark:text-gray-300">
                Take a quick tour through the main Web3DShare workflow. You will learn how to explore models, read stats, open the viewer, and use creator features without awkward page reloads.
            </p>
            <p class="mt-3 text-sm leading-relaxed text-gray-500 dark:text-gray-400">
                You can move forward or backward at any time, or skip the tour if you already know your way around.
            </p>
            <div class="mt-8 flex flex-col sm:flex-row sm:items-center gap-3">
                <button type="button" data-tour-start class="inline-flex justify-center bg-green-500 dark:bg-neon text-white dark:text-black font-bold px-7 py-3 rounded-xl hover:bg-green-600 dark:hover:bg-[#00cc6a] transition-all shadow-lg">
                    Get Started
                </button>
                <button type="button" data-tour-welcome-skip class="text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-neon transition-colors">
                    Skip tutorial
                </button>
            </div>
        </div>
    </div>

    <div data-tour-stage class="fixed inset-0 z-[300] hidden pointer-events-none">
        <div data-tour-panel="top" class="web3d-tour-panel"></div>
        <div data-tour-panel="right" class="web3d-tour-panel"></div>
        <div data-tour-panel="bottom" class="web3d-tour-panel"></div>
        <div data-tour-panel="left" class="web3d-tour-panel"></div>
        <div data-tour-highlight class="web3d-tour-highlight"></div>

        <section data-tour-tooltip class="web3d-tour-tooltip pointer-events-auto w-[min(420px,calc(100vw-2rem))] rounded-2xl border border-green-400/40 bg-white dark:bg-darkPanel p-5 shadow-2xl dark:shadow-[0_0_45px_rgba(0,255,136,0.16)]">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p data-tour-count class="text-[11px] font-bold uppercase tracking-[0.2em] text-green-600 dark:text-neon mb-2"></p>
                    <h3 data-tour-title class="text-xl font-bold text-gray-950 dark:text-white leading-snug"></h3>
                </div>
                <button type="button" data-tour-close class="text-gray-400 hover:text-red-500 transition-colors" aria-label="Close tutorial">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <p data-tour-body class="mt-3 text-sm leading-relaxed text-gray-600 dark:text-gray-300"></p>
            <div class="mt-5 grid grid-cols-2 gap-3">
                <button type="button" data-tour-back class="border border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-darkBg text-gray-700 dark:text-gray-300 font-semibold px-4 py-2.5 rounded-xl hover:border-green-400 dark:hover:border-neon/40 transition-all">
                    Back
                </button>
                <button type="button" data-tour-next class="bg-green-500 dark:bg-neon text-white dark:text-black font-bold px-4 py-2.5 rounded-xl hover:bg-green-600 dark:hover:bg-[#00cc6a] transition-all">
                    Next
                </button>
            </div>
            <button type="button" data-tour-skip class="mt-4 w-full text-center text-sm font-semibold text-gray-500 dark:text-gray-400 hover:text-green-600 dark:hover:text-neon transition-colors">
                Skip tutorial
            </button>
        </section>
    </div>
</div>

<script>
(() => {
    if (window.__web3dTourMounted) return;
    window.__web3dTourMounted = true;

    const root = document.getElementById('web3d-onboarding');
    if (!root) return;

    const isAuth = root.dataset.auth === '1';
    const userId = root.dataset.userId || 'guest';
    const audience = isAuth ? 'user' : 'guest';
    const version = 'v1';
    const activeKey = 'web3dshare_tour_active_' + version;
    const doneKey = audience === 'user'
        ? 'web3dshare_tour_done_user_' + userId + '_' + version
        : 'web3dshare_tour_done_guest_' + version;

    const welcome = root.querySelector('[data-tour-welcome]');
    const stage = root.querySelector('[data-tour-stage]');
    const highlight = root.querySelector('[data-tour-highlight]');
    const tooltip = root.querySelector('[data-tour-tooltip]');
    const panels = {
        top: root.querySelector('[data-tour-panel="top"]'),
        right: root.querySelector('[data-tour-panel="right"]'),
        bottom: root.querySelector('[data-tour-panel="bottom"]'),
        left: root.querySelector('[data-tour-panel="left"]'),
    };
    const titleEl = root.querySelector('[data-tour-title]');
    const bodyEl = root.querySelector('[data-tour-body]');
    const countEl = root.querySelector('[data-tour-count]');
    const backBtn = root.querySelector('[data-tour-back]');
    const nextBtn = root.querySelector('[data-tour-next]');

    const guestSteps = [
        {
            route: '/',
            target: '[data-tour="home-title"]',
            title: 'This is the Explore page',
            body: 'This is where you discover the latest 3D models from the community. Search, categories, tags, and sorting help you narrow down what you are looking for.'
        },
        {
            route: '/',
            target: '[data-tour="model-card"]',
            title: 'Each card is one model',
            body: 'A model card shows the thumbnail, title, creator, category, and quick stats. Click the thumbnail or title to open the viewer.'
        },
        {
            route: '/',
            target: '[data-tour="model-views"]',
            title: 'The eye icon means views',
            body: 'This number increases when a model is opened. It helps you see which models are getting attention.'
        },
        {
            route: '/',
            target: '[data-tour="model-stars"]',
            title: 'Stars show appreciation',
            body: 'Stars are used to mark models you like. You need to log in before using this feature.'
        },
        {
            route: '/',
            target: '[data-tour="model-downloads"]',
            title: 'Download counter',
            body: 'The download icon shows how many counted downloads a model has. The file can be downloaded from the model viewer.'
        },
        {
            route: '/',
            target: '[data-tour="auth-actions"]',
            title: 'Creator features require login',
            body: 'Upload, Request Verify, and My Models are available after you log in or register. Create an account first if you want to become a creator.'
        },
        {
            route: '/',
            target: '[data-tour="model-open"]',
            title: 'Now open a model',
            body: 'Next, we will continue inside the model viewer. Click Next and the tour will open the first model on this page.',
            nextLabel: 'Open model',
            action: 'open-first-model'
        },
        {
            target: '[data-tour="viewer-stage"]',
            title: 'This is the model viewer',
            body: 'This area loads the 3D file. You can rotate, zoom, and inspect the model directly in the browser.'
        },
        {
            target: '[data-tour="viewer-top-actions"]',
            title: 'Share and report',
            body: 'Share copies the model link. Report is used when a model has a problem, but you need to log in before submitting a report.'
        },
        {
            target: '[data-tour="viewer-actions"]',
            title: 'Star and download',
            body: 'This section lets you star or download the model. Some actions may ask you to log in first.'
        },
        {
            target: '[data-tour="viewer-comments"]',
            title: 'Comments',
            body: 'Comments are used for model discussions. If you are not logged in, you will be asked to log in before commenting.'
        }
    ];

    const userSteps = [
        {
            route: '/',
            target: '[data-tour="home-title"]',
            title: 'Your creator dashboard',
            body: 'You are logged in, so besides exploring models, you can upload, request verification, and manage your own models.'
        },
        {
            route: '/',
            target: '[data-tour="model-card"]',
            title: 'Start with a model card',
            body: 'Cards are the main entry point to the viewer. The stats under each card help you understand model performance.'
        },
        {
            route: '/',
            target: '[data-tour="sidebar-upload"]',
            title: 'Upload model',
            body: 'The Upload menu is used to submit a .glb file, thumbnail, category, tags, and description. Let us open it briefly.',
            nextLabel: 'Open Upload',
            action: 'navigate',
            href: '/upload'
        },
        {
            route: '/upload',
            target: '[data-tour="upload-panel"]',
            title: 'Form upload',
            body: 'This form is intentionally complete so uploaded models have clean metadata. The model file cannot be edited after upload, so delete and upload again if the 3D file changes.'
        },
        {
            route: '/upload',
            target: '[data-tour="upload-limit"]',
            title: 'Upload limit',
            body: 'Basic accounts have a monthly upload limit. Verified accounts unlock unlimited uploads.',
            nextLabel: 'Open Verify',
            action: 'navigate',
            href: '/verify'
        },
        {
            route: '/verify',
            target: '[data-tour="verify-panel"]',
            title: 'Request verification',
            body: 'This page lets you request a verified badge. The system checks the requirements before the form can be submitted.'
        },
        {
            route: '/verify',
            target: '[data-tour="verify-rules"]',
            title: 'Verification rules',
            body: 'These rules are checked server-side, so they cannot be bypassed through inspect element. You can adjust the numbers from env when you want stricter requirements.',
            nextLabel: 'Open My Models',
            action: 'navigate',
            href: '/?filter=my_models'
        },
        {
            route: '/?filter=my_models',
            target: '[data-tour="sidebar-my-models"]',
            title: 'My Models',
            body: 'This menu shows your own models. Edit and delete actions appear here.'
        },
        {
            route: '/?filter=my_models',
            target: '[data-tour="model-card"]',
            title: 'Manage card',
            body: 'Inside My Models, each card has Edit and Delete buttons. Edit only changes the title, description, category, and tags.'
        },
        {
            route: '/?filter=my_models',
            target: '[data-tour="model-open"]',
            title: 'Open a model from My Models',
            body: 'Click Next to open the viewer in management mode. Owner actions only appear when the viewer is opened from My Models.',
            nextLabel: 'Open model',
            action: 'open-first-model'
        },
        {
            target: '[data-tour="viewer-owner-actions"]',
            title: 'Owner actions in the viewer',
            body: 'If the viewer is opened from My Models and the model belongs to you, Edit and Delete also appear inside the viewer.'
        },
        {
            target: '[data-tour="viewer-comments"]',
            title: 'Comments and discussion',
            body: 'As a logged-in user, you can join model discussions through comments. Keep comments constructive so moderation stays clean.'
        }
    ];

    const steps = isAuth ? userSteps : guestSteps;
    let currentIndex = 0;
    let activeTarget = null;
    let resizeHandler = null;
    let scrollHandler = null;

    function currentPath() {
        return window.location.pathname + window.location.search;
    }

    function isHomePath() {
        return window.location.pathname === '/';
    }

    function routeMatches(route) {
        if (!route) return true;
        const url = new URL(route, window.location.origin);
        if (url.pathname !== window.location.pathname) return false;

        const required = new URLSearchParams(url.search);
        for (const [key, value] of required.entries()) {
            if (new URLSearchParams(window.location.search).get(key) !== value) {
                return false;
            }
        }

        if (url.pathname === '/' && !url.search && new URLSearchParams(window.location.search).get('filter') === 'my_models') {
            return false;
        }

        return true;
    }

    function saveActive(index) {
        sessionStorage.setItem(activeKey, JSON.stringify({ audience, index }));
    }

    function readActive() {
        try {
            const value = JSON.parse(sessionStorage.getItem(activeKey) || 'null');
            return value && value.audience === audience ? value : null;
        } catch (error) {
            return null;
        }
    }

    function clearActive() {
        sessionStorage.removeItem(activeKey);
    }

    function setDone() {
        localStorage.setItem(doneKey, '1');
    }

    function showWelcome() {
        root.classList.remove('hidden');
        welcome.classList.remove('hidden');
        welcome.classList.add('flex');
    }

    function hideWelcome() {
        welcome.classList.add('hidden');
        welcome.classList.remove('flex');
    }

    function showStage() {
        root.classList.remove('hidden');
        stage.classList.remove('hidden');
    }

    function hideStage() {
        stage.classList.add('hidden');
    }

    function finish(markDone = true) {
        if (markDone) setDone();
        clearActive();
        cleanupListeners();
        hideWelcome();
        hideStage();
        root.classList.add('hidden');
        activeTarget = null;
    }

    function cleanupListeners() {
        if (resizeHandler) window.removeEventListener('resize', resizeHandler);
        if (scrollHandler) window.removeEventListener('scroll', scrollHandler, true);
        resizeHandler = null;
        scrollHandler = null;
    }

    function waitForTarget(selector, timeout = 8000) {
        return new Promise(resolve => {
            if (!selector) {
                resolve(null);
                return;
            }

            const existing = document.querySelector(selector);
            if (existing) {
                resolve(existing);
                return;
            }

            const startedAt = Date.now();
            const timer = setInterval(() => {
                const found = document.querySelector(selector);
                if (found) {
                    clearInterval(timer);
                    resolve(found);
                } else if (Date.now() - startedAt > timeout) {
                    clearInterval(timer);
                    resolve(null);
                }
            }, 120);
        });
    }

    function prepareForTarget(selector) {
        if (selector && selector.includes('sidebar-')) {
            const sidebar = document.getElementById('sidebar');
            if (sidebar) sidebar.classList.remove('collapsed');
        }
    }

    function targetRect(target) {
        if (!target) {
            const width = Math.min(520, window.innerWidth - 48);
            const height = 180;
            return {
                left: (window.innerWidth - width) / 2,
                top: (window.innerHeight - height) / 2,
                right: (window.innerWidth + width) / 2,
                bottom: (window.innerHeight + height) / 2,
                width,
                height,
            };
        }

        const rect = target.getBoundingClientRect();
        const margin = 10;
        return {
            left: Math.max(12, rect.left - margin),
            top: Math.max(12, rect.top - margin),
            right: Math.min(window.innerWidth - 12, rect.right + margin),
            bottom: Math.min(window.innerHeight - 12, rect.bottom + margin),
            width: Math.min(window.innerWidth - 24, rect.width + margin * 2),
            height: Math.min(window.innerHeight - 24, rect.height + margin * 2),
        };
    }

    function placePanels(rect) {
        panels.top.style.left = '0px';
        panels.top.style.top = '0px';
        panels.top.style.width = '100vw';
        panels.top.style.height = rect.top + 'px';

        panels.bottom.style.left = '0px';
        panels.bottom.style.top = rect.bottom + 'px';
        panels.bottom.style.width = '100vw';
        panels.bottom.style.height = Math.max(0, window.innerHeight - rect.bottom) + 'px';

        panels.left.style.left = '0px';
        panels.left.style.top = rect.top + 'px';
        panels.left.style.width = rect.left + 'px';
        panels.left.style.height = rect.height + 'px';

        panels.right.style.left = rect.right + 'px';
        panels.right.style.top = rect.top + 'px';
        panels.right.style.width = Math.max(0, window.innerWidth - rect.right) + 'px';
        panels.right.style.height = rect.height + 'px';
    }

    function placeHighlight(rect, hasTarget) {
        highlight.style.opacity = hasTarget ? '1' : '0';
        highlight.style.left = rect.left + 'px';
        highlight.style.top = rect.top + 'px';
        highlight.style.width = rect.width + 'px';
        highlight.style.height = rect.height + 'px';
    }

    function clamp(value, min, max) {
        return Math.min(Math.max(value, min), max);
    }

    function placeTooltip(rect) {
        const tooltipWidth = tooltip.offsetWidth || Math.min(420, window.innerWidth - 32);
        const tooltipHeight = tooltip.offsetHeight || 240;
        let left = clamp(rect.left, 16, window.innerWidth - tooltipWidth - 16);
        let top = rect.bottom + 16;

        if (top + tooltipHeight > window.innerHeight - 16) {
            top = rect.top - tooltipHeight - 16;
        }

        if (top < 16) {
            top = clamp((window.innerHeight - tooltipHeight) / 2, 16, window.innerHeight - tooltipHeight - 16);
            if (rect.right + tooltipWidth + 16 < window.innerWidth) {
                left = rect.right + 16;
            } else if (rect.left - tooltipWidth - 16 > 0) {
                left = rect.left - tooltipWidth - 16;
            }
        }

        tooltip.style.left = left + 'px';
        tooltip.style.top = top + 'px';
    }

    function updateSpotlight() {
        const rect = targetRect(activeTarget);
        placePanels(rect);
        placeHighlight(rect, !!activeTarget);
        placeTooltip(rect);
    }

    async function renderStep(index) {
        if (index < 0) index = 0;
        if (index >= steps.length) {
            finish(true);
            return;
        }

        const step = steps[index];
        currentIndex = index;
        saveActive(index);

        if (step.route && !routeMatches(step.route)) {
            window.location.href = step.route;
            return;
        }

        prepareForTarget(step.target);
        showStage();
        hideWelcome();

        titleEl.textContent = step.title;
        bodyEl.textContent = step.body;
        countEl.textContent = 'Step ' + (index + 1) + ' / ' + steps.length;
        backBtn.disabled = index === 0;
        backBtn.classList.toggle('opacity-50', index === 0);
        nextBtn.textContent = step.nextLabel || (index === steps.length - 1 ? 'Finish' : 'Next');

        const target = await waitForTarget(step.target);
        activeTarget = target;

        if (target) {
            target.scrollIntoView({ behavior: 'smooth', block: 'center', inline: 'center' });
            await new Promise(resolve => setTimeout(resolve, 280));
        }

        updateSpotlight();
        cleanupListeners();
        resizeHandler = updateSpotlight;
        scrollHandler = updateSpotlight;
        window.addEventListener('resize', resizeHandler);
        window.addEventListener('scroll', scrollHandler, true);
    }

    function openFirstModel(nextIndex) {
        const opener = document.querySelector('[data-tour="model-open"]');
        if (!opener) {
            renderStep(nextIndex);
            return;
        }

        saveActive(nextIndex);
        opener.click();
        setTimeout(() => renderStep(nextIndex), 550);
    }

    function navigateTo(href, nextIndex) {
        saveActive(nextIndex);
        window.location.href = href;
    }

    function next() {
        const step = steps[currentIndex];
        const nextIndex = currentIndex + 1;

        if (step.action === 'open-first-model') {
            openFirstModel(nextIndex);
            return;
        }

        if (step.action === 'navigate' && step.href) {
            navigateTo(step.href, nextIndex);
            return;
        }

        renderStep(nextIndex);
    }

    function back() {
        renderStep(currentIndex - 1);
    }

    root.querySelector('[data-tour-start]').addEventListener('click', () => {
        hideWelcome();
        renderStep(0);
    });
    root.querySelector('[data-tour-welcome-skip]').addEventListener('click', () => finish(true));
    root.querySelector('[data-tour-close]').addEventListener('click', () => finish(true));
    root.querySelector('[data-tour-skip]').addEventListener('click', () => finish(true));
    nextBtn.addEventListener('click', next);
    backBtn.addEventListener('click', back);

    document.addEventListener('keydown', (event) => {
        if (stage.classList.contains('hidden')) return;
        if (event.key === 'Escape') finish(true);
        if (event.key === 'ArrowRight') next();
        if (event.key === 'ArrowLeft' && currentIndex > 0) back();
    });

    window.Web3DTour = {
        restart() {
            localStorage.removeItem(doneKey);
            clearActive();
            showWelcome();
        },
        skip: () => finish(true),
    };

    setTimeout(() => {
        const active = readActive();
        if (active) {
            renderStep(active.index);
            return;
        }

        if (!localStorage.getItem(doneKey) && isHomePath() && !new URLSearchParams(window.location.search).get('filter')) {
            showWelcome();
        }
    }, 450);
})();
</script>
