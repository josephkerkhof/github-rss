<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    /** @use HasFactory<\Database\Factories\BranchFactory> */
    use HasFactory;

    protected $fillable = [
        'repository_id',
        'name',
    ];

    public function pullRequests(): HasMany
    {
        return $this->hasMany(PullRequest::class);
    }

    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }
}
