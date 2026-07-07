<?php

namespace App\Livewire\Admin;

use App\Models\Bonus;

class BonusTable extends SearchableTable
{
    protected function getView(): string
    {
        return 'livewire.admin.bonus-table';
    }

    public function getItems()
    {
        $query = Bonus::query()->latest();

        if ($this->search !== '') {
            $search = '%' . trim($this->search) . '%';

            $query->where(function ($q) use ($search) {
                $q->where('nama_bonus', 'like', $search)
                    ->orWhere('jenis_bonus', 'like', $search)
                    ->orWhere('keterangan', 'like', $search);
            });
        }

        return $query->paginate($this->perPage);
    }
}
