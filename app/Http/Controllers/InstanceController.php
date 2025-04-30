<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstanceRequest;
use App\Models\Entity;
use App\Repositories\EntityRepository;
use App\Repositories\InstanceRepository;
use App\Repositories\PropertyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InstanceController extends Controller
{
    private $repository;

    public function __construct()
    {
        $this->repository = new InstanceRepository();
    }

    /**
     * Returns all instances.
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        $instances = $this->repository->getAll();
        return response()->json($instances);
    }

    /**
     * Counts the number of instances.
     * @return JsonResponse
     */
    public function count(): JsonResponse
    {
        return response()->json($this->repository->getAll()->count());
    }

    /**
     * Returns all instances of a given entity.
     * @param string $entity_name
     * @return JsonResponse
     */
    public function index(string $entity_name): JsonResponse
    {
        $instances = $this->repository->getByEntity($entity_name);
        return response()->json($instances->load('data.property.entity'));
    }

    /**
     * Returns a specific instance by its ID.
     * @param int $instance_id
     * @return JsonResponse
     */
    public function show(int $instance_id): JsonResponse
    {
        $instance = $this->repository->findById($instance_id);
        return response()->json($instance->load('data.property.entity'));
    }

    /**
     * Returns a specific instance by its data.
     * @param Request $request
     * @return JsonResponse
     */
    public function showByData(Request $request): JsonResponse
    {
        $request->validate([
            'properties' => 'required|array',
            'properties.*.name' => 'required|string',
            'properties.*.value' => 'required',
        ]);

        $instances = $this->repository->getByData($request->properties);
        if ($instances->isEmpty()) {
            return response()->json(['message' => 'No instances found.'], 404);
        }

        return response()->json($instances->load('data.property.entity'));
    }


    /**
     * Stores a new instance of an entity.
     * @param InstanceRequest $request
     * @return JsonResponse
     */
    public function store(InstanceRequest $request): JsonResponse
    {
        $entity = $this->validateEntity($request->entity);

        $properties = $this->validateProperties($entity, $request->properties);

        $instance = $this->repository->store($entity, $properties);

        return response()->json($instance->load('data.property.entity'), 201);
    }

    /**
     * Updates an instance by its ID.
     * @param InstanceRequest $request
     * @return JsonResponse
     */
    public function update(InstanceRequest $request): JsonResponse
    {
        $instance = $this->repository->findById($request->id);
        $properties = $this->validateProperties($instance->entity, $request->properties);

        $instance = $this->repository->update($instance, $properties);

        return response()->json($instance->load('data.property.entity'), 200);
    }

    /**
     * Validates the entity.
     */
    public function validateEntity(string $entityName): Entity|JsonResponse
    {
        $entity = (new EntityRepository())->getByName($entityName);
        if (!$entity) {
            return response()->json(['message' => 'Entity not found.'], 404);
        }
        return $entity;
    }

    /**
     * Validates the properties.
     * @param Entity $entity
     * @param array $properties
     * @return array|JsonResponse
     */
    public function validateProperties(Entity $entity, array $properties): array|JsonResponse
    {
        $properties = (new PropertyRepository())->validateProperties($entity, $properties);
        if (!count($properties)) {
            return response()->json(['message' => 'No valid properties provided.'], 422);
        }

        return $properties;
    }

    /**
     * Deletes an instance by its ID.
     * @param int $instance_id
     * @return JsonResponse
     */
    public function destroy(int $instance_id): JsonResponse
    {
        $this->repository->destroy($instance_id);
        return response()->json(['message' => 'Instance deleted successfully.']);
    }

}
