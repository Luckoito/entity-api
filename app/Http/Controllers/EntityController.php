<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntityRequest;
use App\Repositories\EntityRepository;
use App\Repositories\PropertyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    private EntityRepository $repository;

    public function __construct()
    {
        $this->repository = new EntityRepository();
    }

    /**
     * Display a listing of the entities.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $entities = $this->repository->getAll();
        return response()->json($entities);
    }

    /**
     * Returns all records of an entity by entity name.
     * @param string $entity_name
     * @return JsonResponse
     */
    public function show(string $entity_name): JsonResponse
    {
        $records = $this->repository->getByName($entity_name);
        return response()->json($records);
    }

    /**
     * Returns a specific entity by its ID.
     * @param int $entity_id
     * @return JsonResponse
     */
    public function showById(int $entity_id): JsonResponse
    {
        $entity = $this->repository->find($entity_id);
        return response()->json($entity->load('properties', 'instances.data.property'));
    }

    /**
     * Counts the number of records.
     * @return JsonResponse
     */
    public function count(): JsonResponse
    {
        return response()->json($this->repository->getAll()->count());
    }

    /**
     * Store a newly created entity in storage and sets its properties.
     * @param EntityRequest $request
     * @return JsonResponse
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
     * @param EntityRequest $request
     * @return JsonResponse
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
     * @param int $entity_id
     * @return JsonResponse
     */
    public function destroy(int $entity_id): JsonResponse
    {
        $this->repository->delete($entity_id);
        return response()->json(['message'=>'Entity deleted successfully'], 204);
    }
}
