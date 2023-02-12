<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    public function fromWallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'id', 'from_wallet_id');
    }

    public function toWallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'id', 'to_wallet_id');
    }

    public function setScheduledAtAttribute($value)
    {
        if ($value != '') {
            $this->attributes['scheduled_at'] = date('Y-m-d', strtotime($value));
        } else {
            $this->attributes['scheduled_at'] = null;
        }
    }

    public function getScheduledAtAttribute($value)
    {
        if ($value != '') {
            return date('d.m.Y', strtotime($value));
        }
    }
}
