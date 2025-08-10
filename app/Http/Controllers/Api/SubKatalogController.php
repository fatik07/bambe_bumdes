<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Katalog;
use App\Models\SubKatalog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SubKatalogController extends Controller
{
    /**
     * Display a listing of subkatalogs by katalog slug.
     */
    public function getByKatalog(string $katalogSlug): JsonResponse
    {
        try {
            $katalog = Katalog::where('slug', $katalogSlug)->firstOrFail();
            $subKatalogs = SubKatalog::where('katalog_id', $katalog->id)
                ->with('katalog:id,nama,slug')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Data sub katalog berhasil diambil',
                'data' => [
                    'katalog' => $katalog,
                    'sub_katalogs' => $subKatalogs
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Katalog tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Display the specified subkatalog by katalog slug and subkatalog slug.
     */
    public function show(string $katalogSlug, string $subKatalogSlug): JsonResponse
    {
        try {
            $katalog = Katalog::where('slug', $katalogSlug)->firstOrFail();
            $subKatalog = SubKatalog::where('katalog_id', $katalog->id)
                ->where('slug', $subKatalogSlug)
                ->with('katalog:id,nama,slug')
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'message' => 'Data sub katalog berhasil diambil',
                'data' => $subKatalog
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Sub katalog tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store a newly created subkatalog.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'katalog_id' => 'required|exists:katalogs,id',
                'nama' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $subKatalog = new SubKatalog();
            $subKatalog->katalog_id = $request->katalog_id;
            $subKatalog->nama = $request->nama;
            $subKatalog->slug = SubKatalog::generateUniqueSlug($request->nama);
            $subKatalog->deskripsi = $request->deskripsi;

            // Handle image upload
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('sub_katalogs', $imageName, 'public');
                $subKatalog->image = $imagePath;
            }

            $subKatalog->save();

            return response()->json([
                'success' => true,
                'message' => 'Sub katalog berhasil dibuat',
                'data' => $subKatalog->load('katalog:id,nama,slug')
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat sub katalog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified subkatalog.
     */
    public function update(Request $request, string $katalogSlug, string $subKatalogSlug): JsonResponse
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $katalog = Katalog::where('slug', $katalogSlug)->firstOrFail();
            $subKatalog = SubKatalog::where('katalog_id', $katalog->id)
                ->where('slug', $subKatalogSlug)
                ->firstOrFail();

            $oldImage = $subKatalog->image;

            $subKatalog->nama = $request->nama;
            $subKatalog->deskripsi = $request->deskripsi;

            // Update slug if nama changed
            if ($subKatalog->isDirty('nama')) {
                $subKatalog->slug = SubKatalog::generateUniqueSlug($request->nama, $subKatalog->id);
            }

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('sub_katalogs', $imageName, 'public');
                $subKatalog->image = $imagePath;
            }

            $subKatalog->save();

            return response()->json([
                'success' => true,
                'message' => 'Sub katalog berhasil diupdate',
                'data' => $subKatalog->load('katalog:id,nama,slug')
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate sub katalog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified subkatalog.
     */
    public function destroy(string $katalogSlug, string $subKatalogSlug): JsonResponse
    {
        try {
            $katalog = Katalog::where('slug', $katalogSlug)->firstOrFail();
            $subKatalog = SubKatalog::where('katalog_id', $katalog->id)
                ->where('slug', $subKatalogSlug)
                ->firstOrFail();

            // Delete image if exists
            if ($subKatalog->image && Storage::disk('public')->exists($subKatalog->image)) {
                Storage::disk('public')->delete($subKatalog->image);
            }

            $subKatalog->delete();

            return response()->json([
                'success' => true,
                'message' => 'Sub katalog berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus sub katalog',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
