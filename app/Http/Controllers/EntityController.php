<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntityRequest;
use App\Repositories\EntityRepository;
use App\Repositories\PropertyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class EntityController
 * @package App\Http\Controllers
 */
class EntityController extends Controller
{
    /**
     * @var EntityRepository
     */
    private EntityRepository $repository;

    /**
     * EntityController constructor.
     */
    public function __construct()
    {
        $this->repository = new EntityRepository();
    }

    /**
     * Display a listing of all entities.
     *
     * @return JsonResponse A JSON response containing all entities.
     */
    public function index(): JsonResponse
    {
        $entities = $this->repository->getAll();
        return response()->json($entities);
    }

    /**
     * Returns all records of an entity by entity name.
     *
     * @param string $entity_name The name of the entity.
     * @return JsonResponse A JSON response containing the entity records.
     */
    public function show(string $entity_name): JsonResponse
    {
        $records = $this->repository->getByName($entity_name);
        return response()->json($records);
    }

    /**
     * Returns a specific entity by its ID.
     *
     * @param int $entity_id The ID of the entity.
     * @return JsonResponse A JSON response containing the entity.
     */
    public function showById(int $entity_id): JsonResponse
    {
        $entity = $this->repository->find($entity_id);
        return response()->json($entity->load('properties', 'instances.data.property'));
    }

    /**
     * Counts the total number of entities.
     *
     * @return JsonResponse A JSON response containing the total number of entities.
     */
    public function count(): JsonResponse
    {
        return response()->json($this->repository->getAll()->count());
    }

    /**
     * Store a newly created entity in storage and sets its properties.
     *
     * @param EntityRequest $request The request object containing the entity name and properties.
     * @return JsonResponse A JSON response containing the newly created entity.
     */
    public function store(EntityRequest $request): JsonResponse
    {
        $entity = $this->repository->create($request->name);
        $properties = $request->properties;

        foreach ($properties as $property) {
            (new PropertyRepository())->store($property, $entity->id);
        }
        return response()->json($entity->load('properties'), 201);
    }

    /**
     * Renames a specified entity.
     *
     * @param Request $request The request object containing the entity ID and new name.
     * @return JsonResponse A JSON response containing the renamed entity.
     */
    public function rename(Request $request): JsonResponse
    {
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|string|max:255',
        ]);

        $entity = $this->repository->rename($request->id, $request->name);
        return response()->json($entity);
    }

    /**
     * Deletes the specified entity.
     *
     * @param int $entity_id The ID of the entity to delete.
     * @return JsonResponse A JSON response confirming the deletion.
     */
    public function destroy(int $entity_id): JsonResponse
    {
        $this->repository->delete($entity_id);
        return response()->json(['message'=>'Entity deleted successfully'], 204);
    }
}
