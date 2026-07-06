@extends ('layouts.app')

@section ('content')
    <div
        class="fixed inset-0 z-[100] flex items-start justify-center overflow-y-auto bg-black/70 p-2 backdrop-blur-md sm:p-4"
    >
        <!-- Parent Container diatur agar flex-col dan membatasi tinggi maksimal -->
        <div
            class="flex h-[calc(100dvh-1rem)] min-h-0 w-full max-w-4xl flex-col overflow-hidden rounded-xl border border-neon/20 bg-darkPanel text-gray-200 shadow-[0_0_40px_rgba(0,255,136,0.08)] sm:h-auto sm:max-h-[88vh] sm:rounded-2xl"
        >
            <!-- Header (Tetap Diam/Sticky di Atas) -->
            <div class="flex items-start justify-between gap-3 border-b border-gray-800 p-4 sm:gap-6 sm:p-6">
                <div class="min-w-0">
                    <h2 class="text-xl font-bold tracking-wide text-white sm:text-3xl">
                        <span class="text-neon">Reports</span>
                    </h2>
                    <p class="mt-1 text-sm text-gray-400">Track submitted reports and moderation notices for your models.</p>
                </div>

                <a
                    href="/"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full border border-gray-800 bg-darkBg text-gray-400 transition-colors hover:border-neon/40 hover:bg-neon/10 hover:text-neon"
                    aria-label="Close"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </a>
            </div>

            <!-- Body Container (Otomatis memicu Scrollbar jika konten kepenuhan) -->
            <div class="min-h-0 flex-1 scrollbar-thin scrollbar-thumb-gray-800 space-y-4 overflow-y-auto p-3 sm:p-6">
                <div
                        class="grid w-full grid-cols-2 rounded-xl border border-gray-800 bg-black/40 p-1 shadow-inner backdrop-blur-sm sm:inline-flex sm:w-auto"
                >
                    <button
                        type="button"
                        onclick="switchReportsPanel('reports')"
                        id="panel-tab-reports"
                        class="reports-panel-tab flex min-w-0 items-center justify-center gap-1.5 rounded-lg border border-transparent px-2 py-2 text-center text-[10px] font-bold tracking-wider uppercase transition-all duration-300 sm:gap-2 sm:px-4 sm:text-xs"
                    >
                        <span>Reports</span>
                        <span class="rounded px-1.5 py-0.5 text-[10px] font-black">{{ $reports->count() }}</span>
                    </button>
                    <button
                        type="button"
                        onclick="switchReportsPanel('notices')"
                        id="panel-tab-notices"
                        class="reports-panel-tab flex min-w-0 items-center justify-center gap-1.5 rounded-lg border border-transparent px-2 py-2 text-center text-[10px] font-bold tracking-wider uppercase transition-all duration-300 sm:gap-2 sm:px-4 sm:text-xs"
                    >
                        <span>Model Notices</span>
                        <span class="rounded px-1.5 py-0.5 text-[10px] font-black">{{ $ownerReports->count() }}</span>
                    </button>
                </div>

                <!-- SECTION MODEL NOTICES -->
                <section id="panel-model-notices" class="reports-panel hidden space-y-3">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-white">Model Notices</h3>
                            <p class="mt-1 text-xs text-gray-400">Moderation messages about models you uploaded.</p>
                        </div>
                        <span
                            class="rounded-full border border-blue-500/20 bg-blue-500/10 px-2.5 py-1 text-xs font-bold text-blue-300"
                        >
                            {{ $ownerReports->count() }}
                        </span>
                    </div>

                    @forelse ($ownerReports as $ownerReport)
                        @php
                                $ownerModelAvailable = $ownerReport->model3d && ! $ownerReport->model3d->trashed();
                            @endphp
                        <article class="rounded-xl border border-blue-500/20 bg-blue-500/10 p-4 sm:p-5">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h4 class="min-w-0 text-base font-bold break-words text-white">
                                            {{ $ownerReport->model3d->title ?? 'Deleted model' }}
                                        </h4>
                                        <span
                                            class="rounded-full border px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider
                                                {{ $ownerReport->owner_action === 'model_taken_down'
                                                    ? 'border-red-500/20 bg-red-500/10 text-red-300'
                                                    : 'border-blue-400/20 bg-blue-400/10 text-blue-200' }}"
                                        >
                                            {{ $ownerReport->owner_action === 'model_taken_down' ? 'Taken down' : 'Report update' }}
                                        </span>
                                    </div>
                                    <p class="mt-3 text-sm leading-relaxed whitespace-pre-wrap text-blue-50">{{ $ownerReport->owner_message }}</p>
                                    <p class="mt-3 text-[10px] font-bold tracking-widest text-blue-300/80 uppercase">
                                        Moderator: {{ $ownerReport->reviewer ? ucfirst($ownerReport->reviewer->role) : 'Staff' }}
                                        @if ($ownerReport->owner_notified_at)
                                            &middot; {{ $ownerReport->owner_notified_at->format('M j, Y H:i') }}
                                        @endif
                                    </p>
                                </div>

                                @if ($ownerModelAvailable)
                                    <a
                                        href="/models/{{ $ownerReport->model_id }}?from=my_models"
                                        class="inline-flex w-full shrink-0 items-center justify-center rounded-lg border border-blue-400/30 bg-blue-400/10 px-4 py-2 text-sm font-semibold text-blue-100 transition-colors hover:bg-blue-400/20 sm:w-auto"
                                    >
                                        Edit Model
                                    </a>
                                @else
                                    <span
                                        class="inline-flex w-full shrink-0 items-center justify-center rounded-lg border border-red-500/20 bg-red-500/10 px-4 py-2 text-sm font-semibold text-red-200 sm:w-auto"
                                    >
                                        Model unavailable
                                    </span>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div
                            class="flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-800 bg-darkBg p-10 text-center"
                        >
                            <h3 class="text-lg font-bold text-white">No model notices yet</h3>
                            <p class="mt-2 text-sm text-gray-400">Moderation notices about your uploaded models will appear here.</p>
                        </div>
                    @endforelse
                </section>

                <!-- SECTION REPORTS -->
                <section id="panel-reports" class="reports-panel space-y-4">
                    <div
                        class="mb-2 grid w-full grid-cols-2 rounded-xl border border-gray-800 bg-black/40 p-1 shadow-inner backdrop-blur-sm sm:inline-flex sm:w-auto"
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

                    @forelse ($reports as $report)
                        @php
                        $group = ($report->report_status === 'resolved') ? 'resolved' : 'ongoing';
                        
                        $modelAvailable = $report->model3d && ! $report->model3d->trashed();
                        $reviewerRole = $report->reviewer ? ucfirst($report->reviewer->role) : 'Not assigned yet';
                        $description = trim((string) $report->description);
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
                        <article
                            data-report-group="{{ $group }}"
                            class="report-card rounded-xl border border-gray-800 bg-darkBg p-4 transition-colors hover:border-neon/30 sm:p-5"
                        >
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="min-w-0 text-base font-bold break-words text-white sm:text-lg">
                                            {{ $report->model3d->title ?? 'Deleted model' }}
                                        </h3>

                                        <span
                                            class="text-[10px] uppercase tracking-wider px-2 py-0.5 rounded-full border font-medium
                                        {{ $report->report_status === 'pending' 
                                            ? 'bg-yellow-100 dark:bg-yellow-500/10 text-yellow-700 dark:text-yellow-400 border-yellow-300 dark:border-yellow-500/20' 
                                            : ($report->report_status === 'reviewed' 
                                                ? 'bg-blue-100 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border-blue-300 dark:border-blue-500/20' 
                                                : 'bg-green-100 dark:bg-green-500/10 text-green-700 dark:text-neon border-green-300 dark:border-green-500/20') 
                                        }}"
                                        >
                                            {{ $report->report_status === 'reviewed' ? 'In review' : ucfirst($report->report_status) }}
                                        </span>

                                        @if (!$report->model3d || !is_null($report->model3d->deleted_at))
                                            <span
                                                class="rounded-full border border-red-500/20 bg-red-500/10 px-2 py-0.5 text-[10px] font-medium tracking-wider text-red-500 uppercase"
                                            >
                                                Taken down
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-2 flex flex-wrap items-center gap-2 text-sm text-gray-400">
                                        <span class="mr-2">Reason:</span>
                                        <span
                                            class="inline-flex items-center rounded-md border border-gray-800 bg-black/20 px-2.5 py-1 text-xs font-semibold text-gray-200"
                                            >{{ ucwords(str_replace('_', ' ', $report->reason)) }}</span
                                        >
                                    </p>
                                </div>

                                @if ($modelAvailable)
                                    <a
                                        href="/models/{{ $report->model_id }}"
                                        class="inline-flex w-full shrink-0 items-center justify-center rounded-lg border border-gray-700 bg-gray-800 px-4 py-2 text-sm font-semibold text-gray-200 transition-colors hover:border-neon/50 hover:bg-neon/10 hover:text-neon sm:w-auto"
                                    >
                                        Open Model
                                    </a>
                                @else
                                    <span
                                        class="inline-flex w-full shrink-0 items-center justify-center rounded-lg border border-red-500/20 bg-red-500/10 px-4 py-2 text-sm font-semibold text-red-300 sm:w-auto"
                                    >
                                        Model unavailable
                                    </span>
                                @endif
                            </div>

                            <dl class="mt-4 grid gap-3 border-t border-gray-800 pt-4 text-xs sm:grid-cols-3">
                                <div class="flex flex-col gap-1">
                                    <dt class="text-[10px] font-bold tracking-widest text-gray-500 uppercase">
                                        Submitted
                                    </dt>
                                    <dd class="font-semibold text-gray-300">
                                        {{ $report->created_at?->format('M j, Y H:i') ?? 'Unknown' }}
                                    </dd>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <dt class="text-[10px] font-bold tracking-widest text-gray-500 uppercase">
                                        Reviewer
                                    </dt>
                                    <dd class="font-semibold text-gray-300">{{ $reviewerRole }}</dd>
                                </div>
                                <div class="flex flex-col gap-1">
                                    <dt class="text-[10px] font-bold tracking-widest text-gray-500 uppercase">
                                        Updated
                                    </dt>
                                    <dd class="font-semibold text-gray-300">
                                        {{ $report->updated_at?->format('M j, Y H:i') ?? 'Unknown' }}
                                    </dd>
                                </div>
                            </dl>

                            @if ($description)
                                <div class="mt-4 space-y-3">
                                    @if ($detailText !== '')
                                        <div class="rounded-lg border border-gray-800 bg-black/20 p-3">
                                            <p class="mb-1 text-[10px] font-bold tracking-widest text-gray-500 uppercase">Your details</p>
                                            <p class="text-sm leading-relaxed whitespace-pre-wrap text-gray-300">{{ $detailText }}</p>
                                        </div>
                                    @endif

                                    @if ($replyText)
                                        <div
                                            class="rounded-lg border border-green-300 bg-green-50 p-3 text-xs text-gray-700 dark:border-neon/20 dark:bg-neon/5 dark:text-gray-300"
                                        >
                                            <p class="mb-1 block text-[10px] font-bold tracking-wide text-green-600 uppercase dark:text-neon">Moderator reply</p>
                                            <p class="text-sm leading-relaxed whitespace-pre-wrap text-gray-200">{{ $replyText }}</p>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div
                                    class="mt-4 rounded-lg border border-dashed border-gray-800 p-3 text-sm text-gray-500"
                                >
                                    No additional details were submitted for this report.
                                </div>
                            @endif
                        </article>
                    @empty
                        <div
                            class="flex flex-col items-center justify-center rounded-xl border border-dashed border-gray-800 bg-darkBg p-10 text-center"
                        >
                            <h3 class="text-lg font-bold text-white">No reports yet</h3>
                            <p class="mt-2 text-sm text-gray-400">You have not submitted any reports yet. When you do, they will appear here.</p>
                        </div>
                    @endforelse

                    <div id="report-filter-empty" class="hidden py-10 text-center text-gray-500 dark:text-gray-400">
                        No data found for this filter.
                    </div>
                </section>
            </div>
        </div>
    </div>
    <!-- SCRIPTS -->
    <script>
        function switchReportsPanel(panel) {
            const panels = document.querySelectorAll('.reports-panel');
            panels.forEach((item) => item.classList.add('hidden'));

            const activePanel = document.getElementById(panel === 'notices' ? 'panel-model-notices' : 'panel-reports');
            if (activePanel) {
                activePanel.classList.remove('hidden');
            }

            const buttons = document.querySelectorAll('.reports-panel-tab');
            buttons.forEach((btn) => {
                btn.classList.remove('bg-white', 'text-gray-900', 'shadow-sm', 'dark:bg-gray-700', 'dark:text-white');
                btn.classList.add('text-gray-500', 'dark:text-gray-400', 'dark:hover:text-gray-200');
            });

            const activeTab = document.getElementById(panel === 'notices' ? 'panel-tab-notices' : 'panel-tab-reports');
            if (activeTab) {
                activeTab.classList.remove('text-gray-500', 'dark:text-gray-400', 'dark:hover:text-gray-200');
                activeTab.classList.add(
                    'bg-white',
                    'text-gray-900',
                    'shadow-sm',
                    'dark:bg-gray-700',
                    'dark:text-white',
                );
            }
        }

        function filterReports(status) {
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

            const filterEmptyState = document.getElementById('report-filter-empty');
            if (filterEmptyState) {
                if (visibleCount === 0 && cards.length > 0) {
                    filterEmptyState.classList.remove('hidden');
                } else {
                    filterEmptyState.classList.add('hidden');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const cards = document.querySelectorAll('.report-card');
            let ongoingCount = 0;
            let resolvedCount = 0;

            cards.forEach((card) => {
                if (card.getAttribute('data-report-group') === 'ongoing') ongoingCount++;
                if (card.getAttribute('data-report-group') === 'resolved') resolvedCount++;
            });

            if (document.getElementById('count-ongoing'))
                document.getElementById('count-ongoing').innerText = ongoingCount;
            if (document.getElementById('count-resolved'))
                document.getElementById('count-resolved').innerText = resolvedCount;

            switchReportsPanel('reports');
            filterReports('ongoing');
        });
    </script>
@endsection
