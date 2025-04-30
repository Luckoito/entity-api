<?php

namespace App\Http\Controllers;

use App\Http\Requests\PropertyRequest;
use App\Repositories\PropertyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    private PropertyRepository$repository;

    public function __construct()
    {
        $this->repository = new PropertyRepository();
    }

    /**
     * Returns all records.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $properties = $this->repository->getAll();
        return response()->json($properties);
    }

    /**
     * Counts the number of records.
     *
     * @return JsonResponse
     */
    public function count(): JsonResponse
    {
        return response()->json($this->repository->getAll()->count());
    }

    /**
     * Finds a property by its ID.
     * @param int $property_id
     * @return JsonResponse
     */
    public function show(int $property_id): JsonResponse
    {
        $property = $this->repository->find($property_id);
        return response()->json($property->load('entity'));
    }

    /**
     * Finds a property by its name.
     * @param string $name
     * @return JsonResponse
     */
    public function showByName(string $name): JsonResponse
    {
        $property = $this->repository->findByName($name);
        return response()->json($property->load('entity'));
    }

    /**
     * Stores a new property.
     * @param PropertyRequest $request
     * @return JsonResponse
     */
    public function store(PropertyRequest $request): JsonResponse
    {
        $property = $this->repository->store($request->name, $request->entity_id);
        return response()->json($property->load('entity.properties'), 201);
    }

    /**
     * Renames a property.
     * @param Request $request
     * @return JsonResponse
     */
    public function rename(Request $request): JsonResponse
    {
        $request->validate(['name' => 'required|string', 'id' => 'required|integer']);
        $property = $this->repository->rename($request->id, $request->name);
        return response()->json($property->load('entity.properties'), 201);
    }

    /**
     * Deletes a property.
     * @param int $property_id
     * @return JsonResponse
     */
    public function destroy(int $property_id): JsonResponse
    {
        $this->repository->destroy($property_id);
        return response()->json(['message' => 'Property deleted successfully.']);
    }
}
