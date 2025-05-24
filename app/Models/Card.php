<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\CardStatus;
use App\Enums\CardPriority;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use App\Models\User;
use App\Models\Workspace;
use App\Models\Member;
use App\Models\Attachment;
use App\Models\Task;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function workspaces(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function attachments(): HasMany
    {
        return this->hasMany(Attachment::class);
    }

    public function tasks(): HasMany
    {
        return this->hasMany(Task::class);
    }

    public function members(): MorphMany
    {
        return this->morphMany(Member::class, 'memberable');
    }
}
