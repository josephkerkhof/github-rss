<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

final class Author extends Model
{
    /** @use HasFactory<\Database\Factories\AuthorFactory> */
    use HasFactory;

    protected $fillable = [
        'username',
        'profile_url'
    ];

    public function pullRequests(): HasMany
    {
        return $this->hasMany(PullRequest::class);
    }
}
