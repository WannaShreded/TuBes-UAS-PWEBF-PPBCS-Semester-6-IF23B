<?php

namespace App\Livewire\Admin;

use App\Models\Bonus;

class BonusTable extends SearchableTable
{
    public string $jenis_bonus = '';
    public string $periode_bonus = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'jenis_bonus' => ['except' => ''],
        'periode_bonus' => ['except' => ''],
    ];

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

        $query->when($this->jenis_bonus !== '', fn ($q, $jenis) => $q->where('jenis_bonus', $jenis));
        $query->when($this->periode_bonus !== '', fn ($q, $periode) => $q->where('periode_bonus', 'like', $periode . '%'));

        return $query->paginate($this->perPage);
    }
}
