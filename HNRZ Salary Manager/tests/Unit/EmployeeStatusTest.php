<?php

use App\Models\Employee;

test('active employees can be processed in payroll and inactive employees cannot', function () {
    $activeEmployee = new Employee(['is_active' => true]);
    $inactiveEmployee = new Employee(['is_active' => false]);

    expect($activeEmployee->canBeProcessedInPayroll())->toBeTrue()
        ->and($inactiveEmployee->canBeProcessedInPayroll())->toBeFalse();
});
