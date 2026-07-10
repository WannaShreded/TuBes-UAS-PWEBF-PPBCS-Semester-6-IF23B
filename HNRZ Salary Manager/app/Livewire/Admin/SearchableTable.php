<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

abstract class SearchableTable extends Component
{
    use WithPagination;

    public string $search = '';

    public int $perPage = 5;

    protected $queryString = ['search' => ['except' => '']];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingPerPage(): void
    {
        $this->resetPage();
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
