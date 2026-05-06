<div class="fixed inset-0 bg-black/80 flex items-center justify-center z-[999]" onclick="closeAll(event)">

<div class="bg-gray-900 w-[90%] max-w-6xl max-h-[90%] overflow-auto relative" onclick="event.stopPropagation()">

<button onclick="closeTop()" class="absolute top-2 right-2 text-white">X</button>

<div class="modal-content">
{!! $content !!}
</div>

</div>

</div>