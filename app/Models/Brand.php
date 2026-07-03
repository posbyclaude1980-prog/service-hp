<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'country',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function phoneModels(): HasMany
    {
        return $this->hasMany(PhoneModel::class);
    }

    public function deviceModels(): HasMany
    {
        return $this->hasMany(PhoneModel::class);
    }
}
