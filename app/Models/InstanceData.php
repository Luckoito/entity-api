<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class InstanceData
 * @package App\Models
 */
class InstanceData extends Model
{
    /**
     * @var string[]
     */
    protected $fillable = ['value', 'property_id', 'instance_id'];

    /**
     * Get the property that this data belongs to.
     *
     * @return BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the instance that this data belongs to.
     *
     * @return BelongsTo
     */
    public function instance(): BelongsTo
    {
        return $this->belongsTo(Instance::class);
    }
}
