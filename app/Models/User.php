<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function detail(): HasOne
    {
        return $this->hasOne(UserDetail::class);
    }

    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    public function transactions(): HasManyThrough
    {
        return $this->hasManyThrough(
            Transaction::class,
            Wallet::class,
            'user_id',
            'wallet_id'
        );
    }

    public function sentTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'from_user_id');
    }

    public function receivedTransfers(): HasMany
    {
        return $this->hasMany(Transfer::class, 'to_user_id');
    }

    public function issuedInvoicesFrom(): HasMany
    {
        return $this->hasMany(Invoice::class, 'from_user_id');
    }

    public function issuedInvoicesTo(): HasMany
    {
        return $this->hasMany(Invoice::class, 'to_user_id');
    }
}
