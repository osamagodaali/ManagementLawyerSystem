<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class admin_has_cases_roles extends Model
{
    use HasFactory;
    protected $fillable = [
        'admin_id',
        'case_id',
    ];
}
