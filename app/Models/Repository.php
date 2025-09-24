<?php

namespace App\Models;

use Iksaku\Laravel\MassUpdate\MassUpdatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $owner
 * @property string $repo
 */
class Repository extends Model
{
    /** @use HasFactory<\Database\Factories\RepositoryFactory> */
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
}
