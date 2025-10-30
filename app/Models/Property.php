<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Class Property
 * @package App\Models
 */
class Property extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['name', 'entity_id'];

    /**
     * Get the entity that this property belongs to.
     *
     * @return BelongsTo
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * Get the instance data for the property.
     *
     * @return HasMany
     */
    public function data(): HasMany
    {
        return $this->hasMany(InstanceData::class);
    }

    /**
     * Get all instances that have this property.
     *
     * @return HasManyThrough
     */
    public function instances(): HasManyThrough
    {
        return $this->hasManyThrough(
            Instance::class,
            InstanceData::class,
            'property_id',
            'id',
            'id',
            'instance_id'
        );
    }
}
