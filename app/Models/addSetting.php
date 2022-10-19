<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class addSetting extends Model
{

    protected $fillable = [
        "accountId",
        "Project",
        "Saleschannel",
        "APPROVED_BY_BANK",
        "ACCEPTED_BY_MERCHANT",
        "COMPLETED",
        "CANCELLED",
        "RETURNED",
    ];

    use HasFactory;
}
