<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'accountId',
        'tokenMs',
        'apiKey',

        'saleChannel',
        'project',

        'paymentDocument',
        'payment_type',
        'OperationCash',
        'OperationCard',
        'OperationMobile',
    ];

    use HasFactory;
}
