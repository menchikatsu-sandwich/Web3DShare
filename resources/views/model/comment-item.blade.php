<div class="flex gap-3 bg-gray-50/30 dark:bg-darkPanel/20 p-3.5 rounded-xl border border-gray-100/50 dark:border-gray-800/40 items-start">
    <!-- Profile Picture & Garis Jalur Balasan Terpadu -->
    <div class="flex flex-col items-center flex-shrink-0">
        <img src="{{ $comment->user->profileImageUrl() ?? 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->username) . '&background=e5e7eb&color=1f2937' }}" 
             class="w-7 h-7 rounded-full object-cover">
    </div>

    <!-- Konten Nama, Teks, & Aksi Komentar -->
    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2 mb-1">
            <span class="text-xs font-bold text-gray-800 dark:text-gray-200 truncate">
                {{ $comment->user->nickname ?? $comment->user->username }}
            </span>
            <span class="text-[10px] text-gray-400 font-medium flex-shrink-0">
                {{ $comment->created_at->diffForHumans() }}
            </span>
        </div>
        
        <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed whitespace-pre-line break-words">
            {{ $comment->body }}
        </p>

        <!-- Aksi di Bawah Komentar -->
        <div class="flex items-center gap-4 mt-2.5 flex-wrap">
            @auth
            <button type="button" 
                    onclick="toggleReplyForm('{{ $comment->id }}')"
                    class="text-[11px] font-bold text-gray-400 hover:text-green-500 dark:hover:text-neon transition-colors flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" /></svg>
                Reply
            </button>
            @endauth

            @if(($allComments->where('parent_id', $comment->id)->count() > 0))
            <button type="button" 
                    onclick="toggleRepliesDisplay('{{ $comment->id }}')" 
                    id="toggle-btn-{{ $comment->id }}"
                    class="text-[11px] font-bold text-green-600 dark:text-neon hover:underline flex items-center gap-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3" style="transition: transform 0.2s;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                <span>Show Replies ({{ $allComments->where('parent_id', $comment->id)->count() }})</span>
            </button>
            @endif

            @auth
                @if(auth()->id() === $comment->user_id || in_array(auth()->user()->role, ['admin', 'moderator']))
                <form action="/comments/{{ $comment->id }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus komentar ini? Semua balasan di bawahnya juga akan ikut terhapus secara permanen.')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="text-[11px] font-bold text-red-400 hover:text-red-600 dark:text-red-500/80 dark:hover:text-red-400 transition-colors flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.34 6.6m-4.77 0L9 9m11.11-8.8a1 1 0 0 0-1-1H5.82a1 1 0 0 0-1 1M4.77 5h14.46M15 11v6.5m-6-6.5V17.5" /></svg>
                        Delete
                    </button>
                </form>
                @endif
            @endauth
        </div>

        <!-- Form Reply (Disisipkan pas di dalam hirarki agar posisinya presisi & tidak renggang) -->
        @auth
        <div id="reply-form-{{ $comment->id }}" class="hidden mt-3 pt-1">
            <form action="/models/{{ $modelId }}/comment" method="POST">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <div class="w-full bg-white dark:bg-black/40 border border-gray-200 dark:border-gray-800 rounded-xl focus-within:border-green-500 dark:focus-within:border-neon transition-all p-1.5 shadow-inner">
                    <textarea name="body" rows="2" required placeholder="Reply to {{ $comment->user->nickname ?? $comment->user->username }}..." 
                        class="w-full bg-transparent border-0 resize-none outline-none focus:ring-0 px-2 py-1 text-xs text-gray-800 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-500"></textarea>
                    <div class="flex justify-end gap-1.5 pt-1">
                        <button type="button" onclick="toggleReplyForm('{{ $comment->id }}')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 font-semibold text-[10px] px-2.5 py-1 rounded-md transition-all">
                            Cancel
                        </button>
                        <button type="submit" class="bg-green-500 dark:bg-neon hover:bg-green-600 text-white dark:text-black font-bold text-[10px] px-3 py-1 rounded-md shadow-sm transition-all">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
        @endauth
    </div>
</div>

<!-- Wadah penampung balasan berikutnya (Indentation Nested) -->
<div id="replies-container-{{ $comment->id }}" class="hidden ml-6 pl-3 border-l border-gray-200 dark:border-gray-800 mt-2 space-y-2 transition-all">
    @foreach($allComments->where('parent_id', $comment->id) as $subComment)
        @include('model.comment-item', ['comment' => $subComment, 'allComments' => $allComments, 'modelId' => $modelId])
    @endforeach
</div>