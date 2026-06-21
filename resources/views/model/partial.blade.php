@php
    $isManageContext = $isManageContext ?? false;
    $hasStarred = $hasStarred ?? false;
@endphp

<div
    data-model-shell="{{ $model->id }}"
    class="flex h-full min-h-0 w-full flex-col bg-white text-gray-800 lg:flex-row dark:bg-darkPanel dark:text-gray-200"
>
    <div class="flex min-h-0 flex-1 flex-col overflow-x-hidden overflow-y-auto">
        <div
            class="flex flex-shrink-0 items-center justify-between gap-3 border-b border-gray-100 bg-white px-4 py-4 sm:px-6 lg:px-8 dark:border-gray-800 dark:bg-darkPanel"
        >
            <a
                href="/"
                id="back-to-explore-btn"
                class="hidden items-center gap-2 rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-semibold text-gray-600 shadow-sm transition-colors hover:border-green-300 hover:text-green-600 dark:border-gray-800 dark:bg-darkBg dark:text-gray-400 dark:hover:border-neon/30 dark:hover:text-neon"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back to Explore
            </a>

            <div class="ml-auto flex items-center gap-2" data-tour="viewer-top-actions">
                <details class="relative">
                    <summary
                        class="flex cursor-pointer list-none items-center gap-1.5 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm font-semibold text-gray-500 shadow-sm transition-all hover:border-red-300 hover:text-red-500 dark:border-gray-800 dark:bg-darkBg dark:text-gray-400"
                    >
                        Report
                    </summary>
                    <div
                        class="absolute right-0 z-30 mt-2 w-80 max-w-[calc(100vw-2rem)] rounded-xl border border-gray-200 bg-white p-4 shadow-xl dark:border-gray-800 dark:bg-darkPanel"
                    >
                        @auth
                            <form
                                method="POST"
                                action="/models/{{ $model->id }}/report"
                                class="space-y-3"
                                data-ajax-report
                            >
                                @csrf
                                <select
                                    name="reason"
                                    required
                                    class="w-full rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-red-500 focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200"
                                >
                                    <option value="" disabled selected>Select reason...</option>
                                    <option value="stolen_content">Stolen content</option>
                                    <option value="inappropriate_content">Inappropriate content</option>
                                    <option value="spam">Spam or misleading</option>
                                    <option value="broken_file">Broken or unsafe file</option>
                                    <option value="wrong_category">Wrong category</option>
                                    <option value="other">Other</option>
                                </select>
                                <textarea
                                    name="description"
                                    rows="3"
                                    maxlength="1000"
                                    placeholder="Add context for moderators..."
                                    class="w-full resize-none rounded-lg border border-gray-300 bg-gray-50 px-3 py-2 text-sm text-gray-900 focus:border-red-500 focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200"
                                ></textarea>
                                <button
                                    type="submit"
                                    class="w-full rounded-lg bg-red-600 py-2 text-sm font-semibold text-white transition-colors hover:bg-red-700"
                                >
                                    Submit Report
                                </button>
                            </form>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">Please <a href="/login" class="font-semibold text-green-600 hover:underline dark:text-neon">login</a> to report this model.</p>
                        @endauth
                    </div>
                </details>

                <button
                    type="button"
                    onclick="copyModelUrl('{{ url('/models/' . $model->id) }}', this)"
                    class="flex items-center gap-2 rounded-lg bg-green-500 px-4 py-2 font-semibold text-white shadow-sm transition-all hover:bg-green-600 dark:bg-neon dark:text-black dark:hover:bg-[#00cc6a]"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 19c1.5-5.5 5.5-8.5 11-8.5V5l6 6-6 6v-5.5C10.5 11.5 6.8 14 4 19z" />
                    </svg>
                    <span class="share-text">Share</span>
                </button>
            </div>
        </div>

        <div
            data-tour="viewer-stage"
            class="group relative h-[46vh] min-h-[320px] w-full flex-shrink-0 bg-gray-100 lg:h-[58vh] dark:bg-black"
        >
            <model-viewer
                src="{{ $model->modelUrl() }}"
                loading="lazy"
                camera-controls
                auto-rotate
                shadow-intensity="1"
                class="h-full w-full outline-none"
            ></model-viewer>
        </div>

        <div class="flex flex-col gap-6 p-6 lg:p-8">
            <div class="flex flex-col gap-5">
                <div class="flex flex-col justify-between gap-5 lg:flex-row lg:items-start">
                    <div class="min-w-0">
                        <div
                            data-tour="viewer-views"
                            class="mb-3 inline-flex items-center gap-1.5 rounded-lg border border-gray-200 bg-gray-50 px-2.5 py-1 text-sm font-semibold text-gray-600 dark:border-gray-800 dark:bg-darkBg dark:text-gray-300"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $model->view_count }}
                        </div>
                        <h1
                            data-tour="viewer-title"
                            class="text-3xl leading-tight font-bold text-gray-900 dark:text-white"
                        >
                            {{ $model->title }}
                        </h1>
                        <div class="mt-3 flex items-center gap-3">
                            <a href="/creators/{{ $model->user->username }}" class="flex-shrink-0">
                                <img
                                    loading="lazy"
                                    src="{{ $model->user->profileImageUrl() ?? 'https://ui-avatars.com/api/?name='.urlencode($model->user->nickname ?? $model->user->username).'&background=e5e7eb&color=1f2937' }}"
                                    class="h-10 w-10 rounded-full object-cover ring-2 ring-green-600 dark:ring-neon"
                                />
                            </a>
                            <div class="min-w-0">
                                <a
                                    href="/creators/{{ $model->user->username }}"
                                    class="block truncate leading-none font-bold text-gray-900 hover:text-green-600 dark:text-white dark:hover:text-neon"
                                    >{{ $model->user->nickname ?? $model->user->username }}</a
                                >
                                <p class="mt-1.5 text-xs font-medium text-gray-500 dark:text-gray-400">{{ $authorModelCount ?? $model->user->models_count ?? 0 }} Models Published</p>
                            </div>
                            @if ($model->user->upload_tier === 'verified')
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-6 w-6 flex-shrink-0 text-green-500 dark:text-neon" title="Verified Creator">
                                    <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 lg:justify-end" data-tour="viewer-actions">
                        <form method="POST" action="/models/{{ $model->id }}/star" data-ajax-star>
                            @csrf
                            <button
                                data-star-button
                                data-starred="{{ $hasStarred ? 'true' : 'false' }}"
                                class="flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-darkBg border border-gray-200 dark:border-gray-800 {{ $hasStarred ? 'text-yellow-500 border-yellow-300 dark:text-yellow-400' : 'text-gray-700 dark:text-gray-300' }} font-semibold rounded-lg hover:text-yellow-500 hover:border-yellow-300 dark:hover:text-yellow-400 transition-all shadow-sm"
                            >
                                <svg
                                    data-star-icon
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="{{ $hasStarred ? 'currentColor' : 'none' }}"
                                    viewBox="0 0 24 24"
                                    stroke-width="2"
                                    stroke="currentColor"
                                    class="h-4 w-4"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                                </svg>
                                <span data-star-label>{{ $hasStarred ? 'Starred' : 'Star' }}</span>
                                (<span data-star-count>{{ $model->stars_count }}</span>)
                            </button>
                        </form>

                        <a
                            href="/models/{{ $model->id }}/download"
                            data-ajax-download
                            class="flex items-center gap-2 rounded-lg bg-green-500 px-4 py-2 font-semibold text-white shadow-sm transition-all hover:bg-green-600 dark:bg-neon dark:text-black dark:hover:bg-[#00cc6a]"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Download (<span data-download-count>{{ $model->download_count }}</span>)
                        </a>

                        @if ($isManageContext)
                            <div data-tour="viewer-owner-actions" class="flex flex-wrap items-center gap-3">
                                <button
                                    type="button"
                                    onclick="openViewerEditModal()"
                                    class="flex items-center gap-2 rounded-lg border border-yellow-300 bg-yellow-400 px-4 py-2 font-semibold text-black shadow-sm transition-all hover:bg-yellow-300"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 7.125 16.875 4.5" />
                                    </svg>
                                    Edit
                                </button>

                                <form
                                    method="POST"
                                    action="/models/{{ $model->id }}"
                                    onsubmit="return confirmFormSubmission(this);"
                                    data-confirm-title="Delete this model?"
                                    data-confirm-message="This permanently deletes the model, its files, comments, and related activity. This action cannot be undone."
                                    data-confirm-action="Delete model"
                                >
                                    @csrf
                                    @method ('DELETE')
                                    <button
                                        type="submit"
                                        class="flex items-center gap-2 rounded-lg border border-red-700 bg-red-600 px-4 py-2 font-semibold text-white shadow-sm transition-all hover:bg-red-700"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-4 w-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21H8.084a2.25 2.25 0 0 1-2.244-1.327L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endif

                        @can ('moderator')
                            <form
                                method="POST"
                                action="/admin/delete-model/{{ $model->id }}"
                                onsubmit="return confirmFormSubmission(this);"
                                data-confirm-title="Permanently delete this model?"
                                data-confirm-message="This removes the model and all related reports. This action cannot be undone."
                                data-confirm-action="Delete model"
                            >
                                @csrf
                                @method ('DELETE')
                                <button
                                    type="submit"
                                    class="flex items-center gap-2 rounded-xl border border-red-200 bg-red-100 px-6 py-3 font-bold text-red-600 shadow-sm transition-all duration-300 hover:border-red-600 hover:bg-red-600 hover:text-white hover:shadow-red-500/20 active:scale-95 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-500 dark:hover:border-red-600 dark:hover:bg-red-600 dark:hover:text-white"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>

                <div
                    class="space-y-4 rounded-2xl border border-gray-100 bg-gray-50 p-5 dark:border-gray-800 dark:bg-darkBg/50"
                >
                    <div>
                        <h3 class="mb-2 text-[10px] font-bold tracking-widest text-gray-400 uppercase">About</h3>
                        <p class="text-sm leading-relaxed whitespace-pre-wrap text-gray-700 dark:text-gray-300">{{ $model->description ?? 'No description available for this model.' }}</p>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        @if (isset($model->category))
                            <a
                                href="/?category={{ $model->category_id }}"
                                class="inline-flex items-center rounded-md border border-gray-200 bg-white px-2.5 py-1 text-xs font-medium text-gray-800 shadow-sm transition-colors hover:bg-green-100 hover:text-green-600 dark:border-gray-800 dark:bg-gray-900/60 dark:text-gray-200 dark:hover:bg-neon/20 dark:hover:text-neon"
                            >
                                {{ $model->category->name }}
                            </a>
                        @endif

                        @foreach ($model->tags as $tag)
                            <a
                                href="/?tag={{ $tag->slug }}"
                                class="inline-flex items-center gap-1 rounded-md border border-gray-200 bg-white px-2.5 py-1 text-xs font-medium text-gray-600 shadow-sm transition-all hover:border-green-400 hover:text-green-600 dark:border-gray-800 dark:bg-gray-900/60 dark:text-gray-400 dark:hover:border-neon/40 dark:hover:text-neon"
                            >
                                <span class="text-gray-400">#</span>{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div data-tour="viewer-comments" class="mt-8 border-t border-gray-100 pt-8 dark:border-gray-800/60">
                    <h3 class="mb-6 flex items-center gap-2 text-xl font-bold text-gray-900 dark:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5 text-green-500 dark:text-neon"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.222 3.419.169A1.751 1.751 0 0 1 10.5 18v2.25a2.25 2.25 0 0 0 3.935 1.507l2.812-2.812A1.75 1.75 0 0 0 18.5 17.75c1.002-.012 1.996-.143 2.96-.39a1.75 1.75 0 0 0 1.29-1.666V8.25a1.75 1.75 0 0 0-1.75-1.75h-3.536a4.466 4.466 0 0 1-.52-.805 4.75 4.75 0 0 0-7.38 0c-.15.244-.325.513-.52.805H3.75A1.75 1.75 0 0 0 2 8.25v3.76Z" /></svg>
                        Comments (<span data-comments-count>{{ $model->comments ? $model->comments->count() : 0 }}</span
                        >)
                    </h3>

                    @auth
                        <!-- Top-level comment form -->
                        <form action="/models/{{ $model->id }}/comment" method="POST" class="mb-8" data-ajax-comment>
                            @csrf
                            <div
                                class="w-full rounded-xl border border-gray-200 bg-gray-50 p-2 transition-all focus-within:border-green-500 dark:border-gray-800 dark:bg-black/30 dark:focus-within:border-neon"
                            >
                                <textarea
                                    name="body"
                                    rows="3"
                                    required
                                    placeholder="Write a constructive comment..."
                                    class="w-full resize-none border-0 bg-transparent px-3 py-2 text-sm text-gray-800 placeholder-gray-400 outline-none focus:ring-0 dark:text-gray-200 dark:placeholder-gray-500"
                                ></textarea>
                                <div class="flex justify-end border-t border-gray-200/60 pt-2 dark:border-gray-800/50">
                                    <button
                                        type="submit"
                                        class="rounded-lg bg-green-500 px-4 py-2 text-xs font-semibold text-white shadow-sm transition-all hover:bg-green-600 dark:bg-neon dark:text-black dark:hover:bg-[#00cc6a]"
                                    >
                                        Post Comment
                                    </button>
                                </div>
                            </div>
                        </form>
                    @else
                        <div
                            class="mb-8 rounded-xl border border-gray-200 bg-gray-50 p-4 text-center text-sm text-gray-500 dark:border-gray-800 dark:bg-black/20"
                        >
                            Please
                            <a href="/login" class="font-semibold text-green-600 hover:underline dark:text-neon"
                                >login</a
                            >
                            to participate in the discussion.
                        </div>
                    @endauth

                    <!-- Threaded comments list -->
                    <div class="max-h-[550px] space-y-4 overflow-y-auto pr-2" data-comments-list>
                        @php
                        $allComments = $model->comments ?? collect();
                    @endphp

                        @forelse ($allComments->where('parent_id', null) as $comment)
                            @include ('model.comment-item', ['comment' => $comment, 'allComments' => $allComments, 'modelId' => $model->id])
                        @empty
                            <div class="py-8 text-center text-sm text-gray-400 dark:text-gray-600" data-comments-empty>
                                No comments yet. Be the first to share your thoughts!
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @php
    $isFromPanel = Str::contains(request()->header('referer'), 'panel');
    @endphp

    @if (!$isFromPanel)
        <div
            data-tour="viewer-recommendations"
            class="relative flex w-full flex-shrink-0 flex-col border-l border-gray-200 bg-gray-50 lg:w-80 dark:border-gray-800 dark:bg-[#0c0c0e]"
        >
            <div
                class="z-10 flex items-center justify-between border-b border-gray-200 bg-gray-50 p-4 dark:border-gray-800 dark:bg-[#0c0c0e]"
            >
                <h3 class="text-xs font-bold tracking-widest text-gray-500 uppercase">More Like This</h3>

                <div class="flex items-center gap-2">
                    <button
                        id="close-modal-btn"
                        onclick="closeTop()"
                        class="rounded-lg p-1.5 text-gray-400 transition-colors hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-500/10 dark:hover:text-red-500"
                        title="Close"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="flex-1 space-y-4 overflow-y-auto p-4">
                @forelse ($recommendations as $rec)
                    <a
                        href="/models/{{ $rec->id }}"
                        onclick="handleModelClick('{{ $rec->id }}', event)"
                        class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition-all hover:border-green-400 hover:shadow-md dark:border-gray-800 dark:bg-darkPanel dark:hover:border-neon/40"
                    >
                        <div class="relative h-32 w-full overflow-hidden bg-gray-200 dark:bg-black">
                            <img
                                src="{{ $rec->thumbnailUrl() }}"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                            />
                        </div>
                        <div class="p-3 text-center">
                            <p class="truncate text-sm font-semibold text-gray-800 transition-colors group-hover:text-green-600 dark:text-gray-200 dark:group-hover:text-neon">{{ $rec->title }}</p>
                            <p class="mt-1 truncate text-[10px] font-bold tracking-wider text-gray-400 uppercase">{{ $rec->category->name ?? 'Category' }}</p>
                        </div>
                    </a>
                @empty
                    <p class="py-10 text-center text-xs text-gray-400">No similar models found.</p>
                @endforelse
            </div>
        </div>
    @endif

    @if ($isManageContext)
        <div
            id="viewer-edit-model-modal"
            class="fixed inset-0 z-[140] hidden items-center justify-center bg-black/70 p-4 backdrop-blur-md"
        >
            <div
                class="relative w-full max-w-xl rounded-2xl border border-gray-200 bg-white p-6 shadow-xl sm:p-8 dark:border-neon/20 dark:bg-darkPanel dark:shadow-[0_0_40px_rgba(0,255,136,0.1)]"
                onclick="event.stopPropagation()"
            >
                <button
                    type="button"
                    onclick="closeViewerEditModal()"
                    class="absolute top-4 right-4 text-gray-400 transition-colors hover:text-red-500"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>

                <form method="POST" action="/models/{{ $model->id }}" class="flex flex-col gap-5">
                    @csrf
                    @method ('PATCH')
                    <div>
                        <h2 class="text-3xl font-bold tracking-wide text-gray-900 dark:text-white">
                            Edit <span class="text-green-600 dark:text-neon">Model</span>
                        </h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">The GLB file cannot be replaced here. Delete and upload again if the model file changes.</p>
                    </div>

                    <input
                        type="text"
                        name="title"
                        value="{{ $model->title }}"
                        required
                        maxlength="255"
                        class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-all focus:border-neon focus:ring-1 focus:ring-neon focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200"
                    />

                    <textarea
                        name="description"
                        rows="4"
                        maxlength="5000"
                        placeholder="Description"
                        class="w-full resize-none rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-all focus:border-neon focus:ring-1 focus:ring-neon focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200"
                        >{{ $model->description }}</textarea
                    >

                    <select
                        name="category_id"
                        required
                        class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-all focus:border-neon focus:ring-1 focus:ring-neon focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200"
                    >
                        @foreach ($categories as $cat)
                            @continue (!is_object($cat) || !isset($cat->id, $cat->name))
                            <option value="{{ $cat->id }}" {{ $model->category_id == $cat->id ? 'selected' : '' }}
                                >{{ $cat->name }}
                            </option>
                        @endforeach
                    </select>

                    <input
                        type="text"
                        name="tags"
                        value="{{ $model->tags->pluck('name')->implode(', ') }}"
                        placeholder="Tags separated by comma"
                        class="w-full rounded-xl border border-gray-300 bg-gray-50 px-4 py-3 text-gray-900 transition-all focus:border-neon focus:ring-1 focus:ring-neon focus:outline-none dark:border-gray-800 dark:bg-darkBg dark:text-gray-200"
                    />

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-green-500 py-3 text-lg font-semibold text-white transition-all hover:bg-green-600 dark:bg-neon dark:text-black dark:hover:bg-[#00cc6a]"
                    >
                        Save Changes
                    </button>
                </form>
            </div>
        </div>
        <script>
            function openViewerEditModal() {
                const modal = document.getElementById('viewer-edit-model-modal');
                if (!modal) return;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeViewerEditModal() {
                const modal = document.getElementById('viewer-edit-model-modal');
                if (!modal) return;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        </script>
    @endif
</div>
