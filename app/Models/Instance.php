<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Instance extends Model
{
    protected $fillable = ['entity_id'];

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

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

    public function data(): HasMany
    {
        return $this->hasMany(InstanceData::class)->with('property');
    }
}
