<div
    x-data="notifyToast()"
    x-show="show"
    x-cloak
    @notify.window="showNotify($event.detail)"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-(-4)"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-(-4)"
    class="fixed top-4 right-4 z-[60] min-w-72 max-w-sm px-4 py-3 rounded shadow-lg text-sm font-medium"
    :class="{
        'bg-green-100 text-green-800 border border-green-300': type === 'success',
        'bg-red-100 text-red-800 border border-red-300':     type === 'error',
        'bg-yellow-100 text-yellow-800 border border-yellow-300': type === 'warning',
    }"
>
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-2">
            {{-- Icon success --}}
            <template x-if="type === 'success'">
                <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 13l4 4L19 7"/>
                </svg>
            </template>
            {{-- Icon error --}}
            <template x-if="type === 'error'">
                <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </template>
            {{-- Icon warning --}}
            <template x-if="type === 'warning'">
                <svg class="w-5 h-5 text-yellow-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
            </template>
            <span x-text="message"></span>
        </div>
        {{-- Tombol tutup --}}
        <button @click="show = false" class="shrink-0 opacity-60 hover:opacity-100">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

{{-- Script notifikasi --}}
<script>
function notifyToast() {
    return {
        show: false,
        message: '',
        type: 'success',
        timer: null,

        showNotify(detail) {
            // Support format array dari Livewire dispatch
            const data = Array.isArray(detail) ? detail[0] : detail;

            this.message = data.message ?? '';
            this.type    = data.type    ?? 'success';
            this.show    = true;

            // Auto hide setelah 4 detik
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                this.show = false;
            }, 4000);
        }
    }
}
</script>

<div
    x-data="confirmModal()"
    x-show="open"
    x-cloak
    @open-confirm-modal.window="openFromLivewire($event.detail[0])"
    class="fixed inset-0 z-50 flex items-center justify-center"
>
    {{-- Overlay --}}
    <div
        class="absolute inset-0 bg-black bg-opacity-50"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="close()"
    ></div>

    {{-- Modal Box --}}
    <div
        class="relative bg-white rounded-lg shadow-xl w-full max-w-md mx-4 p-6"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        {{-- Icon --}}
        <div class="flex items-center justify-center mb-4">
            <div
                class="w-12 h-12 rounded-full flex items-center justify-center"
                :class="type === 'danger' ? 'bg-red-100' : 'bg-yellow-100'"
            >
                <template x-if="type === 'danger'">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </template>
                <template x-if="type === 'warning'">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </template>
            </div>
        </div>

        {{-- Judul --}}
        <h3 class="text-lg font-semibold text-gray-900 text-center mb-2"
            x-text="title"></h3>

        {{-- Pesan --}}
        <p class="text-sm text-gray-500 text-center mb-6"
           x-text="message"></p>

        {{-- Tombol --}}
        <div class="flex gap-3 justify-center">
            <button
                @click="close()"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-sm font-medium"
            >
                Batal
            </button>
            <button
                @click="confirm()"
                class="px-4 py-2 rounded text-sm font-medium text-white"
                :class="type === 'danger' ? 'bg-red-600 hover:bg-red-700' : 'bg-yellow-500 hover:bg-yellow-600'"
                x-text="confirmText"
            ></button>
        </div>

        {{-- Form tersembunyi untuk submit DELETE biasa (non-Livewire) --}}
        <form x-ref="confirmForm" method="POST" class="hidden">
            @csrf
            <input x-ref="methodField" type="hidden" name="_method" value="DELETE">
        </form>
    </div>
</div>

<script>
function confirmModal() {
    return {
        open: false,
        type: 'danger',
        title: '',
        message: '',
        confirmText: '',
        actionUrl: '',
        actionMethod: 'DELETE',
        formId: '',
        livewireAction: '',
        livewireParams: [],

        init() {
            window.openConfirmModal = (options) => {
                this.resetState();
                this.type           = options.type         ?? 'danger';
                this.title          = options.title        ?? 'Konfirmasi';
                this.message        = options.message      ?? 'Apakah Anda yakin?';
                this.confirmText    = options.confirmText  ?? 'Ya';
                this.actionUrl      = options.actionUrl    ?? '';
                this.actionMethod   = options.actionMethod ?? 'DELETE';
                this.formId         = options.formId       ?? '';
                this.livewireAction = options.livewireAction ?? '';
                this.livewireParams = options.livewireParams ?? [];
                this.open = true;
            };

            window.openConfirmFromEl = (el) => {
                window.openConfirmModal({
                    type:         el.dataset.type         ?? 'danger',
                    title:        el.dataset.title        ?? 'Konfirmasi',
                    message:      el.dataset.message      ?? 'Apakah Anda yakin?',
                    confirmText:  el.dataset.confirmText  ?? 'Ya',
                    actionUrl:    el.dataset.actionUrl    ?? '',
                    actionMethod: el.dataset.actionMethod ?? 'DELETE',
                    formId:       el.dataset.formId       ?? '',
                });
            };
        },

        openFromLivewire(options) {
            const open = () => {
                this.resetState();
                this.type           = options.type        ?? 'danger';
                this.title          = options.title       ?? 'Konfirmasi';
                this.message        = options.message     ?? 'Apakah Anda yakin?';
                this.confirmText    = options.confirmText ?? 'Ya';
                this.livewireAction = options.livewireAction ?? 'deleteItem';
                this.livewireParams = options.roleId !== undefined ? [options.roleId] : [];
                this.open = true;
            };

            if (this.open) {
                this.open = false;
                setTimeout(open, 250);
            } else {
                open();
            }
        },

        confirm() {
            this.open = false;

            if (this.formId !== '') {
                document.getElementById(this.formId).submit();

            } else if (this.livewireAction !== '') {
                Livewire.dispatch('call-livewire-action', {
                    action: this.livewireAction,
                    params: this.livewireParams,
                });

            } else if (this.actionUrl !== '') {
                const form = this.$refs.confirmForm;
                form.action = this.actionUrl;
                this.$refs.methodField.value = this.actionMethod;
                form.submit();
            }

            this.$nextTick(() => this.resetState());
        },

        close() {
            this.open = false;
            this.$nextTick(() => this.resetState());
        },

        resetState() {
            this.type           = 'danger';
            this.title          = '';
            this.message        = '';
            this.confirmText    = '';
            this.actionUrl      = '';
            this.actionMethod   = 'DELETE';
            this.formId         = '';
            this.livewireAction = '';
            this.livewireParams = [];
        }
    }
}
</script>
