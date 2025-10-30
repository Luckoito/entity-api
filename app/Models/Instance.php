<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Class Instance
 * @package App\Models
 */
class Instance extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['entity_id'];

    /**
     * Get the entity that this instance belongs to.
     *
     * @return BelongsTo
     */
    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    /**
     * Get the properties for the instance.
     *
     * @return HasManyThrough
     */
    public function properties(): HasManyThrough
    {
        return $this->hasManyThrough(
            Property::class,
            InstanceData::class,
            'instance_id',
            'id',
            'id',
            'property_id'
        );
    }

    /**
     * Get the data associated with the instance.
     *
     * @return HasMany
     */
    public function data(): HasMany
    {
        return $this->hasMany(InstanceData::class)->with('property');
    }
}
