<?php

namespace App\Models\Catalogs;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image',
        'price',
        'quantity',
        'category_id',
        'user_id',
    ];
    protected $attributes = [
        'category_id' => Category::WITHOUT_CATEGORY,        
        'status' => 1,
    ];

    protected $appends = [
        'status_label',
        'can_modify',
    ];

    public function getStatusLabelAttribute(): string
    {
        return $this->attributes['status'] === 1 ? 'ACTIVE' : 'INACTIVE';
    }

    public function getCanModifyAttribute(): bool
    {
        return $this->attributes['user_id'] === request()->user()->id;
    }
}
