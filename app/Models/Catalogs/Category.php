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
    
    protected $appends = [
        'status_label',
    ];

    public function getStatusLabelAttribute(): string
    {
        return $this->attributes['status'] === 1 ? 'ACTIVE' : 'INACTIVE';
    }
}
