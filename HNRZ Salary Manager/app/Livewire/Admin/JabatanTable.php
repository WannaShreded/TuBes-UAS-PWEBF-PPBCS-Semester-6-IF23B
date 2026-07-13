<?php

namespace App\Livewire\Admin;

use App\Models\Jabatan;

class JabatanTable extends SearchableTable
{
    public string $sortField = 'id';
    public string $salary_min = '';
    public string $salary_max = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'salary_min' => ['except' => ''],
        'salary_max' => ['except' => ''],
    ];

    protected function getView(): string
    {
        return 'livewire.admin.jabatan-table';
    }

    public function getItems()
    {
        $query = Jabatan::query();

        if ($this->search !== '') {
            $search = '%' . trim($this->search) . '%';

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('description', 'like', $search);
            });
        }

        $salaryMin = $this->normalizeNumericFilter($this->salary_min);
        if ($salaryMin !== null) {
            $query->where('salary', '>=', $salaryMin);
        }

        $salaryMax = $this->normalizeNumericFilter($this->salary_max);
        if ($salaryMax !== null) {
            $query->where('salary', '<=', $salaryMax);
        }

        return $this->applySorting($query, ['id', 'name', 'gaji_pokok', 'created_at'], 'id')->paginate($this->perPage);
    }

    private function normalizeNumericFilter(?string $value): ?int
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        return (int) trim((string) $value);
    }

    public function confirmDelete(int $id, string $name): void
    {
        $this->dispatch('open-confirm-modal', [
            'type'           => 'danger',
            'title'          => 'Konfirmasi Hapus',
            'message'        => "Anda akan menghapus jabatan \"{$name}\". Tindakan ini tidak dapat dibatalkan.",
            'confirmText'    => 'Ya, Hapus',
            'livewireAction' => 'deleteItem',
            'roleId'         => $id,
        ]);
    }

    public function deleteItem(int $id): void
    {
        $jabatan = Jabatan::findOrFail($id);

        if ($jabatan->employees()->exists()) {
            $this->dispatch('notify', message: 'Jabatan tidak dapat dihapus karena masih digunakan oleh karyawan.', type: 'error');
            return;
        }

        $jabatan->delete();
        $this->dispatch('notify', message: 'Jabatan berhasil dihapus.', type: 'success');
    }
}
