<?php
// File: app/Models/User.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
<<<<<<< Updated upstream
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
=======
use Spatie\Permission\Traits\HasRoles;  // <-- tambahkan ini

>>>>>>> Stashed changes
class User extends Authenticatable
{
	use HasRoles;  // <-- tambahkan trait ini

	protected $fillable = [
    	'name',
    	'email',
    	'password',
	];

	// ... kode lainnya
}
