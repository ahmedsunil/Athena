<div id="toast-stack" class="fixed bottom-4 right-4 z-[200] flex flex-col gap-2 pointer-events-none"></div>

<template id="toast-template">
    <div class="pointer-events-auto flex min-w-[200px] items-center gap-2.5 rounded-lg px-4 py-2.5 admin-button-label text-white shadow-lg ring-1 ring-white/10 transition duration-200">
        <svg data-toast-success class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
        </svg>
        <svg data-toast-error class="hidden h-4 w-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
        <span data-toast-message class="flex-1"></span>
        <button type="button" data-toast-dismiss class="ml-auto opacity-70 hover:opacity-100">
            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var stack = document.getElementById('toast-stack');
        var template = document.getElementById('toast-template');

        if (! stack || ! template || stack.dataset.ready === '1') {
            return;
        }

        stack.dataset.ready = '1';

        function dismiss(toast) {
            toast.classList.add('opacity-0', 'translate-x-4');
            setTimeout(function () {
                toast.remove();
            }, 180);
        }

        window.addEventListener('toast', function (event) {
            var detail = event.detail || {};
            var toast = template.content.firstElementChild.cloneNode(true);
            var isError = detail.type === 'error';

            toast.classList.add(isError ? 'bg-red-600' : 'bg-zinc-900', 'opacity-0', 'translate-x-4');
            toast.querySelector('[data-toast-message]').textContent = detail.message || '';
            toast.querySelector('[data-toast-success]').classList.toggle('hidden', isError);
            toast.querySelector('[data-toast-error]').classList.toggle('hidden', ! isError);
            toast.querySelector('[data-toast-dismiss]').addEventListener('click', function () {
                dismiss(toast);
            });

            stack.appendChild(toast);
            requestAnimationFrame(function () {
                toast.classList.remove('opacity-0', 'translate-x-4');
            });
            setTimeout(function () {
                dismiss(toast);
            }, 3500);
        });
    });
</script>
