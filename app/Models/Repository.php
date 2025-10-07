<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\RepositoryFactory;
use Iksaku\Laravel\MassUpdate\MassUpdatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property string $owner
 * @property string $slug
 * @property string $repo
 */
class Repository extends Model
{
    /** @use HasFactory<RepositoryFactory> */
    use HasFactory;
    use MassUpdatable;

    protected $fillable = [
        'name',
        'owner',
        'repo'
    ];

    protected function slug(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->owner . '/' . $this->repo,
        );
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class);
    }

    public function pullRequests(): HasMany
    {
        return $this->hasMany(PullRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
