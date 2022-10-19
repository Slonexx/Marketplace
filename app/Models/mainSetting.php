<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mainSetting extends Model
{

    protected $fillable = [
        "accountId",
        "TokenMoySklad",
        "TokenKaspi",
    ];

    use HasFactory;
}
