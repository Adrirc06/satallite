<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chassis extends Model
{
    use HasFactory;

    protected $table = 'chassis';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
        'chassis_type_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'chassis_type_id' => 'integer',
        ];
    }

    public function builds(): HasMany
    {
        return $this->hasMany(Build::class);
    }
}
