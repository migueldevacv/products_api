<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'description',
        'status'
    ];

    public const WITHOUT_CATEGORY = 1;
}
