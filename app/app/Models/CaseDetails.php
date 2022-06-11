<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'caseid',
        'case_decisions',
        'suggested_action',
        'details',
        'nextfollowdate',
        'followby',
    ];
}
