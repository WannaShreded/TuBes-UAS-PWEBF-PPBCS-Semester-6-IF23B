<div
    x-data="confirmModal()"
    x-show="open"
    x-cloak
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
                {{-- Icon hapus --}}
                <template x-if="type === 'danger'">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </template>
                {{-- Icon edit --}}
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

        {{-- Form tersembunyi untuk submit delete --}}
        <form x-ref="confirmForm" method="POST" class="hidden">
            @csrf
            <input x-ref="methodField" type="hidden" name="_method">
        </form>
    </div>
</div>

{{-- Form tersembunyi untuk submit DELETE --}}
<form x-ref="confirmForm" method="POST" class="hidden">
    @csrf
    <input x-ref="methodField" type="hidden" name="_method" value="DELETE">
</form>

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

        init() {
            window.openConfirmModal = (options) => {
                this.type         = options.type         ?? 'danger';
                this.title        = options.title        ?? 'Konfirmasi';
                this.message      = options.message      ?? 'Apakah Anda yakin?';
                this.confirmText  = options.confirmText  ?? 'Ya';
                this.actionUrl    = options.actionUrl    ?? '';
                this.actionMethod = options.actionMethod ?? 'DELETE';
                this.formId       = options.formId       ?? '';
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

        confirm() {
            this.open = false;

            if (this.formId !== '') {
                // Kasus: Simpan Perubahan di halaman edit
                // Submit form edit yang sudah ada di halaman
                document.getElementById(this.formId).submit();

            } else if (this.actionUrl !== '') {
                // Kasus: Hapus data dari halaman index
                // Submit form tersembunyi dengan method DELETE/PUT
                const form = this.$refs.confirmForm;
                form.action = this.actionUrl;
                this.$refs.methodField.value = this.actionMethod;
                form.submit();

            }
        },

        close() {
            this.open = false;
        }
    }
}
</script>
