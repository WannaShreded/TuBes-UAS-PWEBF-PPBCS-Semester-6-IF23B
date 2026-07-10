<?php

namespace App\Livewire\Admin;

use App\Models\Jabatan;

class JabatanTable extends SearchableTable
{
    protected function getView(): string
    {
        return 'livewire.admin.jabatan-table';
    }

    public function getItems()
    {
        $query = Jabatan::query()->orderBy('id');

        if ($this->search !== '') {
            $search = '%' . trim($this->search) . '%';

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('description', 'like', $search);
            });
        }

        return $query->paginate($this->perPage);
    }
}
