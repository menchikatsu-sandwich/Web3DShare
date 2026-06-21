<div
    data-comment-node="{{ $comment->id }}"
    data-parent-id="{{ $comment->parent_id }}"
    class="space-y-2 {{ $comment->parent_id ? '' : 'border-b border-gray-100 dark:border-gray-800/40 pb-4 last:border-0' }}"
>
    <div
        class="flex items-start gap-3 rounded-xl border border-gray-100/50 bg-gray-50/30 p-3.5 dark:border-gray-800/40 dark:bg-darkPanel/20"
    >
        <!-- Profile picture and reply thread guide -->
        <div class="flex flex-shrink-0 flex-col items-center">
            <img
                src="{{ $comment->user->profileImageUrl() ?? 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->username) . '&background=e5e7eb&color=1f2937' }}"
                class="h-7 w-7 rounded-full object-cover"
            />
        </div>

        <!-- Comment content and actions -->
        <div class="min-w-0 flex-1">
            <div class="mb-1 flex items-center gap-2">
                <span class="truncate text-xs font-bold text-gray-800 dark:text-gray-200">
                    {{ $comment->user->nickname ?? $comment->user->username }}
                </span>
                <span class="flex-shrink-0 text-[10px] font-medium text-gray-400">
                    {{ $comment->created_at->diffForHumans() }}
                </span>
            </div>

            <p class="text-xs leading-relaxed break-words whitespace-pre-line text-gray-600 dark:text-gray-300">
                {{ $comment->body }}
            </p>

            <!-- Comment actions -->
            <div class="mt-2.5 flex flex-wrap items-center gap-4" data-comment-actions="{{ $comment->id }}">
                @auth
                    <button
                        type="button"
                        onclick="toggleReplyForm('{{ $comment->id }}')"
                        class="flex items-center gap-1 text-[11px] font-bold text-gray-400 transition-colors hover:text-green-500 dark:hover:text-neon"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-3 w-3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" /></svg>
                        Reply
                    </button>
                @endauth

                @if (($allComments->where('parent_id', $comment->id)->count() > 0))
                    <button
                        type="button"
                        onclick="toggleRepliesDisplay('{{ $comment->id }}')"
                        id="toggle-btn-{{ $comment->id }}"
                        data-reply-toggle
                        data-reply-count="{{ $allComments->where('parent_id', $comment->id)->count() }}"
                        class="flex items-center gap-1 text-[11px] font-bold text-green-600 hover:underline dark:text-neon"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-3 w-3" style="transition: transform 0.2s;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                        <span
                            ><span data-reply-label>Show Replies</span> (<span
                                data-reply-count-text
                                >{{ $allComments->where('parent_id', $comment->id)->count() }}</span
                            >)</span
                        >
                    </button>
                @endif

                @auth
                    @if (auth()->id() === $comment->user_id || in_array(auth()->user()->role, ['admin', 'moderator']))
                        <form
                            action="/comments/{{ $comment->id }}"
                            method="POST"
                            onsubmit="return confirmFormSubmission(this);"
                            data-confirm-title="Delete this comment?"
                            data-confirm-message="All replies under this comment will also be permanently deleted. This action cannot be undone."
                            data-confirm-action="Delete comment"
                            class="inline"
                            data-ajax-comment-delete
                        >
                            @csrf
                            @method ('DELETE')
                            <button
                                type="submit"
                                class="flex items-center gap-1 text-[11px] font-bold text-red-400 transition-colors hover:text-red-600 dark:text-red-500/80 dark:hover:text-red-400"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-3 w-3"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.34 6.6m-4.77 0L9 9m11.11-8.8a1 1 0 0 0-1-1H5.82a1 1 0 0 0-1 1M4.77 5h14.46M15 11v6.5m-6-6.5V17.5" /></svg>
                                Delete
                            </button>
                        </form>
                    @endif
                @endauth
            </div>

            <!-- Reply form kept inside the comment hierarchy for accurate spacing -->
            @auth
                <div id="reply-form-{{ $comment->id }}" class="mt-3 hidden pt-1">
                    <form action="/models/{{ $modelId }}/comment" method="POST" data-ajax-comment>
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}" />
                        <div
                            class="w-full rounded-xl border border-gray-200 bg-white p-1.5 shadow-inner transition-all focus-within:border-green-500 dark:border-gray-800 dark:bg-black/40 dark:focus-within:border-neon"
                        >
                            <textarea
                                name="body"
                                rows="2"
                                required
                                placeholder="Reply to {{ $comment->user->nickname ?? $comment->user->username }}..."
                                class="w-full resize-none border-0 bg-transparent px-2 py-1 text-xs text-gray-800 placeholder-gray-400 outline-none focus:ring-0 dark:text-gray-200 dark:placeholder-gray-500"
                            ></textarea>
                            <div class="flex justify-end gap-1.5 pt-1">
                                <button
                                    type="button"
                                    onclick="toggleReplyForm('{{ $comment->id }}')"
                                    class="rounded-md px-2.5 py-1 text-[10px] font-semibold text-gray-400 transition-all hover:text-gray-600 dark:hover:text-gray-300"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="rounded-md bg-green-500 px-3 py-1 text-[10px] font-bold text-white shadow-sm transition-all hover:bg-green-600 dark:bg-neon dark:text-black"
                                >
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endauth
        </div>
    </div>

    <!-- Nested replies container -->
    <div
        id="replies-container-{{ $comment->id }}"
        class="mt-2 ml-6 hidden space-y-2 border-l border-gray-200 pl-3 transition-all dark:border-gray-800"
    >
        @foreach ($allComments->where('parent_id', $comment->id) as $subComment)
            @include ('model.comment-item', ['comment' => $subComment, 'allComments' => $allComments, 'modelId' => $modelId])
        @endforeach
    </div>
</div>
