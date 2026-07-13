@php
    $flashToasts = collect(['success', 'error', 'warning', 'info'])
        ->filter(fn ($type) => session()->has($type))
        ->map(fn ($type) => ['type' => $type, 'message' => session($type)])
        ->values();
@endphp

<div x-data="toastNotifications(@js($flashToasts))" class="pointer-events-none fixed right-4 top-4 z-[70] flex w-[min(24rem,calc(100vw-2rem))] flex-col gap-3" aria-live="polite" aria-atomic="true">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="translate-y-2 opacity-0" @mouseenter="pause(toast)" @mouseleave="resume(toast)" class="pointer-events-auto flex items-start gap-3 rounded-[14px] border bg-white/95 backdrop-blur-md p-4 shadow-xl" :class="styles[toast.type] || styles.info" role="status">
            <span class="mt-0.5 h-2.5 w-2.5 shrink-0 rounded-full animate-pulse" :class="dots[toast.type] || dots.info"></span>
            <p class="min-w-0 flex-1 text-sm font-semibold" x-text="toast.message"></p>
            <button type="button" @click="dismiss(toast)" class="-m-1 rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-800" aria-label="Tutup notifikasi">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m6 6 12 12M18 6 6 18" /></svg>
            </button>
        </div>
    </template>

</div>

<script>
function toastNotifications(initialToasts) {
    return {
        toasts: [],
        styles: { success: 'border-emerald-200 text-emerald-900', error: 'border-red-200 text-red-900', warning: 'border-amber-200 text-amber-900', info: 'border-blue-200 text-blue-900' },
        dots: { success: 'bg-emerald-500', error: 'bg-red-500', warning: 'bg-amber-500', info: 'bg-blue-500' },
        init() {
            initialToasts.forEach(toast => this.add(toast));
            window.addEventListener('notify', event => this.add(Array.isArray(event.detail) ? event.detail[0] : event.detail));
        },
        add(data) {
            if (!data?.message) return;
            const toast = { id: Date.now() + Math.random(), message: data.message, type: data.type || 'info', visible: true, remaining: 4000, startedAt: Date.now(), timer: null };
            this.toasts.push(toast); this.resume(toast);
        },
        dismiss(toast) { clearTimeout(toast.timer); toast.visible = false; setTimeout(() => this.toasts = this.toasts.filter(item => item.id !== toast.id), 180); },
        pause(toast) { clearTimeout(toast.timer); toast.remaining -= Date.now() - toast.startedAt; },
        resume(toast) { toast.startedAt = Date.now(); clearTimeout(toast.timer); toast.timer = setTimeout(() => this.dismiss(toast), Math.max(0, toast.remaining)); },
    };
}
</script>
