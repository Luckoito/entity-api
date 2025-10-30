<?php

namespace App\Http\Controllers;

use App\Http\Requests\PropertyRequest;
use App\Repositories\PropertyRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class PropertyController
 * @package App\Http\Controllers
 */
class PropertyController extends Controller
{
    /**
     * @var PropertyRepository
     */
    private PropertyRepository$repository;

    /**
     * PropertyController constructor.
     */
    public function __construct()
    {
        $this->repository = new PropertyRepository();
    }

    /**
     * Returns all properties.
     *
     * @return JsonResponse A JSON response containing all properties.
     */
    public function index(): JsonResponse
    {
        $properties = $this->repository->getAll();
        return response()->json($properties);
    }

    /**
     * Counts the total number of properties.
     *
     * @return JsonResponse A JSON response containing the total number of properties.
     */
    public function count(): JsonResponse
    {
        return response()->json($this->repository->getAll()->count());
    }

    /**
     * Finds a property by its ID.
     *
     * @param int $property_id The ID of the property.
     * @return JsonResponse A JSON response containing the property.
     */
    public function show(int $property_id): JsonResponse
    {
        $property = $this->repository->find($property_id);
        return response()->json($property->load('entity'));
    }

    /**
     * Finds a property by its name.
     *
     * @param string $name The name of the property.
     * @return JsonResponse A JSON response containing the property.
     */
    public function showByName(string $name): JsonResponse
    {
        $property = $this->repository->findByName($name);
        return response()->json($property->load('entity'));
    }

    /**
     * Stores a new property.
     *
     * @param PropertyRequest $request The request object containing the property name and entity ID.
     * @return JsonResponse A JSON response containing the newly created property.
     */
    public function store(PropertyRequest $request): JsonResponse
    {
        $property = $this->repository->store($request->name, $request->entity_id);
        return response()->json($property->load('entity.properties'), 201);
    }

    /**
     * Renames a property.
     *
     * @param Request $request The request object containing the property ID and new name.
     * @return JsonResponse A JSON response containing the renamed property.
     */
    public function rename(Request $request): JsonResponse
    {
        $request->validate(['name' => 'required|string', 'id' => 'required|integer']);
        $property = $this->repository->rename($request->id, $request->name);
        return response()->json($property->load('entity.properties'), 201);
    }

    /**
     * Deletes a property.
     *
     * @param int $property_id The ID of the property to delete.
     * @return JsonResponse A JSON response confirming the deletion.
     */
    public function destroy(int $property_id): JsonResponse
    {
        $this->repository->destroy($property_id);
        return response()->json(['message' => 'Property deleted successfully.']);
    }
}
