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
    </style>
    <div class="flex flex-col items-start gap-8 lg:flex-row">
        <div
            class="sticky top-24 w-full flex-shrink-0 rounded-2xl border border-gray-200 bg-white p-6 shadow-md transition-colors duration-300 lg:w-72 dark:border-neon/20 dark:bg-darkPanel dark:shadow-[0_0_30px_rgba(0,255,136,0.05)]"
        >
            <h2 class="mb-4 ml-2 text-xs font-bold tracking-widest text-gray-500 uppercase">Control Panel</h2>
            <ul class="space-y-2 text-sm font-medium">
                <li>
                    <button
                        onclick="changeTab('dashboard', this)"
                        class="tab-btn flex w-full items-center gap-3 rounded-xl px-4 py-3 text-gray-600 transition-all hover:bg-green-50 hover:text-green-600 dark:text-gray-400 dark:hover:bg-neon/5 dark:hover:text-neon"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                        </svg>
                        >Dashboard
                    </button>
                </li>
                <li>
                    <button
                        onclick="changeTab('models', this)"
                        class="tab-btn flex w-full items-center gap-3 rounded-xl px-4 py-3 text-gray-600 transition-all hover:bg-green-50 hover:text-green-600 dark:text-gray-400 dark:hover:bg-neon/5 dark:hover:text-neon"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" /></svg>
                        >Manage Models
                    </button>
                </li>
                <li>
                    <button
                        onclick="changeTab('reports', this)"
                        class="tab-btn flex w-full items-center gap-3 rounded-xl px-4 py-3 text-gray-600 transition-all hover:bg-green-50 hover:text-green-600 dark:text-gray-400 dark:hover:bg-neon/5 dark:hover:text-neon"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        >Reports
                    </button>
                </li>
                <li>
                    <button
                        onclick="changeTab('verify', this)"
                        class="tab-btn flex w-full items-center gap-3 rounded-xl px-4 py-3 text-gray-600 transition-all hover:bg-green-50 hover:text-green-600 dark:text-gray-400 dark:hover:bg-neon/5 dark:hover:text-neon"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" /></svg>
                        >Verifications
                    </button>
                </li>
                <li>
                    <button
                        onclick="changeTab('category', this)"
                        class="tab-btn flex w-full items-center gap-3 rounded-xl px-4 py-3 text-gray-600 transition-all hover:bg-green-50 hover:text-green-600 dark:text-gray-400 dark:hover:bg-neon/5 dark:hover:text-neon"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                        </svg>
                        >Categories
                    </button>
                </li>
                @can ('admin')
                    <li>
                        <button
                            onclick="changeTab('users', this)"
                            class="tab-btn flex w-full items-center gap-3 rounded-xl px-4 py-3 text-gray-600 transition-all hover:bg-green-50 hover:text-green-600 dark:text-gray-400 dark:hover:bg-neon/5 dark:hover:text-neon"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                            >Users Control
                        </button>
                    </li>
                @endcan
            </ul>
        </div>

        <div
            class="min-h-[600px] w-full flex-1 rounded-2xl border border-gray-200 bg-white p-6 shadow-md transition-colors duration-300 lg:p-8 dark:border-neon/10 dark:bg-darkPanel dark:shadow-none"
        >
            <div id="dashboard" class="tab-content">
                <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">
                    Overview <span class="text-green-600 dark:text-neon">Statistics</span>
                </h2>
                <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                    @foreach ($stats as $k => $v)
                        <div
                            class="flex flex-col items-center justify-center rounded-xl border border-gray-200 bg-gray-50 p-6 text-center shadow-sm transition-colors hover:border-green-400 dark:border-gray-800 dark:bg-darkBg dark:hover:border-neon/50"
                        >
                            <p class="mb-2 text-sm tracking-wider text-gray-500 uppercase dark:text-gray-400">{{ $k }}</p>
                            <h2 class="text-4xl font-bold text-green-600 dark:text-neon">{{ $v }}</h2>
                        </div>
                    @endforeach
                </div>
            </div>

            <div id="models" class="tab-content">
                <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">
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

                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3" id="modelsGrid">
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
                                onsubmit="return confirm('Are you sure you want to permanently delete this model?');"
                            >
                                @csrf
                                @method ('DELETE')
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
                <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">
                    User <span class="text-green-600 dark:text-neon">Reports</span>
                </h2>
                <div class="space-y-3">
                    @forelse ($reports as $r)
                        <div
                            class="rounded-xl border border-gray-200 bg-gray-50 p-5 shadow-sm dark:border-gray-800 dark:bg-darkBg"
                        >
                            <div class="flex flex-col justify-between gap-5 lg:flex-row lg:items-start">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <p class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                            {{ $r->model3d->title ?? 'Deleted model' }}
                                        </p>
                                        <span
                                            class="text-[10px] uppercase tracking-wider px-2 py-0.5 rounded-full border
                                    {{ $r->report_status === 'pending' ? 'bg-yellow-100 dark:bg-yellow-500/10 text-yellow-700 dark:text-yellow-400 border-yellow-300 dark:border-yellow-500/20' : ($r->report_status === 'reviewed' ? 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border-blue-300 dark:border-blue-500/20' : 'bg-green-100 dark:bg-green-500/10 text-green-700 dark:text-neon border-green-300 dark:border-green-500/20') }}"
                                        >
                                            {{ $r->report_status }}
                                        </span>
                                    </div>
                                    <p class="mt-2 flex items-center gap-2 text-sm text-red-600 dark:text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                        Reason: {{ ucwords(str_replace('_', ' ', $r->reason)) }}
                                    </p>
                                    <div
                                        class="mt-3 grid gap-2 text-xs text-gray-500 sm:grid-cols-2 dark:text-gray-400"
                                    >
                                        <p>Reporter: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $r->reporter->username ?? 'Deleted user' }}</span></p>
                                        <p>Model owner: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $r->model3d->user->username ?? 'Deleted user' }}</span></p>
                                        <p>Created: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $r->created_at->format('M j, Y H:i') }}</span></p>
                                        <p>Reviewer: <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $r->reviewer->username ?? '-' }}</span></p>
                                    </div>
                                    @if ($r->description)
                                        <p class="mt-3 rounded-lg border border-gray-200 bg-white p-3 text-sm text-gray-700 dark:border-gray-800 dark:bg-black/30 dark:text-gray-300">{{ $r->description }}</p>
                                    @endif
                                </div>

                                <div class="flex flex-shrink-0 flex-col gap-2 sm:flex-row lg:w-44 lg:flex-col">
                                    @if ($r->model3d)
                                        <a
                                            href="/models/{{ $r->model_id }}"
                                            class="rounded-lg border border-gray-200 bg-gray-100 px-4 py-2 text-center text-sm font-medium text-gray-700 transition-colors hover:border-green-400 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:border-neon/40"
                                            >Open Model</a
                                        >
                                    @endif
                                    @if ($r->report_status === 'pending')
                                        <form method="POST" action="/admin/reports/{{ $r->id }}/reviewed">
                                            @csrf
                                            <button
                                                class="w-full rounded-lg border border-blue-200 bg-blue-100 px-4 py-2 text-sm font-medium text-blue-700 transition-colors hover:bg-blue-600 hover:text-white dark:border-blue-500/20 dark:bg-blue-500/10 dark:text-blue-400"
                                            >
                                                Mark Reviewed
                                            </button>
                                        </form>
                                    @endif
                                    @if ($r->report_status !== 'resolved')
                                        <form method="POST" action="/admin/reports/{{ $r->id }}/resolve">
                                            @csrf
                                            <button
                                                class="w-full rounded-lg border border-green-300 bg-green-100 px-4 py-2 text-sm font-semibold text-green-700 transition-colors hover:bg-green-600 hover:text-white dark:border-green-500/20 dark:bg-green-500/10 dark:text-neon dark:hover:text-black"
                                            >
                                                Resolve
                                            </button>
                                        </form>
                                    @endif
                                    @if ($r->model3d)
                                        <form
                                            method="POST"
                                            action="/admin/delete-model/{{ $r->model_id }}"
                                            onsubmit="
                                                return confirm(
                                                    'Take down this model? This will delete the model and its reports.',
                                                );
                                            "
                                        >
                                            @csrf
                                            @method ('DELETE')
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
                        <div class="py-10 text-center text-gray-500">No reports yet.</div>
                    @endforelse
                </div>
            </div>

            <div id="verify" class="tab-content">
                <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">
                    Verification <span class="text-green-600 dark:text-neon">Requests</span>
                </h2>
                <div class="space-y-3">
                    @forelse ($requests as $req)
                        <div
                            class="flex flex-col justify-between rounded-xl border border-gray-200 bg-gray-50 p-5 shadow-sm md:flex-row md:items-center dark:border-gray-800 dark:bg-darkBg"
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
                            <div class="flex gap-3">
                                <form method="POST" action="/verify/{{ $req->id }}/approve">
                                    @csrf
                                    <button
                                        class="rounded-lg border border-green-300 bg-green-100 px-5 py-2 text-sm font-semibold text-green-700 transition-colors hover:bg-green-600 hover:text-white dark:border-green-500/20 dark:bg-green-500/10 dark:text-neon dark:hover:text-black"
                                    >
                                        Approve
                                    </button>
                                </form>
                                <form method="POST" action="/verify/{{ $req->id }}/reject">
                                    @csrf
                                    <button
                                        class="rounded-lg border border-red-200 bg-red-100 px-5 py-2 text-sm font-medium text-red-600 transition-colors hover:bg-red-600 hover:text-white dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-500"
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
                <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">
                    Manage <span class="text-green-600 dark:text-neon">Categories</span>
                </h2>
                <form
                    method="POST"
                    action="/admin/category"
                    class="mb-8 flex gap-3 rounded-xl border border-gray-200 bg-gray-50 p-4 shadow-sm dark:border-gray-800 dark:bg-darkBg"
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
                            class="flex items-center justify-between rounded-xl border border-gray-200 bg-gray-50 p-4 shadow-sm transition-colors hover:border-green-300 dark:border-gray-800 dark:bg-darkBg dark:hover:border-neon/20"
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
                    <h2 class="mb-6 text-2xl font-bold text-gray-900 dark:text-white">
                        User <span class="text-green-600 dark:text-neon">Management</span>
                    </h2>

                    <div class="mb-6">
                        <input
                            type="text"
                            id="searchUsers"
                            onkeyup="filterUsers()"
                            placeholder="Search username or role..."
                            class="w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-2 text-gray-900 transition-all focus:border-green-500 focus:outline-none md:w-80 dark:border-gray-800 dark:bg-darkBg dark:text-gray-200 dark:focus:border-neon"
                        />
                    </div>

                    <div class="space-y-3" id="usersList">
                        @forelse ($users as $u)
                            <div
                                class="user-item flex flex-col justify-between rounded-xl border border-gray-200 bg-gray-50 p-4 shadow-sm transition-colors hover:border-gray-300 md:flex-row md:items-center dark:border-gray-800 dark:bg-darkBg dark:hover:border-gray-700"
                            >
                                <div class="mb-4 flex items-center gap-3 md:mb-0">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 font-bold text-green-700 dark:bg-gray-800 dark:text-neon"
                                    >
                                        {{ substr($u->username, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="user-name font-semibold text-gray-800 dark:text-gray-200">{{ $u->username }}</p>
                                        <span
                                            class="user-role text-[10px] uppercase tracking-wider px-2 py-0.5 rounded-full {{ $u->role === 'admin' ? 'bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400' : ($u->role === 'moderator' ? 'bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400' : 'bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300') }}"
                                            >{{ $u->role }}</span
                                        >
                                    </div>
                                </div>
                                <div class="flex gap-2">
                                    {{-- Tombol Promote ke Mod --}}
                                    @if ($u->role === 'user')
                                        <form method="POST" action="/admin/promote/{{ $u->id }}">
                                            @csrf
                                            <button
                                                class="flex w-28 justify-center rounded-lg border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-green-100 hover:text-green-700 dark:border-transparent dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-green-500/20 dark:hover:text-neon"
                                            >
                                                Make Mod
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Demote ke User (Style disamakan, hover baru kuning) --}}
                                    @if ($u->role === 'moderator')
                                        <form method="POST" action="/admin/demote/{{ $u->id }}">
                                            @csrf
                                            <button
                                                class="flex w-28 justify-center rounded-lg border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-yellow-100 hover:text-yellow-600 dark:border-transparent dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-yellow-500/20 dark:hover:text-yellow-500"
                                            >
                                                Demote
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Ban --}}
                                    @if ($u->id !== auth()->id())
                                        <form method="POST" action="/admin/delete-user/{{ $u->id }}">
                                            @csrf
                                            @method ('DELETE')
                                            <button
                                                class="flex w-28 justify-center rounded-lg border border-gray-300 bg-gray-200 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-red-100 hover:text-red-600 dark:border-transparent dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-red-500/20 dark:hover:text-red-400"
                                            >
                                                Ban User
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

                if (name.includes(input) || role.includes(input)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }
    </script>
@endsection
