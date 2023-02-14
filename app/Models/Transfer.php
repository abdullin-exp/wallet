<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Transfer extends Model
{
    use HasFactory;

    protected $table = 'transfers';

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'from_wallet_id',
        'to_wallet_id',
        'amount',
        'confirmed',
        'scheduled_date',
    ];

    public function walletFrom(): HasOne
    {
        return $this->hasOne(Wallet::class, 'id', 'from_wallet_id');
    }

    public function walletTo(): HasOne
    {
        return $this->hasOne(Wallet::class, 'id', 'to_wallet_id');
    }

    public function setScheduledDateAttribute($value)
    {
        if ($value != '') {
            $this->attributes['scheduled_date'] = date('Y-m-d', strtotime($value));
        } else {
            $this->attributes['scheduled_date'] = null;
        }
    }

    public function getScheduledDateAttribute($value)
    {
        if ($value != '') {
            return date('d.m.Y', strtotime($value));
        }
    }
}
