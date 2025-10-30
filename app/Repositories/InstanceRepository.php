<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;

use App\Models\Entity;
use App\Models\Instance;

/**
 * Class InstanceRepository
 * @package App\Repositories
 */
class InstanceRepository
{
    /**
     * @var Instance
     */
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
     * @return Collection A collection of all instances.
     */
    public function getAll(): Collection
    {
        return $this->model->with('data.property.entity')->get();
    }

    /**
     * Finds an instance by its ID.
     *
     * @param int $id The ID of the instance to find.
     * @return Instance The instance object.
     */
    public function findById(int $id): Instance
    {
        return $this->model->with('data.property')->findOrFail($id);
    }

    /**
     * Stores a new instance.
     *
     * @param Entity $entity The entity of the new instance.
     * @param array $properties The properties of the new instance.
     * @return Instance The newly created instance.
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
     * @param Instance $instance The instance to update.
     * @param array $properties The new properties for the instance.
     * @return Instance The updated instance.
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
     * @param string $entityName The name of the entity.
     * @return Collection A collection of instances of the specified entity.
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
     * @param array $properties The properties to search for.
     * @return Collection A collection of instances that match the specified properties.
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
     * @param int $id The ID of the instance to delete.
     * @return void
     */
    public function destroy(int $id): void
    {
        $instance = $this->model->findOrFail($id);
        $instance->data()->delete();
        $instance->delete();
    }
}
