<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'store_name',
        'address',
        'phone',
        'email',
        'carousel_1',
        'carousel_2',
        'carousel_3',
    ];
}