<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;

class Cases extends Model
{
    use HasFactory , HasRoles, Notifiable;
    protected $fillable = [
        'case_number',
        'title',
        'userid',
        'brancheid',
        'courtid',
        'judicialchamberid',
        'case_type',
        'followby',
        'details',
        'value',
        'case_status',
        'payment_status',
        'start_case',
        'end_case',
    ];
}
