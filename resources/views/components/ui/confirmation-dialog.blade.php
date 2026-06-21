<div
    id="confirmation-dialog"
    class="fixed inset-0 z-[999999] hidden items-center justify-center bg-black/80 p-4 backdrop-blur-md"
    role="dialog"
    aria-modal="true"
    aria-labelledby="confirmation-dialog-title"
    aria-describedby="confirmation-dialog-message"
    style="z-index: 999999 !important;"
>
    <div
        class="w-full max-w-md rounded-xl border border-gray-200 bg-white p-6 shadow-2xl dark:border-red-500/30 dark:bg-darkPanel"
    >
        <div class="flex items-start gap-4">
            <div
                class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600 dark:bg-red-500/15 dark:text-red-400"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 4.5h.008v.008H12v-.008Z" />
                </svg>
            </div>
            <div>
                <h2 id="confirmation-dialog-title" class="text-lg font-bold text-gray-900 dark:text-white">
                    Delete item?
                </h2>
                <p id="confirmation-dialog-message" class="mt-2 text-sm leading-6 text-gray-600 dark:text-gray-300"></p>
            </div>
        </div>

        <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <button
                type="button"
                data-confirm-cancel
                class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition-colors hover:bg-gray-100 dark:border-gray-700 dark:bg-darkBg dark:text-gray-200 dark:hover:bg-gray-800"
            >
                Cancel
            </button>
            <button
                type="button"
                data-confirm-approve
                class="rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-red-700 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:outline-none dark:focus:ring-offset-darkPanel"
            >
                Delete
            </button>
        </div>
    </div>
</div>

<script>
    (() => {
        window.confirmFormSubmission = (form) => {
            if (form.dataset.confirmed === 'true') {
                delete form.dataset.confirmed;
                return true;
            }

            const titleText = form.dataset.confirmTitle || 'Delete item?';
            const messageText = form.dataset.confirmMessage || 'This action cannot be undone.';
            const actionText = form.dataset.confirmAction || 'Delete';

            // Membuat overlay utama dengan style inline agar persis seperti konfigurasi Tailwind lu
            const dialogOverlay = document.createElement('div');
            dialogOverlay.style.cssText = `
                position: fixed; inset: 0; z-index: 99999999; display: flex; 
                align-items: center; justify-content: center; background: rgba(0, 0, 0, 0.7);
                backdrop-filter: blur(4px); padding: 16px;
            `;

            // Box konfirmasi dengan style yang disamakan persis dengan class Tailwind lu
            dialogOverlay.innerHTML = `
                <div style="width: 100%; max-width: 448px; background: #1f2937; border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 12px; padding: 24px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);">
                    <div style="display: flex; align-items: flex-start; gap: 16px;">
                        <div style="display: flex; height: 44px; width: 44px; flex-shrink: 0; align-items: center; justify-content: center; rounded: 8px; background: rgba(239, 68, 68, 0.15); color: #f87171; border-radius: 8px;">
                            <svg style="height: 24px; width: 24px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 4.5h.008v.008H12v-.008Z" />
                            </svg>
                        </div>
                        <div>
                            <h2 style="font-size: 18px; font-weight: bold; color: #ffffff; margin: 0;">${titleText}</h2>
                            <p style="margin-top: 8px; font-size: 14px; line-height: 24px; color: #d1d5db; margin-bottom: 0;">${messageText}</p>
                        </div>
                    </div>
                    <div style="margin-top: 24px; display: flex; flex-direction: row-reverse; gap: 12px;">
                        <button data-approve style="background: #dc2626; color: white; padding: 10px 16px; font-size: 14px; font-weight: 600; border-radius: 8px; cursor: pointer; border: none; transition: background 0.2s;">${actionText}</button>
                        <button data-cancel style="background: #1f2937; border: 1px solid #374151; color: #e5e7eb; padding: 10px 16px; font-size: 14px; font-weight: 600; border-radius: 8px; cursor: pointer; transition: background 0.2s;">Cancel</button>
                    </div>
                </div>
            `;

            document.body.appendChild(dialogOverlay);

            // Handler untuk klik tombol
            dialogOverlay.querySelector('[data-cancel]').onclick = () => dialogOverlay.remove();
            
            dialogOverlay.querySelector('[data-approve]').onclick = () => {
                dialogOverlay.remove();
                form.dataset.confirmed = 'true';
                form.requestSubmit();
            };

            // Close saat klik di luar kotak
            dialogOverlay.onclick = (e) => {
                if (e.target === dialogOverlay) dialogOverlay.remove();
            };

            return false;
        };
    })();
</script>