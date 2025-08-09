<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Legalitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LegalitasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Legalitas::query();

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('nama', 'like', "%{$search}%");
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 10);
        $legalitas = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Legalitas retrieved successfully',
            'data' => $legalitas->items(),
            'meta' => [
                'current_page' => $legalitas->currentPage(),
                'from' => $legalitas->firstItem(),
                'last_page' => $legalitas->lastPage(),
                'per_page' => $legalitas->perPage(),
                'to' => $legalitas->lastItem(),
                'total' => $legalitas->total(),
            ],
            'links' => [
                'first' => $legalitas->url(1),
                'last' => $legalitas->url($legalitas->lastPage()),
                'prev' => $legalitas->previousPageUrl(),
                'next' => $legalitas->nextPageUrl(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('legalitas', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        $legalitas = Legalitas::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Legalitas created successfully',
            'data' => $legalitas
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Legalitas $legalitas)
    {
        return response()->json([
            'success' => true,
            'message' => 'Legalitas retrieved successfully',
            'data' => $legalitas
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Legalitas $legalitas)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string|max:255',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($legalitas->image && Storage::disk('public')->exists($legalitas->image)) {
                Storage::disk('public')->delete($legalitas->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('legalitas', $imageName, 'public');
            $data['image'] = $imagePath;
        }

        $legalitas->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Legalitas updated successfully',
            'data' => $legalitas
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Legalitas $legalitas)
    {
        // Delete image file if exists
        if ($legalitas->image && Storage::disk('public')->exists($legalitas->image)) {
            Storage::disk('public')->delete($legalitas->image);
        }

        $legalitas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Legalitas deleted successfully'
        ]);
    }
}
