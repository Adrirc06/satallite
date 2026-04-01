<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ram extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
        'cas_latency',
        'size',
        'modules',
        'speed',
        'ram_type_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'cas_latency' => 'integer',
            'size' => 'integer',
            'modules' => 'integer',
            'speed' => 'integer',
            'ram_type_id' => 'integer',
        ];
    }

    public function builds(): HasMany
    {
        return $this->hasMany(Build::class);
    }
}
