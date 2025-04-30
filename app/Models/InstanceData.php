<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstanceData extends Model
{
    protected $fillable = ['value', 'property_id', 'instance_id'];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function instance(): BelongsTo
    {
        return $this->belongsTo(Instance::class);
    }
}
