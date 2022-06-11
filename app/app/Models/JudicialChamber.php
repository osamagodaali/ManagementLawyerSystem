<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JudicialChamber extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'courtid',
        'address',
    ];
}
