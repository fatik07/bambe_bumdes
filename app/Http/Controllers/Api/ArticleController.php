<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 9); // Default 9 items per page
            $perPage = min($perPage, 50); // Maximum 50 items per page
            
            $articles = Article::with('tag')
                ->when($request->search, function ($query, $search) {
                    return $query->where('judul', 'like', "%{$search}%")
                                ->orWhere('deskripsi', 'like', "%{$search}%")
                                ->orWhere('penulis', 'like', "%{$search}%");
                })
                ->when($request->tag_id, function ($query, $tagId) {
                    return $query->where('tag_id', $tagId);
                })
                ->orderBy($request->get('sort_by', 'created_at'), $request->get('sort_order', 'desc'))
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Articles retrieved successfully',
                'data' => $articles->items(),
                'pagination' => [
                    'current_page' => $articles->currentPage(),
                    'last_page' => $articles->lastPage(),
                    'per_page' => $articles->perPage(),
                    'total' => $articles->total(),
                    'from' => $articles->firstItem(),
                    'to' => $articles->lastItem(),
                    'has_more' => $articles->hasMorePages(),
                ],
                'links' => [
                    'first' => $articles->url(1),
                    'last' => $articles->url($articles->lastPage()),
                    'prev' => $articles->previousPageUrl(),
                    'next' => $articles->nextPageUrl(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'tag_id' => 'required|exists:tags,id',
                'penulis' => 'required|string|max:255',
            ]);

            // Handle file upload
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('articles', 'public');
            }

            $article = Article::create($validated);
            $article->load('tag');

            return response()->json([
                'success' => true,
                'message' => 'Article created successfully',
                'data' => $article
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $article = Article::with('tag')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Article retrieved successfully',
                'data' => $article
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Article not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $article = Article::findOrFail($id);
            
            $validated = $request->validate([
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'tag_id' => 'required|exists:tags,id',
                'penulis' => 'required|string|max:255',
            ]);

            // Handle file upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($article->image && Storage::disk('public')->exists($article->image)) {
                    Storage::disk('public')->delete($article->image);
                }
                $validated['image'] = $request->file('image')->store('articles', 'public');
            }

            $article->update($validated);
            $article->load('tag');

            return response()->json([
                'success' => true,
                'message' => 'Article updated successfully',
                'data' => $article
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update article',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $article = Article::findOrFail($id);
            
            // Delete image file if exists
            if ($article->image && Storage::disk('public')->exists($article->image)) {
                Storage::disk('public')->delete($article->image);
            }
            
            $article->delete();

            return response()->json([
                'success' => true,
                'message' => 'Article deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete article',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get articles by tag
     */
    public function getByTag(Request $request, string $tagId): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 10);
            $perPage = min($perPage, 50);
            
            $articles = Article::with('tag')
                ->where('tag_id', $tagId)
                ->when($request->search, function ($query, $search) {
                    return $query->where('judul', 'like', "%{$search}%")
                                ->orWhere('deskripsi', 'like', "%{$search}%")
                                ->orWhere('penulis', 'like', "%{$search}%");
                })
                ->orderBy($request->get('sort_by', 'created_at'), $request->get('sort_order', 'desc'))
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'message' => 'Articles retrieved successfully',
                'data' => $articles->items(),
                'pagination' => [
                    'current_page' => $articles->currentPage(),
                    'last_page' => $articles->lastPage(),
                    'per_page' => $articles->perPage(),
                    'total' => $articles->total(),
                    'from' => $articles->firstItem(),
                    'to' => $articles->lastItem(),
                    'has_more' => $articles->hasMorePages(),
                ],
                'links' => [
                    'first' => $articles->url(1),
                    'last' => $articles->url($articles->lastPage()),
                    'prev' => $articles->previousPageUrl(),
                    'next' => $articles->nextPageUrl(),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all tags for filter
     */
    public function getTags(): JsonResponse
    {
        try {
            $tags = \App\Models\Tag::select('id', 'nama')->get();

            return response()->json([
                'success' => true,
                'message' => 'Tags retrieved successfully',
                'data' => $tags
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve tags',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
