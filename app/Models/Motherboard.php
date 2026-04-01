<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Motherboard extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
        'max_memory',
        'socket_id',
        'form_factor_id',
        'ram_type_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'max_memory' => 'integer',
            'socket_id' => 'integer',
            'form_factor_id' => 'integer',
            'ram_type_id' => 'integer',
        ];
    }

    public function builds(): HasMany
    {
        return $this->hasMany(Build::class);
    }
}
