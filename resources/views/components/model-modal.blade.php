<div class="fixed inset-0 z-[999] flex items-center justify-center bg-black/80" onclick="closeAll(event)">
    <div class="relative max-h-[90%] w-[90%] max-w-6xl overflow-auto bg-gray-900" onclick="event.stopPropagation()">
        <button onclick="closeTop()" class="absolute top-2 right-2 text-white">X</button>

        <div class="modal-content">{!! $content !!}</div>
    </div>
</div>
