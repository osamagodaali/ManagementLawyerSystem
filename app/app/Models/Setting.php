<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable = [
        'company_name',
        'logo_img_name',
        'favicon_img_name',
        'profile_img_name',
        'login_user_img_name',
        'login_admin_img_name',
    ];
}
