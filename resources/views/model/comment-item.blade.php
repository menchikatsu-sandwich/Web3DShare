<div
    data-comment-node="{{ $comment->id }}"
    data-parent-id="{{ $comment->parent_id }}"
    class="block w-full relative {{ $comment->parent_id ? 'mt-4 pl-6 before:absolute before:left-0 before:top-[-16px] before:w-4 before:h-[34px] before:border-l-2 before:border-b-2 before:border-gray-150 dark:before:border-gray-800/60 before:rounded-bl-xl' : 'border-b border-gray-150 dark:border-gray-800/60 pb-6 last:border-0 pt-3.5' }}"
>
    <div class="flex w-full items-start gap-4 bg-transparent">
        
        <div class="flex-shrink-0 pt-0.5">
            <img
                src="{{ $comment->user->profileImageUrl() ?? 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->username) . '&background=00ff88&color=000000' }}"
                class="rounded-full object-cover block shadow-sm {{ $comment->parent_id ? 'h-7 w-7 min-w-[28px] max-w-[28px] min-h-[28px] max-h-[28px]' : 'h-10 w-10 min-w-[40px] max-w-[40px] min-h-[40px] max-h-[40px]' }}"
            />
        </div>

        <div class="min-w-0 flex-1 flex flex-col justify-start">
            
            <div class="flex h-4 items-center gap-2 mb-1.5">
                <span class="truncate text-[13px] font-bold text-gray-900 dark:text-gray-200 leading-none">
                    {{ $comment->user->nickname ?? $comment->user->username }}
                </span>
                <span class="flex-shrink-0 text-[11px] text-gray-400 dark:text-gray-500 leading-none">
                    {{ $comment->created_at->diffForHumans() }}
                </span>
            </div>

            <p class="text-[14px] sm:text-base leading-tight text-gray-800 dark:text-gray-300 pr-2 break-words whitespace-pre-line m-0 p-0">
                {{ $comment->body }}
            </p>

            <div class="mt-1.5 mb-2 flex items-center gap-4" data-comment-actions="{{ $comment->id }}">
                @auth
                    <button
                        type="button"
                        onclick="toggleReplyForm('{{ $comment->id }}')"
                        class="flex items-center gap-1 text-xs font-bold text-gray-500 transition-colors hover:text-green-500 dark:text-gray-400 dark:hover:text-neon"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-3.5 w-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" /></svg>
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
                        class="flex items-center gap-1 text-xs font-black text-green-600 hover:text-green-700 dark:text-neon dark:hover:opacity-80"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="h-3 w-3 transition-transform duration-200"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                        <span>
                            <span data-reply-label>Show Replies</span> 
                            (<span data-reply-count-text>{{ $allComments->where('parent_id', $comment->id)->count() }}</span>)
                        </span>
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
                            class="inline-flex items-center"
                            data-ajax-comment-delete
                        >
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="flex items-center gap-1 text-xs font-bold text-red-400/80 transition-colors hover:text-red-600 dark:text-red-500/60 dark:hover:text-red-400"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-3.5 w-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.34 6.6m-4.77 0L9 9m11.11-8.8a1 1 0 0 0-1-1H5.82a1 1 0 0 0-1 1M4.77 5h14.46M15 11v6.5m-6-6.5V17.5" /></svg>
                                Delete
                            </button>
                        </form>
                    @endif
                @endauth
            </div>

            @auth
                <div id="reply-form-{{ $comment->id }}" class="mt-3 hidden w-full mb-1">
                    <form action="/models/{{ $modelId }}/comment" method="POST" data-ajax-comment class="m-0 p-0">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}" />
                        <div class="w-full border-b border-gray-300 focus-within:border-gray-900 dark:border-gray-800 dark:focus-within:border-neon transition-colors duration-200 pb-1">
                            <textarea
                                name="body"
                                rows="1"
                                required
                                placeholder="Add a public reply..."
                                class="w-full resize-none border-0 bg-transparent px-0 py-1 text-sm text-gray-800 placeholder-gray-400 outline-none focus:ring-0 dark:text-gray-200 dark:placeholder-gray-500"
                                oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"
                            ></textarea>
                            <div class="flex justify-end gap-2 mt-2">
                                <button
                                    type="button"
                                    onclick="toggleReplyForm('{{ $comment->id }}')"
                                    class="rounded-full px-3 py-1 text-xs font-semibold hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-600 dark:text-gray-400"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    class="rounded-full bg-gray-900 dark:bg-neon px-4 py-1 text-xs font-black text-white dark:text-black hover:opacity-90 transition-all shadow-md shadow-neon/10"
                                >
                                    Reply
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endauth
        </div>
    </div>

    <div
        id="replies-container-{{ $comment->id }}"
        class="mt-2 ml-5 sm:ml-6 hidden space-y-2 pl-2 transition-all relative"
    >
        @foreach ($allComments->where('parent_id', $comment->id) as $subComment)
            @include('model.comment-item', ['comment' => $subComment, 'allComments' => $allComments, 'modelId' => $modelId])
        @endforeach
    </div>
</div>