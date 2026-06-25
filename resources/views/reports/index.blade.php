@extends ('layouts.app')

@section ('content')
    <div class="fixed inset-0 z-[100] flex items-center justify-center bg-black/70 p-4 backdrop-blur-md">
        <div
            class="flex max-h-[88vh] w-full max-w-4xl flex-col overflow-hidden rounded-2xl border border-neon/20 bg-darkPanel text-gray-200 shadow-[0_0_40px_rgba(0,255,136,0.08)]"
        >
            <div class="flex items-start justify-between gap-6 border-b border-gray-800 p-6">
                <div>
                    <h2 class="text-3xl font-bold tracking-wide text-white">
                        My <span class="text-neon">Reports</span>
                    </h2>
                    <p class="mt-1 text-sm text-gray-400">Track reports you submitted and read moderation updates.</p>
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

            <div class="min-h-0 flex-1 space-y-4 overflow-y-auto p-6">
                @forelse ($reports as $report)
                    @php
                        $statusClass = match ($report->report_status) {
                            'pending' => 'border-yellow-500/30 bg-yellow-500/10 text-yellow-400',
                            'reviewed' => 'border-blue-500/30 bg-blue-500/10 text-blue-400',
                            default => 'border-neon/30 bg-neon/10 text-neon',
                        };
                        $statusLabel = $report->report_status === 'reviewed' ? 'In review' : ucfirst($report->report_status);
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
                        class="rounded-xl border border-gray-800 bg-darkBg p-5 transition-colors hover:border-neon/30"
                    >
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="truncate text-lg font-bold text-white">
                                        {{ $report->model3d->title ?? 'Deleted model' }}
                                    </h3>
                                    <span
                                        class="{{ $statusClass }} rounded-full border px-2.5 py-0.5 text-[10px] font-bold tracking-widest uppercase"
                                    >
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <p class="mt-2 flex items-center text-sm text-gray-400">
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
                                    class="inline-flex shrink-0 items-center justify-center rounded-lg border border-gray-700 bg-gray-800 px-4 py-2 text-sm font-semibold text-gray-200 transition-colors hover:border-neon/50 hover:bg-neon/10 hover:text-neon"
                                >
                                    Open Model
                                </a>
                            @else
                                <span
                                    class="inline-flex shrink-0 items-center justify-center rounded-lg border border-red-500/20 bg-red-500/10 px-4 py-2 text-sm font-semibold text-red-300"
                                >
                                    Model unavailable
                                </span>
                            @endif
                        </div>

                        <dl class="mt-4 grid gap-3 border-t border-gray-800 pt-4 text-xs sm:grid-cols-3">
                            <div class="flex flex-col gap-1">
                                <dt class="text-[10px] font-bold tracking-widest text-gray-500 uppercase">Submitted</dt>
                                <dd class="font-semibold text-gray-300">
                                    {{ $report->created_at?->format('M j, Y H:i') ?? 'Unknown' }}
                                </dd>
                            </div>
                            <div class="flex flex-col gap-1">
                                <dt class="text-[10px] font-bold tracking-widest text-gray-500 uppercase">Reviewer</dt>
                                <dd class="font-semibold text-gray-300">{{ $reviewerRole }}</dd>
                            </div>
                            <div class="flex flex-col gap-1">
                                <dt class="text-[10px] font-bold tracking-widest text-gray-500 uppercase">Updated</dt>
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
                                    <div class="rounded-lg border border-neon/20 bg-neon/5 p-3">
                                        <p class="mb-1 text-[10px] font-bold tracking-widest text-neon uppercase">Moderator reply</p>
                                        <p class="text-sm leading-relaxed whitespace-pre-wrap text-gray-200">{{ $replyText }}</p>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="mt-4 rounded-lg border border-dashed border-gray-800 p-3 text-sm text-gray-500">
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
            </div>
        </div>
    </div>
@endsection
