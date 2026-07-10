<?php

use App\Livewire\Admin\BonusTable;
use App\Livewire\Admin\PayrollMethodTable;
use App\Models\Bonus;
use App\Models\PayrollMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('bonus type and month filters return the matching records', function () {
    Bonus::create([
        'nama_bonus' => 'Bonus Bulanan',
        'nominal_bonus' => 100000,
        'jenis_bonus' => 'Tunjangan',
        'periode_bonus' => '2026-07-01',
        'keterangan' => 'Cocok',
    ]);

    Bonus::create([
        'nama_bonus' => 'Bonus Lain',
        'nominal_bonus' => 200000,
        'jenis_bonus' => 'Insentif',
        'periode_bonus' => '2026-08-01',
        'keterangan' => 'Tidak cocok',
    ]);

    $component = Livewire::test(BonusTable::class)
        ->set('jenis_bonus', 'tunjangan')
        ->set('periode_bonus', '2026-07');

    $items = $component->viewData('items');

    expect($items->pluck('nama_bonus')->all())->toBe(['Bonus Bulanan']);
});

test('payroll method type and active filters return the matching records', function () {
    PayrollMethod::create([
        'name' => 'Transfer Bank',
        'type' => 'Transfer',
        'description' => 'Bank transfer',
        'is_active' => true,
    ]);

    PayrollMethod::create([
        'name' => 'Tunai',
        'type' => 'Cash',
        'description' => 'Cash',
        'is_active' => false,
    ]);

    $component = Livewire::test(PayrollMethodTable::class)
        ->set('type', 'transfer')
        ->set('is_active', '1');

    $items = $component->viewData('items');

    expect($items->pluck('name')->all())->toBe(['Transfer Bank']);
});
