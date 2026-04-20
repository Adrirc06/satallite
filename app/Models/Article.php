<?php

namespace App\Models;

use App\Traits\HasAuthor;
use App\Traits\ModelHelpers;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasAuthor, HasFactory, ModelHelpers;

    const TABLE = 'articles';

    protected $table = self::TABLE;

    public $timestamps = false;

    protected $fillable = [
        'title',
        'subtitle',
        'content',
        'banner_url',
        'public_banner_url',
        'date',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }
}
