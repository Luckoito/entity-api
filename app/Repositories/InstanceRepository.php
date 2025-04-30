<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Models\Entity;
use App\Models\Instance;

class InstanceRepository
{
    private Instance $model;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->model = new Instance();
    }

    /**
     * Gets all instances.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->with('data.property.entity')->get();
    }

    /**
     * Finds an instance by its ID.
     *
     * @param int $id
     * @return Instance
     */
    public function findById(int $id)
    {
        return $this->model->with('data.property')->findOrFail($id);
    }

    /**
     * Stores a new instance.
     *
     * @param Entity $entity
     * @param array $properties
     * @return Instance
     */
    public function store(Entity $entity, array $properties): Instance
    {
        $instance = $this->model->create([
            'entity_id' => $entity->id,
        ]);

        foreach ($properties as $propertyId => $value) {
            $instance->data()->create([
                'property_id' => $propertyId,
                'value' => $value,
            ]);
        }

        return $instance;
    }

    /**
     * Updates an instance by its ID.
     *
     * @param Instance $instance
     * @param array $properties
     * @return Instance
     */
    public function update(Instance $instance, array $properties): Instance
    {
        foreach ($properties as $propertyId => $value) {
            $instance->data()->updateOrCreate(
                ['property_id' => $propertyId],
                ['value' => $value]
            );
        }

        return $instance;
    }

    /**
     * Gets all instances of an entity by its name.
     *
     * @param string $entityName
     * @return Collection
     */
    public function getByEntity(string $entityName): Collection
    {
        return $this->model
            ->whereHas('entity', function ($query) use ($entityName) {
                $query->where('name', $entityName);
            })
            ->with('data.property.entity')
            ->get();
    }

    /**
     * Gets instances by their properties.
     *
     * @param array $properties
     * @return Collection
     */
    public function getByData(array $properties): Collection
    {
        return $this->model
            ->where(function ($query) use ($properties) {
                foreach ($properties as $property) {
                    $query->whereHas('data', function ($query) use ($property) {
                        $query->whereHas('property', function ($query) use ($property) {
                            $query->where('name', $property['name']);
                        })
                            ->where('value', $property['value']);
                    });
                }
            })
            ->with('data.property.entity')
            ->get();
    }

    /**
     * Deletes an instance by its ID.
     *
     * @param int $id
     * @return void
     */
    public function destroy(int $id): void
    {
        $instance = $this->model->findOrFail($id);
        $instance->data()->delete();
        $instance->delete();
    }
}
