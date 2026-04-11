<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cpu extends Model
{
    use HasFactory;

    const TABLE = 'cpus';

    protected $table = self::TABLE;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
        'cores',
        'tdp',
        'frequency',
        'max_frequency',
        'socket_id',
        'igpu_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'frequency' => 'decimal:2',
            'max_frequency' => 'decimal:2',
            'cores' => 'integer',
            'tdp' => 'integer',
            'socket_id' => 'integer',
            'igpu_id' => 'integer',
        ];
    }

    public function builds(): HasMany
    {
        return $this->hasMany(Build::class);
    }
}
