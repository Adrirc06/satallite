<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gpu extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
        'tdp',
        'vram',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'tdp' => 'integer',
            'vram' => 'integer',
        ];
    }

    public function builds(): HasMany
    {
        return $this->hasMany(Build::class);
    }
}
