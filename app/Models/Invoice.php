<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'deposit_id',
        'withdraw_id',
        'amount',
        'status'
    ];
}
