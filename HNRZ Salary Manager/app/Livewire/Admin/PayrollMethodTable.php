<?php

namespace App\Livewire\Admin;

use App\Models\PayrollMethod;

class PayrollMethodTable extends SearchableTable
{
    public string $type = '';
    public string $is_active = '';
    public array $types = [];

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'is_active' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->types = PayrollMethod::query()->distinct()->pluck('type')->filter()->values()->toArray();
    }

    protected function getView(): string
    {
        return 'livewire.admin.payroll-method-table';
    }

    public function getItems()
    {
        $query = PayrollMethod::query()->latest();

        if ($this->search !== '') {
            $search = '%' . trim($this->search) . '%';

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('type', 'like', $search)
                    ->orWhere('description', 'like', $search);
            });
        }

        $query->when($this->type !== '', fn ($q, $type) => $q->where('type', $type));
        $query->when($this->is_active !== '', fn ($q, $active) => $q->where('is_active', (bool) $active));

        return $query->paginate($this->perPage);
    }
}
