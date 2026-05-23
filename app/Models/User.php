<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'firstname',
        'lastname',
        'email',
        'password',
        'phone',
        'birth_date',
        'address',
        'city',
        'postal_code',
        'country',
        'profile_picture',
        'google_id',
        'avatar',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'birth_date'        => 'date',
    ];

    public function ordini()
    {
        return $this->hasMany(Ordine::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->firstname . ' ' . $this->lastname) ?: $this->name;
    }
}