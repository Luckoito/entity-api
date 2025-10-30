<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Entity
 * @package App\Models
 */
class Entity extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['name'];

    /**
     * Get the properties for the entity.
     *
     * @return HasMany
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Get the instances for the entity.
     *
     * @return HasMany
     */
    public function instances(): HasMany
    {
        return $this->hasMany(Instance::class);
    }
}
