<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class personal extends Model
{

    protected $fillable = [
        'appId',
        'accountId',
        'path',
    ];

    use HasFactory;
}
