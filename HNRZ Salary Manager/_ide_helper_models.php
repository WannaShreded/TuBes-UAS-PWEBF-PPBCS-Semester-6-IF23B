<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $nama_bonus
 * @property numeric $nominal_bonus
 * @property string $jenis_bonus
 * @property \Illuminate\Support\Carbon $periode_bonus
 * @property string|null $keterangan
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $nominal_format
 * @property-read string $periode_label
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereJenisBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereKeterangan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereNamaBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereNominalBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus wherePeriodeBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bonus whereUpdatedAt($value)
 */
	class Bonus extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $user_id
 * @property string $id_pekerja
 * @property string $nik
 * @property string $nama_lengkap
 * @property string $no_telepon
 * @property string $nama_bank
 * @property string $nomor_rekening
 * @property string $email
 * @property string $alamat
 * @property string $jabatan
 * @property int|null $jabatan_id
 * @property int|null $payroll_method_id
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $position_name
 * @property-read int $salary
 * @property-read \App\Models\PayrollMethod|null $payrollMethod
 * @property-read \App\Models\Jabatan|null $position
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\EmployeeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereAlamat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereIdPekerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereJabatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereJabatanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNamaBank($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNamaLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNoTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereNomorRekening($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee wherePayrollMethodId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Employee whereUserId($value)
 */
	class Employee extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property int $salary
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Jabatan whereUpdatedAt($value)
 */
	class Jabatan extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $type
 * @property string $name
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Employee> $employees
 * @property-read int|null $employees_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayrollMethod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayrollMethod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayrollMethod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayrollMethod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayrollMethod whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayrollMethod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayrollMethod whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayrollMethod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayrollMethod whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PayrollMethod whereUpdatedAt($value)
 */
	class PayrollMethod extends \Eloquent {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Employee|null $employee
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $teams
 * @property-read int|null $teams_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User permission($permissions, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User role($roles, ?string $guard = null, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User team($teams, bool $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutRole($roles, ?string $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTeam($teams)
 */
	class User extends \Eloquent implements \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

