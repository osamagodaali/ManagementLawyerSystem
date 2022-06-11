<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class revenues extends Model
{
    use HasFactory;

    protected $fillable = [
        'value',
        'details',
        'userid',
        'caseid',
        'addedby',
    ];
}
