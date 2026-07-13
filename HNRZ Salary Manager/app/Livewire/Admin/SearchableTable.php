<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

abstract class SearchableTable extends Component
{
    use WithPagination;

    public string $search = '';

    public int $perPage = 5;

    public string $sortField = 'created_at';

    public string $sortDirection = 'desc';

    protected $queryString = ['search' => ['except' => '']];

    protected $listeners = [
        'call-livewire-action' => 'handleAction',
    ];

    public function handleAction(string $action, array $params = []): void
    {
        if (method_exists($this, $action)) {
            $this->$action(...$params);
        }
    }

    public function updating(string $name): void
    {
        if ($name !== 'page') {
            $this->resetPage();
        }
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    protected function applySorting($query, array $allowed, string $fallback = 'created_at')
    {
        $field = in_array($this->sortField, $allowed, true) ? $this->sortField : $fallback;
        $direction = $this->sortDirection === 'asc' ? 'asc' : 'desc';

        return $query->orderBy($field, $direction);
    }

    protected function getView(): string
    {
        return 'livewire.admin.searchable-table';
    }

    abstract public function getItems();

    public function render()
    {
        return view($this->getView(), [
            'items' => $this->getItems(),
        ]);
    }
}
