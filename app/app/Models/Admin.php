<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
// class Admin extends Model
{
    // use HasFactory;
    use HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'mobile',
        'email',
        'address',
        'password',
        'random_password',
        'cases_availabe',
        'roles',
        'status',
        'password_status',
    ];


    public function receivesBroadcastNotificationsOn()
    {
        return 'admins.'.$this->id;
    }
}
