<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\WorkspaceVisibility;

class Workspace extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'cover',
        'logo',
        'visibility',
    ];

    protected function castc()
    {
        return [
            'visibility' => WorkspaceVisibility::class,
        ];
    }
}
