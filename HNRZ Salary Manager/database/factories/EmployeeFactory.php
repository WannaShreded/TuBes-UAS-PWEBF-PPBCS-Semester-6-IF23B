<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        return [
            'id_pekerja' => 'EMP-' . $this->faker->unique()->numerify('###'),
            'nik' => $this->faker->unique()->numerify('################'),
            'nama_lengkap' => $this->faker->name(),
            'no_telepon' => $this->faker->phoneNumber(),
            'nama_bank' => $this->faker->randomElement(['BCA', 'BNI', 'BRI', 'Mandiri']),
            'nomor_rekening' => $this->faker->numerify('##########'),
            'email' => $this->faker->unique()->safeEmail(),
            'alamat' => $this->faker->address(),
            'jabatan' => $this->faker->jobTitle(),
            'role' => 'karyawan',
            'is_active' => $this->faker->boolean(85),
        ];
    }
}
