<?php

namespace App\Console\Commands;

use App\Models\PayrollHistory;
use Illuminate\Console\Command;

class BackfillPayrollHistoryData extends Command
{
    protected $signature = 'payroll-histories:backfill {--dry-run : Tampilkan perubahan tanpa menyimpan ke database}';

    protected $description = 'Perbarui kolom bonus, payment_method, dan payment_method_id pada semua Riwayat Gaji agar sesuai data karyawan pada periode masing-masing';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $histories = PayrollHistory::with('employee.payrollMethod')->get();

        if ($histories->isEmpty()) {
            $this->info('Tidak ada data Riwayat Gaji untuk diperbarui.');
            return self::SUCCESS;
        }

        $updated = 0;
        $skipped = 0;

        $this->withProgressBar($histories, function (PayrollHistory $history) use (&$updated, &$skipped, $dryRun) {
            $employee = $history->employee;

            if (! $employee) {
                $skipped++;
                return;
            }

            $payrollPeriod = $history->payroll_period;

            $bonusAmount = (int) $employee->payrollBonusesQuery($payrollPeriod)->sum('nominal_bonus');
            $bonusId = $employee->payrollBonusesQuery($payrollPeriod)->first()?->id;
            $paymentMethod = $employee->payrollMethod;

            $newData = [
                'bonus_id' => $bonusId,
                'bonus' => $bonusAmount,
                'payment_method_id' => $paymentMethod?->id,
                'payment_method' => $paymentMethod?->name ?? '-',
                'total_dibayarkan' => $history->gaji_pokok + $bonusAmount,
            ];

            $hasChanges = false;
            foreach ($newData as $key => $value) {
                if ($history->{$key} != $value) {
                    $hasChanges = true;
                    break;
                }
            }

            if (! $hasChanges) {
                $skipped++;
                return;
            }

            if (! $dryRun) {
                $history->update($newData);
            }

            $updated++;
        });

        $this->newLine(2);
        $this->info(($dryRun ? '[DRY RUN] ' : '') . "Selesai. {$updated} record diperbarui, {$skipped} record dilewati.");

        return self::SUCCESS;
    }
}
