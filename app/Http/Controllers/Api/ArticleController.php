<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $articles = Article::with('tag')->latest()->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Articles retrieved successfully',
                'data' => $articles
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
                'image' => 'nullable|string',
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'tag_id' => 'required|exists:tags,id',
                'penulis' => 'required|string|max:255',
            ]);

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
                'image' => 'nullable|string',
                'judul' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'tag_id' => 'required|exists:tags,id',
                'penulis' => 'required|string|max:255',
            ]);

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
    public function getByTag(string $tagId): JsonResponse
    {
        try {
            $articles = Article::with('tag')->where('tag_id', $tagId)->latest()->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Articles retrieved successfully',
                'data' => $articles
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
