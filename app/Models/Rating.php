<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    const TABLE = 'ratings';

    protected $table = self::TABLE;

    public $timestamps = false;

    protected $fillable = [
        'rating',
        'app_user_id',
        'build_id',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'app_user_id' => 'integer',
            'build_id' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'app_user_id');
    }

    public function build(): BelongsTo
    {
        return $this->belongsTo(Build::class);
    }
}
