<?php

namespace App\Livewire\Admin;

use App\Models\Bonus;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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

        $jenisBonus = trim((string) $this->jenis_bonus);
        if ($jenisBonus !== '') {
            $query->whereRaw('LOWER(jenis_bonus) = ?', [Str::lower($jenisBonus)]);
        }

        $periodeBonus = trim((string) $this->periode_bonus);
        if ($periodeBonus !== '') {
            $monthStart = Carbon::createFromFormat('Y-m', $periodeBonus)->startOfMonth()->toDateString();
            $monthEnd = Carbon::createFromFormat('Y-m', $periodeBonus)->endOfMonth()->toDateString();

            $query->whereBetween('periode_bonus', [$monthStart, $monthEnd]);
        }

        return $query->paginate($this->perPage);
    }
}
