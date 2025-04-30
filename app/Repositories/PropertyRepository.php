<?php

namespace App\Repositories;

use App\Models\Entity;
use App\Models\Property;
use Illuminate\Database\Eloquent\Collection;

class PropertyRepository
{
    protected Property $model;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->model  = new Property();
    }

    /**
     * Finds a property by its ID.
     *
     * @param int $id
     * @return Property
     */
    public function findById(int $id): Property
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Finds a property by its entity id and name.
     *
     * @param int $entityId
     * @param string $name
     * @return Property|null
     */
    public function findByEntityIdAndName(int $entityId, string $name): ?Property
    {
        return $this->model->where('entity_id', $entityId)
            ->where('name', $name)
            ->first();
    }

    /**
     * Stores a new property.
     *
     * @param string $name
     * @param int $entityId
     * @return Property
     */
    public function store(string $name, int $entityId)
    {
        return $this->model->create([
            'name' => $name,
            'entity_id' => $entityId,
        ]);
    }

    /**
     * Renames an existing property.
     *
     * @param int $id
     * @param string $name
     * @return Property
     */
    public function rename(int $id, string $name): Property
    {
        $property = $this->model->findOrFail($id);
        $property->update(['name' => $name]);
        return $property;
    }

    /**
     * Deletes a property.
     *
     * @param int $id
     * @return void
     */
    public function destroy(int $id): void
    {
        $property = $this->model->findOrFail($id);
        $property->delete();
    }

    /**
     * Validates the properties in an array against the entity and returns ids and values.
     *
     * @param Entity $entity
     * @param array $propertiesToValidate
     * @return array: [propertyId => value]
     */
    public function validateProperties(Entity $entity, array $propertiesToValidate): array
    {
        $properties = [];
        foreach ($propertiesToValidate as $property) {
            $dbProperty = $this->findByEntityIdAndName($entity->id, $property['name']);
            $properties[$dbProperty->id] = $property['value'];
        }
        return $properties;
    }

    /**
     * Gets all properties.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Finds a property by its ID.
     *
     * @param int $property_id
     * @return Property
     */
    public function find(int $property_id): Property
    {
        return $this->model->findOrFail($property_id);
    }

    /**
     * Finds properties by name.
     *
     * @param string $name
     * @return Collection
     */
    public function findByName(string $name): Collection
    {
        return $this->model->where('name', $name)->get();
    }
}
