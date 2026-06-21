<div
    id="confirmation-dialog"
    class="fixed inset-0 z-[500] hidden items-center justify-center bg-black/70 p-4 backdrop-blur-sm"
    role="dialog"
    aria-modal="true"
    aria-labelledby="confirmation-dialog-title"
    aria-describedby="confirmation-dialog-message"
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
        const dialog = document.getElementById('confirmation-dialog');
        if (!dialog || window.confirmFormSubmission) return;

        const title = dialog.querySelector('#confirmation-dialog-title');
        const message = dialog.querySelector('#confirmation-dialog-message');
        const cancelButton = dialog.querySelector('[data-confirm-cancel]');
        const approveButton = dialog.querySelector('[data-confirm-approve]');
        let pendingForm = null;
        let previousFocus = null;

        const closeDialog = () => {
            dialog.classList.add('hidden');
            dialog.classList.remove('flex');
            pendingForm = null;
            previousFocus?.focus();
        };

        window.confirmFormSubmission = (form) => {
            if (form.dataset.confirmed === 'true') {
                delete form.dataset.confirmed;
                return true;
            }

            pendingForm = form;
            previousFocus = document.activeElement;
            title.textContent = form.dataset.confirmTitle || 'Are you sure?';
            message.textContent = form.dataset.confirmMessage || 'This action cannot be undone.';
            approveButton.textContent = form.dataset.confirmAction || 'Confirm';
            dialog.classList.remove('hidden');
            dialog.classList.add('flex');
            approveButton.focus();

            return false;
        };

        cancelButton.addEventListener('click', closeDialog);
        approveButton.addEventListener('click', () => {
            if (!pendingForm) return;

            const form = pendingForm;
            closeDialog();
            form.dataset.confirmed = 'true';
            form.requestSubmit();
        });

        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) closeDialog();
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !dialog.classList.contains('hidden')) closeDialog();
        });
    })();
</script>
