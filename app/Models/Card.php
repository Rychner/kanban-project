<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\CardStatus;
use App\Enums\CardPriority;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'workspace_id',
        'title',
        'description',
        'deadline',
        'order',
        'status',
        'priority',
    ];

    protected function casts(): array
    {
        return [
            'status'    => CardStatus::class,
            'priority'  => CardPriority::class,
        ];
    }
}
