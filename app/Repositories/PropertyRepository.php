<?php

namespace App\Repositories;

use App\Models\Entity;
use App\Models\Property;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class PropertyRepository
 * @package App\Repositories
 */
class PropertyRepository
{
    /**
     * @var Property
     */
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
     * @param int $id The ID of the property to find.
     * @return Property The property object.
     */
    public function findById(int $id): Property
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Finds a property by its entity id and name.
     *
     * @param int $entityId The ID of the entity.
     * @param string $name The name of the property.
     * @return Property|null The property object if found, otherwise null.
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
     * @param string $name The name of the new property.
     * @param int $entityId The ID of the entity that the property belongs to.
     * @return Property The newly created property.
     */
    public function store(string $name, int $entityId): Property
    {
        return $this->model->create([
            'name' => $name,
            'entity_id' => $entityId,
        ]);
    }

    /**
     * Renames an existing property.
     *
     * @param int $id The ID of the property to rename.
     * @param string $name The new name for the property.
     * @return Property The renamed property.
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
     * @param int $id The ID of the property to delete.
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
     * @param Entity $entity The entity to validate the properties against.
     * @param array $propertiesToValidate The properties to validate.
     * @return array An array of validated properties, with property IDs as keys and their values as values.
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
     * @return Collection A collection of all properties.
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Finds a property by its ID.
     *
     * @param int $property_id The ID of the property to find.
     * @return Property The property object.
     */
    public function find(int $property_id): Property
    {
        return $this->model->findOrFail($property_id);
    }

    /**
     * Finds properties by name.
     *
     * @param string $name The name of the property to find.
     * @return Collection A collection of properties that match the specified name.
     */
    public function findByName(string $name): Collection
    {
        return $this->model->where('name', $name)->get();
    }
}
