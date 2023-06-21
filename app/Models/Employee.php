<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;


class Employee extends Authenticatable
{

    use HasFactory ,HasApiTokens;

    use SoftDeletes;

    protected $guard = 'employee';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'administrator_id',
    ];



    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relationships
    public function administrator()
    {
        return $this->belongsTo(Users::class);
    }

      /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

}
