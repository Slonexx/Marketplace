<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoLogModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'accountId',
        'message',
    ];

    protected $hidden = [
        'updated_at',
    ];

}
