<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Build extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'is_public',
        'app_user_id',
        'motherboard_id',
        'cpu_id',
        'gpu_id',
        'ram_id',
        'psu_id',
        'drive_id',
        'chassis_id',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
            'app_user_id' => 'integer',
            'motherboard_id' => 'integer',
            'cpu_id' => 'integer',
            'gpu_id' => 'integer',
            'ram_id' => 'integer',
            'psu_id' => 'integer',
            'drive_id' => 'integer',
            'chassis_id' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'app_user_id');
    }

    public function motherboard(): BelongsTo
    {
        return $this->belongsTo(Motherboard::class);
    }

    public function cpu(): BelongsTo
    {
        return $this->belongsTo(Cpu::class);
    }

    public function gpu(): BelongsTo
    {
        return $this->belongsTo(Gpu::class);
    }

    public function ram(): BelongsTo
    {
        return $this->belongsTo(Ram::class);
    }

    public function psu(): BelongsTo
    {
        return $this->belongsTo(Psu::class);
    }

    public function drive(): BelongsTo
    {
        return $this->belongsTo(Drive::class);
    }

    public function chassis(): BelongsTo
    {
        return $this->belongsTo(Chassis::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
}
