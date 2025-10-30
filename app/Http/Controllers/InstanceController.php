<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstanceRequest;
use App\Models\Entity;
use App\Repositories\EntityRepository;
use App\Repositories\InstanceRepository;
use App\Repositories\PropertyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class InstanceController
 * @package App\Http\Controllers
 */
class InstanceController extends Controller
{
    /**
     * @var InstanceRepository
     */
    private $repository;

    /**
     * InstanceController constructor.
     */
    public function __construct()
    {
        $this->repository = new InstanceRepository();
    }

    /**
     * Returns all instances.
     *
     * @return JsonResponse A JSON response containing all instances.
     */
    public function all(): JsonResponse
    {
        $instances = $this->repository->getAll();
        return response()->json($instances);
    }

    /**
     * Counts the number of instances.
     *
     * @return JsonResponse A JSON response containing the total number of instances.
     */
    public function count(): JsonResponse
    {
        return response()->json($this->repository->getAll()->count());
    }

    /**
     * Returns all instances of a given entity.
     *
     * @param string $entity_name The name of the entity.
     * @return JsonResponse A JSON response containing the instances of the entity.
     */
    public function index(string $entity_name): JsonResponse
    {
        $instances = $this->repository->getByEntity($entity_name);
        return response()->json($instances->load('data.property.entity'));
    }

    /**
     * Returns a specific instance by its ID.
     *
     * @param int $instance_id The ID of the instance.
     * @return JsonResponse A JSON response containing the instance.
     */
    public function show(int $instance_id): JsonResponse
    {
        $instance = $this->repository->findById($instance_id);
        return response()->json($instance->load('data.property.entity'));
    }

    /**
     * Returns a specific instance by its data.
     *
     * @param Request $request The request object containing the properties to search for.
     * @return JsonResponse A JSON response containing the instance.
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
     *
     * @param InstanceRequest $request The request object containing the entity and properties.
     * @return JsonResponse A JSON response containing the newly created instance.
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
     *
     * @param InstanceRequest $request The request object containing the instance ID and properties.
     * @return JsonResponse A JSON response containing the updated instance.
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
     *
     * @param string $entityName The name of the entity to validate.
     * @return Entity|JsonResponse The entity object if found, otherwise a JSON response with an error message.
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
     *
     * @param Entity $entity The entity to validate the properties against.
     * @param array $properties The properties to validate.
     * @return array|JsonResponse An array of validated properties, otherwise a JSON response with an error message.
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
     *
     * @param int $instance_id The ID of the instance to delete.
     * @return JsonResponse A JSON response confirming the deletion.
     */
    public function destroy(int $instance_id): JsonResponse
    {
        $this->repository->destroy($instance_id);
        return response()->json(['message' => 'Instance deleted successfully.']);
    }

}
