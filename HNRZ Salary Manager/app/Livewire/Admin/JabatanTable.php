<?php

namespace App\Livewire\Admin;

use App\Models\Jabatan;

class JabatanTable extends SearchableTable
{
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
        $query = Jabatan::query()->orderBy('id');

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

        return $query->paginate($this->perPage);
    }

    private function normalizeNumericFilter(?string $value): ?int
    {
        if ($value === null || trim((string) $value) === '') {
            return null;
        }

        return (int) trim((string) $value);
    }
}
