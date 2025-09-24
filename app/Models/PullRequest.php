<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PullRequest extends Model
{
    /** @use HasFactory<\Database\Factories\PullRequestFactory> */
    use HasFactory;

    protected $fillable = [
        'repository_id',
        'branch_id',
        'author_id',
        'number',
        'title',
        'body',
        'url',
        'merged_at',
    ];

    protected $attributes = [
        'body' => null,
    ];

    protected $casts = [
        'merged_at' => 'datetime',
    ];

    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }
}
