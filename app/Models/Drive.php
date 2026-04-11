<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Drive extends Model
{
    use HasFactory;

    const TABLE = 'drives';

    protected $table = self::TABLE;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'price',
        'size',
        'drive_type_id',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'size' => 'integer',
            'drive_type_id' => 'integer',
        ];
    }

    public function builds(): HasMany
    {
        return $this->hasMany(Build::class);
    }
}
