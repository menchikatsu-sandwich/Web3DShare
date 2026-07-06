<div class="fixed inset-0 z-[999] flex items-center justify-center bg-black/80 p-2 sm:p-4" onclick="closeAll(event)">
    <div
        class="relative max-h-[calc(100dvh-1rem)] w-full max-w-6xl overflow-auto rounded-xl bg-gray-900 sm:max-h-[90vh]"
        onclick="event.stopPropagation()"
    >
        <button
            type="button"
            onclick="closeTop()"
            class="absolute top-2 right-2 z-10 flex h-9 w-9 items-center justify-center rounded-lg bg-black/60 text-white transition-colors hover:bg-red-600"
            aria-label="Close"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="modal-content">{!! $content !!}</div>
    </div>
</div>
