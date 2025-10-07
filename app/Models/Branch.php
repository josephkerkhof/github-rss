<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\BranchFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    /** @use HasFactory<BranchFactory> */
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'repository_id',
        'name',
    ];

    public function uniqueIds(): array
    {
        return ['uuid'];
    }

    public function pullRequests(): HasMany
    {
        return $this->hasMany(PullRequest::class);
    }

    public function repository(): BelongsTo
    {
        return $this->belongsTo(Repository::class);
    }
}
