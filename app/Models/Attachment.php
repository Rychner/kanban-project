<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
use App\Models\Card;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'card_id',
        'file',
        'link',
        'name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cards(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }



}
