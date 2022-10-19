<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orderSetting extends Model
{

    protected $fillable = [
        "accountId",
        "Organization",
        "PaymentDocument",
        "Document",
        "PaymentAccount",
        "CheckCreatProduct",
        "Store",
    ];

    use HasFactory;
}
