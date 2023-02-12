<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDetail extends Model
{
    use HasFactory;

    protected $table = 'user_details';

    protected $fillable = [
        'user_id',
        'last_name',
        'first_name',
        'patr_name',
        'birth_date',
        'gender'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function setLastNameAttribute($value)
    {
        if ($value != '') {
            $this->attributes['last_name'] = ucfirst(strtolower($value));
        }
    }

    public function setFirstNameAttribute($value)
    {
        if ($value != '') {
            $this->attributes['first_name'] = ucfirst(strtolower($value));
        }
    }

    public function setPatrNameAttribute($value)
    {
        if ($value != '') {
            $this->attributes['patr_name'] = ucfirst(strtolower($value));
        }
    }

    public function setBirthDateAttribute($value)
    {
        if ($value != '') {
            $this->attributes['birth_date'] = date('Y-m-d', strtotime($value));
        }
    }

    public function getBirthDateAttribute($value)
    {
        if ($value != '') {
            return date('d.m.Y', strtotime($value));
        }
    }
}
