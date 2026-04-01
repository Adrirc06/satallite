<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Psu extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
        'power',
        'psu_type_id',
        'modularity_id',
        'efficiency_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'power' => 'integer',
            'psu_type_id' => 'integer',
            'modularity_id' => 'integer',
            'efficiency_id' => 'integer',
        ];
    }

    public function builds(): HasMany
    {
        return $this->hasMany(Build::class);
    }
}
