<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Property extends Model
{
    protected $fillable = ['name', 'entity_id'];

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function data(): HasMany
    {
        return $this->hasMany(InstanceData::class);
    }

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
