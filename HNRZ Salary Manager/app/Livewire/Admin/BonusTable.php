<?php

namespace App\Livewire\Admin;

use App\Models\Bonus;
use App\Models\Employee;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class BonusTable extends SearchableTable
{
    public string $sortField = 'created_at';
    public string $jenis_bonus = '';
    public string $periode_bonus = '';

    protected $queryString = [
        'search'       => ['except' => ''],
        'jenis_bonus'  => ['except' => ''],
        'periode_bonus' => ['except' => ''],
    ];

    protected function getView(): string
    {
        return 'livewire.admin.bonus-table';
    }

    public function getItems()
    {
        $query = Bonus::query()
            ->withCount('employees')
            ->latest();

        if ($this->search !== '') {
            $search = '%' . trim($this->search) . '%';
            $query->where(function ($q) use ($search) {
                $q->where('nama_bonus', 'like', $search)
                    ->orWhere('jenis_bonus', 'like', $search)
                    ->orWhere('deskripsi', 'like', $search);
            });
        }

        $jenisBonus = trim((string) $this->jenis_bonus);
        if ($jenisBonus !== '') {
            $query->whereRaw('LOWER(jenis_bonus) = ?', [Str::lower($jenisBonus)]);
        }

        $periodeBonus = trim((string) $this->periode_bonus);
        if ($periodeBonus !== '') {
            $monthStart = Carbon::createFromFormat('Y-m', $periodeBonus)->startOfMonth()->toDateString();
            $monthEnd   = Carbon::createFromFormat('Y-m', $periodeBonus)->endOfMonth()->toDateString();
            $query->whereBetween('periode_bonus', [$monthStart, $monthEnd]);
        }

        return $this->applySorting($query, ['nama_bonus', 'jenis_bonus', 'nominal_bonus', 'periode_bonus', 'created_at'])->paginate($this->perPage);
    }

    public function confirmGiveToAll(int $id, string $name): void
    {
        $this->dispatch('open-confirm-modal', [
            'type'           => 'warning',
            'title'          => 'Konfirmasi Pemberian Bonus',
            'message'        => "Anda akan memberikan bonus \"{$name}\" ke SEMUA karyawan.",
            'confirmText'    => 'Ya, Berikan',
            'livewireAction' => 'giveToAll',
            'roleId'         => $id,
            'componentId'    => $this->getId(),
        ]);
    }

    public function confirmCancelAll(int $id, string $name): void
    {
        $this->dispatch('open-confirm-modal', [
            'type'           => 'danger',
            'title'          => 'Konfirmasi Pembatalan Bonus',
            'message'        => "Anda akan MEMBATALKAN bonus \"{$name}\" dari SEMUA karyawan yang sudah menerimanya.",
            'confirmText'    => 'Ya, Batalkan',
            'livewireAction' => 'cancelAll',
            'roleId'         => $id,
            'componentId'    => $this->getId(),
        ]);
    }

    public function confirmDelete(int $id, string $name): void
    {
        $this->dispatch('open-confirm-modal', [
            'type'           => 'danger',
            'title'          => 'Konfirmasi Hapus',
            'message'        => "Anda akan menghapus bonus \"{$name}\". Tindakan ini tidak dapat dibatalkan.",
            'confirmText'    => 'Ya, Hapus',
            'livewireAction' => 'deleteItem',
            'roleId'         => $id,
            'componentId'    => $this->getId(),
        ]);
    }

    public function deleteItem(int $id): void
    {
        Bonus::findOrFail($id)->delete();
        $this->dispatch('notify', message: 'Bonus berhasil dihapus.', type: 'success');
    }

    public function giveToAll(int $id): void
    {
        $bonus       = Bonus::findOrFail($id);
        $employeeIds = Employee::pluck('id');

        $bonus->employees()->syncWithoutDetaching($employeeIds);

        $this->dispatch(
            'notify',
            message: "Bonus \"{$bonus->nama_bonus}\" berhasil diberikan ke semua karyawan ({$employeeIds->count()} orang).",
            type: 'success'
        );
    }

    public function cancelAll(int $id): void
    {
        $bonus = Bonus::findOrFail($id);
        $count = $bonus->employees()->count();

        $bonus->employees()->detach();

        $this->dispatch(
            'notify',
            message: "Bonus \"{$bonus->nama_bonus}\" berhasil dibatalkan dari semua karyawan ({$count} orang).",
            type: 'success'
        );
    }
}
