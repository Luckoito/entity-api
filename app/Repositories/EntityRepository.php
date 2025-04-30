<?php
namespace App\Repositories;

use App\Models\Entity;
use Illuminate\Database\Eloquent\Collection;

class EntityRepository
{
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
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->model->with('properties')->get();
    }

    /**
     * Get entity records by name.
     *
     * @param string $name
     * @return Entity|null
     */
    public function getByName(string $name): ?Entity
    {
        return $this->model->where('name', $name)->with('properties')->first();
    }

    /**
     * Create a new entity.
     *
     * @param string $name
     * @return Entity
     */
    public function create(string $name): Entity
    {
        return $this->model->create(['name'=>$name]);
    }

    /**
     * Renames an existing entity.
     *
     * @param int $id
     * @param string $name
     * @return Entity
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
     * @param int $entity_id
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
     * @param int $entity_id
     * @return Entity
     */
    public function find(int $entity_id): Entity
    {
        return $this->model->findOrFail($entity_id);
    }
}
