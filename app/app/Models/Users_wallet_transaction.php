<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class Users_wallet_transaction extends Model
{
    use HasFactory , HasRoles, Notifiable;

    protected $fillable = [
        'userid',
        'value',
        'type',
        'details',
    ];
}
