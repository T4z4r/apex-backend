<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class PolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $policies = Policy::all();
        return response()->json($policies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'string', Rule::unique('policies')],
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $policy = Policy::create($validated);

        return response()->json($policy, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $policy = Policy::findOrFail($id);
        return response()->json($policy);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $policy = Policy::findOrFail($id);

        $validated = $request->validate([
            'type' => ['required', 'string', Rule::unique('policies')->ignore($id)],
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
        ]);

        $policy->update($validated);

        return response()->json($policy);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $policy = Policy::findOrFail($id);
        $policy->delete();

        return response()->json(['message' => 'Policy deleted successfully']);
    }

    /**
     * Get active policy by type (for public access)
     */
    public function getByType(string $type): JsonResponse
    {
        $policy = Policy::where('type', $type)->active()->first();

        if (!$policy) {
            return response()->json(['message' => 'Policy not found'], 404);
        }

        return response()->json($policy);
    }
}
