<?php
namespace App\Repositories;

use App\Models\Entity;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class EntityRepository
 * @package App\Repositories
 */
class EntityRepository
{
    /**
     * @var Entity
     */
    protected Entity $model;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->model  = new Entity();
    }

    /**
     * Get all entities.
     *
     * @return Collection A collection of all entities.
     */
    public function getAll(): Collection
    {
        return $this->model->with('properties')->get();
    }

    /**
     * Get entity records by name.
     *
     * @param string $name The name of the entity.
     * @return Entity|null The entity object if found, otherwise null.
     */
    public function getByName(string $name): ?Entity
    {
        return $this->model->where('name', $name)->with('properties')->first();
    }

    /**
     * Create a new entity.
     *
     * @param string $name The name of the new entity.
     * @return Entity The newly created entity.
     */
    public function create(string $name): Entity
    {
        return $this->model->create(['name'=>$name]);
    }

    /**
     * Renames an existing entity.
     *
     * @param int $id The ID of the entity to rename.
     * @param string $name The new name for the entity.
     * @return Entity The renamed entity.
     */
    public function rename(int $id, string $name): Entity
    {
        $entity = $this->model->findOrFail($id);
        $entity->update(['name' => $name]);
        return $entity;
    }

    /**
     * Deletes the specified entity.
     *
     * @param int $entity_id The ID of the entity to delete.
     * @return void
     */
    public function delete(int $entity_id): void
    {
        $entity = Entity::findOrFail($entity_id);
        $entity->delete();
    }

    /**
     * Finds an entity by its ID.
     *
     * @param int $entity_id The ID of the entity to find.
     * @return Entity The entity object.
     */
    public function find(int $entity_id): Entity
    {
        return $this->model->findOrFail($entity_id);
    }
}
