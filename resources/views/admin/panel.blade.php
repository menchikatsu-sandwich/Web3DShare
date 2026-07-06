@extends ('layouts.admin')

@section ('content')
    <style>
        .tab-content {
            display: none;
            animation: fadeIn 0.4s ease-in-out;
        }
        .tab-content.active {
            display: block;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        @keyframes growCategoryBar {
            from {
                opacity: 0;
                transform: scaleY(0);
            }
            to {
                opacity: 1;
                transform: scaleY(1);
            }
        }
        .category-chart-bar {
            animation: growCategoryBar 650ms cubic-bezier(0.16, 1, 0.3, 1) both;
            transform-origin: bottom;
        }
        @media (prefers-reduced-motion: reduce) {
            .category-chart-bar {
                animation: none;
            }
        }
    </style>
    <div class="flex flex-col items-start gap-4 lg:flex-row lg:gap-8">
        <div
            class="w-full flex-shrink-0 rounded-xl border border-gray-200 bg-white p-2 shadow-md transition-colors duration-300 lg:sticky lg:top-24 lg:w-72 lg:rounded-2xl lg:p-6 dark:border-neon/20 dark:bg-darkPanel dark:shadow-[0_0_30px_rgba(0,255,136,0.05)]"
        >
            <h2 class="mb-3 ml-2 hidden text-xs font-bold tracking-widest text-gray-500 uppercase lg:block">Control Panel</h2>
            <ul class="no-scrollbar flex gap-2 overflow-x-auto text-sm font-medium lg:block lg:space-y-2 lg:overflow-visible">
                <li>
                    <button
                        onclick="changeTab('dashboard', this)"
                        class="tab-btn flex w-max shrink-0 items-center gap-2 rounded-lg px-3 py-2.5 text-gray-600 transition-all hover:bg-green-50 hover:text-green-600 lg:w-full lg:gap-3 lg:rounded-xl lg:px-4 lg:py-3 dark:text-gray-400 dark:hover:bg-neon/5 dark:hover:text-neon"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                        </svg>
                        Dashboard
                    </button>
                </li>
                <li>
                    <button
                        onclick="changeTab('models', this)"
                        class="tab-btn flex w-max shrink-0 items-center gap-2 rounded-lg px-3 py-2.5 text-gray-600 transition-all hover:bg-green-50 hover:text-green-600 lg:w-full lg:gap-3 lg:rounded-xl lg:px-4 lg:py-3 dark:text-gray-400 dark:hover:bg-neon/5 dark:hover:text-neon"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                        Manage Models
                    </button>
                </li>
                <li>
                    <button
                        onclick="changeTab('reports', this)"
                        class="tab-btn flex w-max shrink-0 items-center gap-2 rounded-lg px-3 py-2.5 text-gray-600 transition-all hover:bg-green-50 hover:text-green-600 lg:w-full lg:gap-3 lg:rounded-xl lg:px-4 lg:py-3 dark:text-gray-400 dark:hover:bg-neon/5 dark:hover:text-neon"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        Reports
                    </button>
                </li>
                <li>
                    <button
                        onclick="changeTab('verify', this)"
                        class="tab-btn flex w-max shrink-0 items-center gap-2 rounded-lg px-3 py-2.5 text-gray-600 transition-all hover:bg-green-50 hover:text-green-600 lg:w-full lg:gap-3 lg:rounded-xl lg:px-4 lg:py-3 dark:text-gray-400 dark:hover:bg-neon/5 dark:hover:text-neon"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" /></svg>
                        Verifications
                    </button>
                </li>
                <li>
                    <button
                        onclick="changeTab('category', this)"
                        class="tab-btn flex w-max shrink-0 items-center gap-2 rounded-lg px-3 py-2.5 text-gray-600 transition-all hover:bg-green-50 hover:text-green-600 lg:w-full lg:gap-3 lg:rounded-xl lg:px-4 lg:py-3 dark:text-gray-400 dark:hover:bg-neon/5 dark:hover:text-neon"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                        </svg>
                        Categories
                    </button>
                </li>
                @can ('admin')
                    <li>
                        <button
                            onclick="changeTab('users', this)"
                            class="tab-btn flex w-max shrink-0 items-center gap-2 rounded-lg px-3 py-2.5 text-gray-600 transition-all hover:bg-green-50 hover:text-green-600 lg:w-full lg:gap-3 lg:rounded-xl lg:px-4 lg:py-3 dark:text-gray-400 dark:hover:bg-neon/5 dark:hover:text-neon"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                            Users Control
                        </button>
                    </li>
                @endcan
            </ul>
        </div>

        <div
            class="min-h-[520px] w-full flex-1 rounded-xl border border-gray-200 bg-white p-4 shadow-md transition-colors duration-300 sm:p-6 lg:min-h-[600px] lg:rounded-2xl lg:p-8 dark:border-neon/10 dark:bg-darkPanel dark:shadow-none"
        >
            <div id="dashboard" class="tab-content space-y-6">
                <div
                    class="flex flex-col justify-between gap-3 border-b border-gray-200 pb-5 sm:flex-row sm:items-end sm:pb-6 dark:border-gray-800"
                >
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 sm:text-3xl dark:text-white">
                            Overview <span class="text-green-600 dark:text-neon">Statistics</span>
                        </h2>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">A live operational snapshot of the Web3DShare community.</p>
                    </div>
                    <span
                        class="inline-flex items-center rounded-lg bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300"
                        ><span class="mr-1.5 h-2 w-2 rounded-full bg-green-500 dark:bg-neon"></span>All time</span
                    >
                </div>

                <div class="grid gap-3 sm:grid-cols-2 sm:gap-4 lg:grid-cols-4">
                    @foreach ([['Published models', $stats['models']], ['Community members', $stats['users']], ['Open reports', $stats['reports']], ['Verification queue', $stats['verify']]] as [$label, $value])
                        <div
                            class="rounded-lg border border-gray-200 bg-gray-50 p-4 transition-colors hover:border-green-400 sm:p-5 dark:border-gray-800 dark:bg-darkBg dark:hover:border-neon/50"
                        >
                            <p class="text-xs font-bold tracking-widest text-gray-500 uppercase dark:text-gray-400">{{ $label }}</p>
                            <p class="mt-2 text-3xl font-bold text-green-600 sm:mt-3 sm:text-4xl dark:text-neon">{{ $value }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="grid gap-6 xl:grid-cols-3">
                    <section
                        class="flex flex-col rounded-lg border border-gray-200 bg-gray-50 p-4 sm:p-6 xl:col-span-2 dark:border-gray-800 dark:bg-darkBg"
                    >
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h3 class="font-semibold text-gray-900 dark:text-white">
                                    Published models by category
                                </h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">The five largest categories by published model count.</p>
                            </div>
                            <div
                                class="hidden items-center gap-2 rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-medium text-gray-500 sm:flex dark:border-gray-700 dark:text-gray-400"
                            >
                                <span class="inline-flex items-center gap-1.5"
                                    ><span class="h-2 w-2 rounded-full bg-green-500 dark:bg-neon"></span>Models</span
                                >
                            </div>
                        </div>

                        @if ($dashboard['category_distribution']->isNotEmpty())
                            <div
                                class="mt-6 flex flex-1 items-end gap-2 overflow-x-auto border-b border-gray-200 pb-3 sm:mt-8 sm:gap-3 dark:border-gray-800"
                                style="height: clamp(220px, 52vw, 320px)"
                            >
                                @foreach ($dashboard['category_distribution'] as $category)
                                    <div
                                        class="group flex min-w-[56px] flex-1 flex-col justify-end gap-2 text-center sm:min-w-0 sm:gap-3"
                                        title="{{ $category->name }}"
                                    >
                                        <div
                                            style="
                                                display: flex;
                                                height: 280px;
                                                align-items: flex-end;
                                                justify-content: center;
                                            "
                                        >
                                            <div
                                                class="category-chart-bar flex w-full max-w-12 items-start justify-center rounded-t-lg pt-2 text-xs font-bold text-black transition-opacity group-hover:opacity-80"
                                                style="height: {{ 140 + (($category->models_count / $dashboard['category_distribution_max']) * 140) }}px; background-color: #00e983; animation-delay: {{ $loop->index * 100 }}ms"
                                                title="{{ $category->models_count }} published models"
                                            >
                                                {{ $category->models_count }}
                                            </div>
                                        </div>
                                        <span
                                            class="truncate text-[11px] font-semibold text-gray-500 dark:text-gray-400"
                                            >{{ $category->name }}</span
                                        >
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div
                                class="mt-6 flex min-h-64 flex-1 items-center justify-center rounded-lg border border-dashed border-gray-300 text-center text-sm font-medium text-gray-500 dark:border-gray-700 dark:text-gray-400"
                            >
                                No published models are available for category reporting yet.
                            </div>
                        @endif
                    </section>

                    <section
                        class="rounded-lg border border-gray-200 bg-gray-50 p-4 sm:p-6 dark:border-gray-800 dark:bg-darkBg"
                    >
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Moderation queue</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Items that need staff attention.</p>
                        <dl class="mt-6 space-y-3">
                            <div
                                class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm dark:border-gray-800 dark:bg-darkPanel"
                            >
                                <dt class="text-gray-600 dark:text-gray-300">Pending reports</dt>
                                <dd
                                    class="rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-bold text-yellow-800 dark:bg-yellow-500/20 dark:text-yellow-400"
                                >
                                    {{ $dashboard['queue']['pending_reports'] }}
                                </dd>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm dark:border-gray-800 dark:bg-darkPanel"
                            >
                                <dt class="text-gray-600 dark:text-gray-300">Reports in review</dt>
                                <dd
                                    class="rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-bold text-blue-800 dark:bg-blue-500/20 dark:text-blue-400"
                                >
                                    {{ $dashboard['queue']['reviewed_reports'] }}
                                </dd>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-lg border border-gray-200 bg-white px-4 py-3 text-sm dark:border-gray-800 dark:bg-darkPanel"
                            >
                                <dt class="text-gray-600 dark:text-gray-300">Verification requests</dt>
                                <dd
                                    class="rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-bold text-green-800 dark:bg-neon/20 dark:text-neon"
                                >
                                    {{ $dashboard['queue']['verification_requests'] }}
                                </dd>
                            </div>
                        </dl>
                    </section>

                    <section
                        class="rounded-lg border border-gray-200 bg-gray-50 p-4 sm:p-6 dark:border-gray-800 dark:bg-darkBg"
                    >
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Creator and staff access</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Access status across {{ $dashboard['tiers']['total'] }} accounts.</p>
                        @php
                            $tierTotal = max(1, $dashboard['tiers']['total']);
                        @endphp
                        <div class="mt-6 space-y-5">
                            <div>
                                <div class="mb-1.5 flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-300">Verified</span
                                    ><span
                                        class="font-semibold text-green-600 dark:text-neon"
                                        >{{ $dashboard['tiers']['verified'] }}</span
                                    >
                                </div>
                                <div class="h-2.5 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                    <div
                                        class="h-full rounded-full"
                                        style="width: {{ ($dashboard['tiers']['verified'] / $tierTotal) * 100 }}%; background-color: #00e983"
                                    ></div>
                                </div>
                            </div>
                            <div>
                                <div class="mb-1.5 flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-300">Basic</span
                                    ><span
                                        class="font-semibold text-gray-700 dark:text-gray-300"
                                        >{{ $dashboard['tiers']['basic'] }}</span
                                    >
                                </div>
                                <div class="h-2.5 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                    <div
                                        class="h-full rounded-full"
                                        style="width: {{ ($dashboard['tiers']['basic'] / $tierTotal) * 100 }}%; background-color: #cbd5e1"
                                    ></div>
                                </div>
                            </div>
                            <div>
                                <div class="mb-1.5 flex justify-between text-sm">
                                    <span class="text-gray-600 dark:text-gray-300">Staff</span
                                    ><span
                                        class="font-semibold text-blue-600 dark:text-blue-400"
                                        >{{ $dashboard['tiers']['staff'] }}</span
                                    >
                                </div>
                                <div class="h-2.5 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                    <div
                                        class="h-full rounded-full"
                                        style="width: {{ ($dashboard['tiers']['staff'] / $tierTotal) * 100 }}%; background-color: #60a5fa"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section
                        class="rounded-lg border border-gray-200 bg-gray-50 p-4 sm:p-6 dark:border-gray-800 dark:bg-darkBg"
                    >
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Lifetime engagement</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Total interactions across all models.</p>
                        <dl class="mt-6 space-y-4">
                            <div
                                class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-darkPanel"
                            >
                                <dt class="text-sm font-semibold text-gray-600 dark:text-gray-300">Total views</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ number_format($dashboard['engagement']['views']) }}
                                </dd>
                            </div>
                            <div
                                class="flex items-center justify-between rounded-lg border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-darkPanel"
                            >
                                <dt class="text-sm font-semibold text-gray-600 dark:text-gray-300">Downloads</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ number_format($dashboard['engagement']['downloads']) }}
                                </dd>
                            </div>
                        </dl>
                    </section>

                    <section
                        class="rounded-lg border border-gray-200 bg-gray-50 p-4 sm:p-6 xl:col-span-3 dark:border-gray-800 dark:bg-darkBg"
                    >
                        <div
                            class="flex flex-col items-start justify-between gap-3 border-b border-gray-200 pb-4 sm:flex-row sm:items-center sm:gap-4 dark:border-gray-800"
                        >
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Most viewed models</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Top published models by counted views, then downloads.</p>
                            </div>
                            <button
                                type="button"
                                onclick="changeTab('models', document.querySelector('button[onclick*=\'models\']'))"
                                class="w-full rounded-lg bg-green-100 px-4 py-2 text-sm font-bold text-green-700 transition-colors hover:bg-green-200 sm:w-auto dark:bg-neon/10 dark:text-neon dark:hover:bg-neon/20"
                            >
                                Manage models
                            </button>
                        </div>
                        <div class="mt-2 divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse ($dashboard['top_models'] as $model)
                                <a
                                    href="/models/{{ $model->id }}"
                                    onclick="openModel('{{ $model->id }}', event)"
                                    class="group flex flex-col justify-between gap-3 rounded-lg px-2 py-4 transition-colors hover:bg-white sm:flex-row sm:items-center dark:hover:bg-white/5"
                                >
                                    <div class="min-w-0">
                                        <p class="truncate font-bold text-gray-900 group-hover:text-green-600 dark:text-white dark:group-hover:text-neon">{{ $model->title }}</p>
                                        <p class="mt-1 text-xs font-medium text-gray-500 dark:text-gray-400">Uploaded by {{ $model->user->username }}</p>
                                    </div>
                                    <div
                                        class="flex shrink-0 gap-2 text-xs font-medium text-gray-600 dark:text-gray-300"
                                    >
                                        <span
                                            class="rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 dark:border-gray-700 dark:bg-gray-800"
                                            >{{ number_format($model->view_count) }} views</span
                                        ><span
                                            class="rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 dark:border-gray-700 dark:bg-gray-800"
                                            >{{ number_format($model->download_count) }} downloads</span
                                        >
                                    </div>
                                </a>
                            @empty
                                <p class="py-10 text-center text-sm font-medium text-gray-500 dark:text-gray-400">No published models yet.</p>
                            @endforelse
                        </div>
                    </section>
                </div>
            </div>

            <div id="models" class="tab-content">
                <h2 class="mb-5 text-xl font-bold text-gray-900 sm:mb-6 sm:text-2xl dark:text-white">
                    Manage <span class="text-green-600 dark:text-neon">Models</span>
                </h2>

                <div class="mb-6">
                    <input
                        type="text"
                        id="searchModels"
                        onkeyup="filterModels()"
                        placeholder="Search model title or user..."
                        class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-gray-900 transition-all focus:border-green-500 focus:outline-none md:w-80 dark:border-gray-800 dark:bg-darkBg dark:text-gray-200 dark:focus:border-neon"
                    />
                </div>

                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3 lg:gap-6" id="modelsGrid">
                    @foreach ($models as $m)
                        <div
                            class="model-item group overflow-hidden rounded-xl border border-gray-200 bg-gray-50 p-3 shadow-sm transition-colors hover:border-green-300 dark:border-gray-800 dark:bg-darkBg dark:hover:border-neon/30"
                        >
                            <a href="/models/{{ $m->id }}" onclick="openModel('{{ $m->id }}', event)">
                                <div class="relative mb-3 h-40 w-full overflow-hidden rounded-lg">
                                    <img
                                        src="{{ $m->thumbnailUrl() }}"
                                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                    />
                                </div>
                            </a>
                            <h3 class="model-title truncate font-semibold text-gray-800 dark:text-gray-200">
                                {{ $m->title }}
                            </h3>
                            <p class="mb-4 flex items-center gap-1 text-xs text-gray-500">
                                <span>By:</span>
                                <span class="inline-flex items-center gap-1">
                                    <span class="model-author font-medium text-green-600 dark:text-neon">
                                        {{ $m->user->username }}
                                    </span>

                                    @if ($m->user->upload_tier === 'verified')
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-3.5 w-3.5 flex-shrink-0 text-green-500 dark:text-neon" title="Verified Creator">
                                            <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0                     011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49                     0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.                  573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75                0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                        </svg>
                                    @endif
                                </span>
                            </p>
                            <form
                                method="POST"
                                action="/admin/delete-model/{{ $m->id }}"
                                onsubmit="return confirmFormSubmission(this);"
                                data-confirm-title="Take down this model?"
                                data-confirm-message="This permanently deletes the model and sends the reason below to the owner. This action cannot be undone."
                                data-confirm-action="Take down model"
                            >
                                @csrf
                                @method ('DELETE')
                                <label class="mb-2 block text-[10px] font-bold tracking-widest text-gray-500 uppercase dark:text-gray-400">
                                    Notice to owner
                                </label>
                                <textarea
                                    name="owner_message"
                                    required
                                    maxlength="2000"
                                    rows="3"
                                    placeholder="Explain why this model is being taken down."
                                    class="mb-3 w-full resize-none rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs text-gray-800 focus:border-red-400 focus:outline-none dark:border-gray-800 dark:bg-black/20 dark:text-gray-200"
                                ></textarea>
                                <button
                                    class="w-full rounded-lg border border-red-200 bg-red-100 py-2 text-sm font-medium text-red-600 shadow-sm transition-all duration-300 hover:border-red-600 hover:bg-red-600 hover:text-white hover:shadow-md hover:shadow-red-500/20 active:scale-[0.98] dark:border-transparent dark:bg-red-500/10 dark:text-red-500 dark:hover:bg-red-600 dark:hover:text-white"
                                >
                                    Take Down
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="reports" class="tab-content">
                <div class="mb-5 flex flex-col justify-between gap-3 sm:mb-6 sm:flex-row sm:items-center">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 sm:text-2xl dark:text-white">
                            User <span class="text-green-600 dark:text-neon">Reports</span>
                        </h2>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Manage and moderate reported 3D models.</p>
                    </div>

                    <!-- TAB FILTER SYSTEM -->
                    <div
                        class="grid w-full grid-cols-2 rounded-xl border border-gray-800 bg-black/40 p-1 shadow-inner backdrop-blur-sm sm:inline-flex sm:w-auto"
                    >
                        <button
                            type="button"
                            onclick="filterReports('ongoing')"
                            id="tab-ongoing"
                            class="report-tab-btn flex min-w-0 items-center justify-center gap-1.5 rounded-lg border border-transparent px-2 py-2 text-center text-[10px] font-bold tracking-wider uppercase transition-all duration-300 sm:gap-2 sm:px-4 sm:text-xs"
                        >
                            <span>Pending & On Going</span>
                            <span
                                id="count-ongoing"
                                class="rounded px-1.5 py-0.5 text-[10px] font-black transition-colors duration-300"
                                >0</span
                            >
                        </button>
                        <button
                            type="button"
                            onclick="filterReports('resolved')"
                            id="tab-resolved"
                            class="report-tab-btn flex min-w-0 items-center justify-center gap-1.5 rounded-lg border border-transparent px-2 py-2 text-center text-[10px] font-bold tracking-wider uppercase transition-all duration-300 sm:gap-2 sm:px-4 sm:text-xs"
                        >
                            <span>Resolved</span>
                            <span
                                id="count-resolved"
                                class="rounded px-1.5 py-0.5 text-[10px] font-black transition-colors duration-300"
                                >0</span
                            >
                        </button>
                    </div>
                </div>

                <!-- CARDS CONTAINER -->
                <div class="space-y-3">
                    @forelse ($reports as $r)
                        @php
                            // Tentukan grup data untuk filter JS nanti
                            $group = ($r->report_status === 'resolved') ? 'resolved' : 'ongoing';
                            $reviewerRole = $r->reviewer ? ucfirst($r->reviewer->role) : '-';
                            $modelIsAvailable = $r->model3d && ! $r->model3d->trashed();
                            $description = trim((string) $r->description);
                            $detailText = $description;
                            $replyText = null;

                            if (str_starts_with($description, 'Reply:')) {
                                $detailText = '';
                                $replyText = trim(substr($description, strlen('Reply:')));
                            } elseif (preg_match('/\R\RReply:\s*/', $description)) {
                                [$detailText, $replyText] = preg_split('/\R\RReply:\s*/', $description, 2);
                                $detailText = trim($detailText);
                                $replyText = trim($replyText);
                            }
                        @endphp
                        <div
                            data-report-group="{{ $group }}"
                            class="report-card rounded-xl border border-gray-200 bg-gray-50 p-4 shadow-sm transition-all duration-200 sm:p-5 dark:border-gray-800 dark:bg-darkBg"
                        >
                            <div class="flex flex-col justify-between gap-5 lg:flex-row lg:items-start">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                            {{ $r->model3d->title ?? 'Deleted model' }}
                                        </p>

                                        <span class="text-[10px] uppercase tracking-wider px-2 py-0.5 rounded-full border font-medium
                                            {{ $r->report_status === 'pending' 
                                                ? 'bg-yellow-100 dark:bg-yellow-500/10 text-yellow-700 dark:text-yellow-400 border-yellow-300 dark:border-yellow-500/20' 
                                                : ($r->report_status === 'reviewed' 
                                                    ? 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border-blue-300 dark:border-blue-500/20' 
                                                    : 'bg-green-100 dark:bg-green-500/10 text-green-700 dark:text-neon border-green-300 dark:border-green-500/20') 
                                            }}">
                                            {{ $r->report_status === 'reviewed' ? 'In review' : $r->report_status }}
                                        </span>

                                        @if(!$r->model3d || !is_null($r->model3d->deleted_at))
                                            <span class="text-[10px] uppercase tracking-wider px-2 py-0.5 rounded-full border font-medium bg-red-500/10 text-red-500 border-red-500/20">
                                                Taken down
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-2 flex items-center gap-2 text-sm text-red-600 dark:text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                        Reason:
                                        <span
                                            class="font-medium text-gray-900 dark:text-gray-100"
                                            >{{ ucwords(str_replace('_', ' ', $r->reason)) }}</span
                                        >
                                    </p>
                                    <div
                                        class="mt-3 grid gap-2 border-t border-gray-200/60 pt-3 text-xs text-gray-500 sm:grid-cols-2 dark:border-gray-800/60 dark:text-gray-400"
                                    >
                                        <p>Reporter: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $r->reporter->username ?? 'Deleted user' }}</span></p>
                                        <p>Model owner: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $r->model3d->user->username ?? 'Deleted user' }}</span></p>
                                        <p>Created: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $r->created_at?->format('M j, Y H:i') ?? 'Unknown' }}</span></p>
                                        <p>Reviewer: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $reviewerRole }}</span></p>
                                    </div>
                                    @if ($description)
                                        <div class="mt-3 space-y-3">
                                            @if ($detailText !== '')
                                                <div
                                                    class="rounded-lg border border-gray-200 bg-white p-3 text-xs text-gray-600 dark:border-gray-800/80 dark:bg-black/20 dark:text-gray-400"
                                                >
                                                    <span
                                                        class="mb-1 block text-[10px] font-bold tracking-wide text-gray-400 uppercase"
                                                        >Details:</span
                                                    >
                                                    <p class="leading-relaxed whitespace-pre-wrap">{{ $detailText }}</p>
                                                </div>
                                            @endif

                                            @if ($replyText)
                                                <div
                                                    class="rounded-lg border border-green-300 bg-green-50 p-3 text-xs text-gray-700 dark:border-neon/20 dark:bg-neon/5 dark:text-gray-300"
                                                >
                                                    <span
                                                        class="mb-1 block text-[10px] font-bold tracking-wide text-green-600 uppercase dark:text-neon"
                                                        >Moderator reply:</span
                                                    >
                                                    <p class="leading-relaxed whitespace-pre-wrap">{{ $replyText }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                    @if ($r->owner_message)
                                        <div
                                            class="mt-3 rounded-lg border border-blue-200 bg-blue-50 p-3 text-xs text-blue-900 dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-200"
                                        >
                                            <span
                                                class="mb-1 block text-[10px] font-bold tracking-wide text-blue-700 uppercase dark:text-blue-300"
                                                >Notice sent to owner:</span
                                            >
                                            <p class="leading-relaxed whitespace-pre-wrap">{{ $r->owner_message }}</p>
                                            <p class="mt-2 text-[10px] font-semibold uppercase tracking-wide text-blue-700/70 dark:text-blue-300/70">
                                                {{ $r->owner_action === 'model_taken_down' ? 'Model taken down' : 'Report resolved' }}
                                                @if ($r->owner_notified_at)
                                                    &middot; {{ $r->owner_notified_at->format('M j, Y H:i') }}
                                                @endif
                                            </p>
                                        </div>
                                    @endif
                                </div>

                                <div
                                    class="flex w-full flex-shrink-0 flex-col justify-end gap-2 sm:flex-row lg:w-44 lg:flex-col"
                                >
                                    @if ($modelIsAvailable)
                                        <a
                                            href="/models/{{ $r->model_id }}"
                                            class="rounded-lg border border-gray-200 bg-white px-4 py-2 text-center text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700/50"
                                            >Open Model</a
                                        >
                                    @endif
                                    @if ($r->report_status === 'pending')
                                        <form method="POST" action="/admin/reports/{{ $r->id }}/reviewed">
                                            @csrf
                                            <button
                                                class="w-full rounded-lg border border-blue-200 bg-blue-100 px-4 py-2 text-sm font-medium text-blue-700 transition-colors hover:bg-blue-600 hover:text-white dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-400"
                                            >
                                                Start Review
                                            </button>
                                        </form>
                                    @endif
                                    @if ($r->report_status !== 'resolved')
                                        <form method="POST" action="/admin/reports/{{ $r->id }}/resolve">
                                            @csrf
                                            <textarea
                                                name="owner_message"
                                                maxlength="2000"
                                                rows="3"
                                                placeholder="Optional notice to model owner."
                                                class="mb-2 w-full resize-none rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs text-gray-800 focus:border-green-400 focus:outline-none dark:border-gray-800 dark:bg-black/20 dark:text-gray-200"
                                            ></textarea>
                                            <button
                                                class="w-full rounded-lg border border-green-300 bg-green-100 px-4 py-2 text-sm font-semibold text-green-700 transition-colors hover:bg-green-600 hover:text-white dark:border-green-500/20 dark:bg-green-500/10 dark:text-neon dark:hover:text-black"
                                            >
                                                Resolve
                                            </button>
                                        </form>
                                    @endif
                                    @if ($modelIsAvailable && $r->report_status !== 'resolved')
                                        <form
                                            method="POST"
                                            action="/admin/delete-model/{{ $r->model_id }}"
                                            onsubmit="return confirmFormSubmission(this);"
                                            data-confirm-title="Take down this model?"
                                            data-confirm-message="This will permanently delete the model and send the reason below to the owner. This action cannot be undone."
                                            data-confirm-action="Take down model"
                                        >
                                            @csrf
                                            @method ('DELETE')
                                            <textarea
                                                name="owner_message"
                                                required
                                                maxlength="2000"
                                                rows="3"
                                                placeholder="Required reason for the model owner."
                                                class="mb-2 w-full resize-none rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs text-gray-800 focus:border-red-400 focus:outline-none dark:border-gray-800 dark:bg-black/20 dark:text-gray-200"
                                            ></textarea>
                                            <button
                                                class="w-full rounded-lg border border-red-200 bg-red-100 px-4 py-2 text-sm font-medium text-red-600 transition-colors hover:bg-red-500 hover:text-white dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-500"
                                            >
                                                Take Down
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div id="report-empty-state" class="py-10 text-center text-gray-500">No reports yet.</div>
                    @endforelse

                    <!-- DYNAMIC EMPTY STATE (Akan muncul via JS jika data filter kosong) -->
                    <div id="report-filter-empty" class="hidden py-10 text-center text-gray-500 dark:text-gray-400">
                        No data found for this filter.
                    </div>
                </div>
            </div>

            <div id="verify" class="tab-content">
                <h2 class="mb-5 text-xl font-bold text-gray-900 sm:mb-6 sm:text-2xl dark:text-white">
                    Verification <span class="text-green-600 dark:text-neon">Requests</span>
                </h2>
                <div class="space-y-3">
                    @forelse ($requests as $req)
                        <div
                            class="flex flex-col justify-between rounded-xl border border-gray-200 bg-gray-50 p-4 shadow-sm sm:p-5 md:flex-row md:items-center dark:border-gray-800 dark:bg-darkBg"
                        >
                            <div class="mb-4 md:mb-0">
                                <p class="flex items-center gap-2 text-lg font-semibold text-gray-800 dark:text-gray-200">
                                    {{ $req->user->username }}
                                    <span
                                        class="rounded border border-blue-300 bg-blue-100 px-2 py-0.5 text-xs text-blue-700 dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-400"
                                        >Pending</span
                                    >
                                </p>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Note: "{{ $req->note }}"</p>
                            </div>
                            <div class="grid grid-cols-2 gap-2 sm:flex sm:gap-3">
                                <form method="POST" action="/verify/{{ $req->id }}/approve">
                                    @csrf
                                    <button
                                        class="w-full rounded-lg border border-green-300 bg-green-100 px-5 py-2 text-sm font-semibold text-green-700 transition-colors hover:bg-green-600 hover:text-white dark:border-green-500/20 dark:bg-green-500/10 dark:text-neon dark:hover:text-black"
                                    >
                                        Approve
                                    </button>
                                </form>
                                <form method="POST" action="/verify/{{ $req->id }}/reject">
                                    @csrf
                                    <button
                                        class="w-full rounded-lg border border-red-200 bg-red-100 px-5 py-2 text-sm font-medium text-red-600 transition-colors hover:bg-red-600 hover:text-white dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-500"
                                    >
                                        Reject
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center text-gray-500">No verification requests pending.</div>
                    @endforelse
                </div>
            </div>

            <div id="category" class="tab-content">
                <h2 class="mb-5 text-xl font-bold text-gray-900 sm:mb-6 sm:text-2xl dark:text-white">
                    Manage <span class="text-green-600 dark:text-neon">Categories</span>
                </h2>
                <form
                    method="POST"
                    action="/admin/category"
                    class="mb-6 flex flex-col gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 shadow-sm sm:mb-8 sm:flex-row dark:border-gray-800 dark:bg-darkBg"
                >
                    @csrf
                    <input
                        name="name"
                        placeholder="Enter new category name..."
                        required
                        class="flex-1 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-gray-900 transition-all focus:border-green-500 focus:ring-1 focus:ring-green-500 focus:outline-none dark:border-gray-700 dark:bg-black dark:text-gray-200 dark:focus:border-neon dark:focus:ring-neon"
                    />
                    <button
                        class="rounded-lg bg-green-500 px-6 py-2.5 font-semibold text-white shadow-sm transition-colors hover:bg-green-600 dark:bg-neon dark:text-black dark:hover:bg-[#00cc6a]"
                    >
                        Add Category
                    </button>
                </form>
                <div class="grid gap-4 md:grid-cols-2">
                    @foreach ($categories as $c)
                        @continue (!is_object($c) || !isset($c->id, $c->name))
                        <div
                            class="flex items-center justify-between gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 shadow-sm transition-colors hover:border-green-300 dark:border-gray-800 dark:bg-darkBg dark:hover:border-neon/20"
                        >
                            <p class="font-medium text-gray-800 dark:text-gray-200">{{ $c->name }}</p>
                            <form method="POST" action="/admin/category/{{ $c->id }}">
                                @csrf
                                @method ('DELETE')
                                <button
                                    class="rounded-lg p-2 text-gray-400 transition-colors hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-500/10 dark:hover:text-red-500"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>

            @can ('admin')
                <div id="users" class="tab-content">
                    <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-end">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                User <span class="text-green-600 dark:text-neon">Management</span>
                            </h2>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage member roles and remove accounts when necessary.</p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Administrator accounts are protected.</p>
                    </div>

                    <div class="mt-6 mb-6">
                        <input
                            type="text"
                            id="searchUsers"
                            onkeyup="filterUsers()"
                            placeholder="Search username, role, or uploader tier..."
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-gray-900 transition-all focus:border-green-500 focus:outline-none md:w-80 dark:border-gray-800 dark:bg-darkBg dark:text-gray-200 dark:focus:border-neon"
                        />
                    </div>

                    <div class="space-y-3" id="usersList">
                        @forelse ($users as $u)
                            @php
                                $isStaffAccount = $u->isStaff();
                                $accessLabel = $isStaffAccount ? 'staff' : $u->upload_tier;
                                $accessClasses = $isStaffAccount
                                    ? ($u->role === 'admin'
                                        ? 'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400'
                                        : 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400')
                                    : ($u->upload_tier === 'verified'
                                        ? 'bg-green-100 text-green-700 dark:bg-neon/15 dark:text-neon'
                                        : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-300');
                            @endphp
                            <div
                                class="user-item flex flex-col justify-between rounded-xl border border-gray-200 bg-gray-50 p-4 shadow-sm transition-colors hover:border-gray-300 md:flex-row md:items-center dark:border-gray-800 dark:bg-darkBg dark:hover:border-gray-700"
                            >
                                <div class="mb-4 flex min-w-0 items-center gap-3 md:mb-0">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full font-bold {{ $accessClasses }}"
                                    >
                                        {{ substr($u->username, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-2">
                                            <p class="user-name truncate font-semibold text-gray-800 dark:text-gray-200">{{ $u->username }}</p>
                                            <span
                                                class="user-role rounded-full px-2 py-0.5 text-[10px] font-semibold tracking-wider uppercase {{ $u->role === 'admin' ? 'bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400' : ($u->role === 'moderator' ? 'bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400' : 'bg-gray-200 text-gray-600 dark:bg-gray-700 dark:text-gray-300') }}"
                                                >{{ $u->role }}</span
                                            >
                                            <span
                                                class="user-tier rounded-full px-2 py-0.5 text-[10px] font-semibold tracking-wider uppercase {{ $accessClasses }}"
                                                >{{ $accessLabel }}</span
                                            >
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $u->models_count }} models · {{ number_format($u->models_sum_download_count ?? 0) }} downloads · Joined {{ $u->created_at?->format('M j, Y') ?? 'Unknown' }}</p>
                                    </div>
                                </div>
                                <div class="grid w-full grid-cols-2 gap-2 sm:flex sm:w-auto sm:flex-wrap">
                                    @if ($u->role === 'user')
                                        <form method="POST" action="/admin/promote/{{ $u->id }}" class="min-w-0">
                                            @csrf
                                            <button
                                    class="flex w-full min-w-28 justify-center rounded-lg border border-blue-200 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700 transition-colors hover:bg-blue-600 hover:text-white sm:w-auto dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-400 dark:hover:bg-blue-600 dark:hover:text-white"
                                            >
                                                Promote
                                            </button>
                                        </form>
                                    @endif

                                    @if ($u->role === 'moderator')
                                        <form method="POST" action="/admin/demote/{{ $u->id }}" class="min-w-0">
                                            @csrf
                                            <button
                                    class="flex w-full min-w-28 justify-center rounded-lg border border-yellow-200 bg-yellow-50 px-4 py-2 text-sm font-semibold text-yellow-700 transition-colors hover:bg-yellow-400 hover:text-black sm:w-auto dark:border-yellow-500/20 dark:bg-yellow-500/10 dark:text-yellow-400 dark:hover:bg-yellow-400 dark:hover:text-black"
                                            >
                                                Demote
                                            </button>
                                        </form>
                                    @endif

                                    @if ($u->id !== auth()->id() && $u->role !== 'admin')
                                        <form
                                            method="POST"
                                            action="/admin/delete-user/{{ $u->id }}"
                                            class="min-w-0"
                                            onsubmit="return confirmFormSubmission(this);"
                                            data-confirm-title="Delete {{ $u->username }}?"
                                            data-confirm-message="This permanently deletes the account and its uploaded models. This action cannot be undone."
                                            data-confirm-action="Delete user"
                                        >
                                            @csrf
                                            @method ('DELETE')
                                            <button
                                                class="flex w-full min-w-28 justify-center rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 transition-colors hover:bg-red-600 hover:text-white sm:w-auto dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-400 dark:hover:bg-red-600 dark:hover:text-white"
                                            >
                                                Delete user
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 dark:text-gray-400">No users found.</p>
                        @endforelse
                    </div>
                </div>
            @endcan
        </div>
    </div>
    <script>
        window.panelStats = @json ($stats);

        document.addEventListener('DOMContentLoaded', function () {
            // 1. Ambil tab terakhir dari localStorage atau URL hash, default ke 'dashboard'
            const lastTab = localStorage.getItem('activeAdminTab') || window.location.hash.substring(1) || 'dashboard';

            // 2. Cari tombol yang sesuai dengan tab tersebut
            const targetBtn = document.querySelector(`button[onclick*="'${lastTab}'"]`);

            // 3. Jalankan fungsi changeTab untuk mengaktifkan tab tersebut
            if (targetBtn) {
                changeTab(lastTab, targetBtn);
            }

            setInterval(checkAdminUpdates, 15000);
        });

        async function checkAdminUpdates() {
            try {
                const response = await fetch('/admin/status', {
                    headers: { Accept: 'application/json' },
                });
                if (!response.ok) return;

                const latest = await response.json();
                const activeTab = localStorage.getItem('activeAdminTab') || 'dashboard';
                const reportChanged = latest.reports !== window.panelStats.reports;
                const verifyChanged = latest.verify !== window.panelStats.verify;

                window.panelStats = latest;

                if ((activeTab === 'reports' && reportChanged) || (activeTab === 'verify' && verifyChanged)) {
                    window.location.reload();
                }
            } catch (error) {
                console.warn('Admin update check failed', error);
            }
        }

        function changeTab(tabId, btnElement) {
            // Keep the selected tab across reloads.
            localStorage.setItem('activeAdminTab', tabId);

            // Update the URL hash so the tab can be linked directly.
            window.location.hash = tabId;

            // Show the selected content.
            document.querySelectorAll('.tab-content').forEach((el) => {
                el.classList.remove('active');
            });
            const targetContent = document.getElementById(tabId);
            if (targetContent) {
                targetContent.classList.add('active');
            }

            // Reset all tab buttons to the inactive style.
            document.querySelectorAll('.tab-btn').forEach((btn) => {
                btn.classList.remove('text-green-700', 'bg-green-100', 'dark:text-neon', 'dark:bg-neon/10');
                btn.classList.add(
                    'text-gray-600',
                    'dark:text-gray-400',
                    'hover:text-green-600',
                    'hover:bg-green-50',
                    'dark:hover:text-neon',
                    'dark:hover:bg-neon/5',
                );
            });

            // Set the clicked tab button to the active style.
            btnElement.classList.remove(
                'text-gray-600',
                'dark:text-gray-400',
                'hover:text-green-600',
                'hover:bg-green-50',
                'dark:hover:text-neon',
                'dark:hover:bg-neon/5',
            );
            btnElement.classList.add('text-green-700', 'bg-green-100', 'dark:text-neon', 'dark:bg-neon/10');
        }

        function filterModels() {
            const input = document.getElementById('searchModels').value.toLowerCase();
            const items = document.querySelectorAll('.model-item');

            items.forEach((item) => {
                const title = item.querySelector('.model-title').innerText.toLowerCase();
                const author = item.querySelector('.model-author').innerText.toLowerCase();

                if (title.includes(input) || author.includes(input)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function filterUsers() {
            const input = document.getElementById('searchUsers').value.toLowerCase();
            const items = document.querySelectorAll('.user-item');

            items.forEach((item) => {
                const name = item.querySelector('.user-name').innerText.toLowerCase();
                const role = item.querySelector('.user-role').innerText.toLowerCase();
                const tier = item.querySelector('.user-tier').innerText.toLowerCase();

                if (name.includes(input) || role.includes(input) || tier.includes(input)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
    <!-- JAVASCRIPT ENGINE UNTUK FILTER REPORTS-->
    <script>
        function filterReports(status) {
            // 1. Atur Style Active Tab Button
            const buttons = document.querySelectorAll('.report-tab-btn');
            buttons.forEach((btn) => {
                btn.classList.remove('bg-white', 'text-gray-900', 'shadow-sm', 'dark:bg-gray-700', 'dark:text-white');
                btn.classList.add('text-gray-500', 'dark:text-gray-400', 'dark:hover:text-gray-200');
            });

            const activeBtn = document.getElementById(`tab-${status}`);
            if (activeBtn) {
                activeBtn.classList.remove('text-gray-500', 'dark:text-gray-400', 'dark:hover:text-gray-200');
                activeBtn.classList.add(
                    'bg-white',
                    'text-gray-900',
                    'shadow-sm',
                    'dark:bg-gray-700',
                    'dark:text-white',
                );
            }

            // 2. Sembunyikan/Tampilkan Card Sesuai Group
            const cards = document.querySelectorAll('.report-card');
            let visibleCount = 0;

            cards.forEach((card) => {
                if (card.getAttribute('data-report-group') === status) {
                    card.classList.remove('hidden');
                    visibleCount++;
                } else {
                    card.classList.add('hidden');
                }
            });

            // 3. Handle Tampilan Kosong (Empty State Filter)
            const filterEmptyState = document.getElementById('report-filter-empty');
            if (filterEmptyState) {
                if (visibleCount === 0 && cards.length > 0) {
                    filterEmptyState.classList.remove('hidden');
                } else {
                    filterEmptyState.classList.add('hidden');
                }
            }
        }

        // Fungsi Otomatis Hitung Badge & Set Default View saat halaman ke-load
        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.report-card');
            let ongoingCount = 0;
            let resolvedCount = 0;

            cards.forEach((card) => {
                if (card.getAttribute('data-report-group') === 'ongoing') ongoingCount++;
                if (card.getAttribute('data-report-group') === 'resolved') resolvedCount++;
            });

            // Tulis total data ke bagde tab
            if (document.getElementById('count-ongoing'))
                document.getElementById('count-ongoing').innerText = ongoingCount;
            if (document.getElementById('count-resolved'))
                document.getElementById('count-resolved').innerText = resolvedCount;

            // Jalankan default view ke tab ongoing pertama kali buka
            filterReports('ongoing');
        });
    </script>
@endsection
