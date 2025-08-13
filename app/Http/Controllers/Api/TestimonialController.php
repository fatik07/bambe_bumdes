<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Katalog;
use App\Models\SubKatalog;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    /**
     * Display testimonials by sub katalog.
     */
    public function getBySubKatalog(string $katalogSlug, string $subKatalogSlug): JsonResponse
    {
        try {
            $katalog = Katalog::where('slug', $katalogSlug)->firstOrFail();
            $subKatalog = SubKatalog::where('katalog_id', $katalog->id)
                ->where('slug', $subKatalogSlug)
                ->firstOrFail();

            $testimonials = Testimonial::where('sub_katalog_id', $subKatalog->id)
                ->with('subKatalog:id,nama,slug')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Data testimonial berhasil diambil',
                'data' => [
                    'sub_katalog' => $subKatalog->load('katalog:id,nama,slug'),
                    'testimonials' => $testimonials
                ]
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
     * Display the specified testimonial.
     */
    public function show(string $katalogSlug, string $subKatalogSlug, string $id): JsonResponse
    {
        try {
            $katalog = Katalog::where('slug', $katalogSlug)->firstOrFail();
            $subKatalog = SubKatalog::where('katalog_id', $katalog->id)
                ->where('slug', $subKatalogSlug)
                ->firstOrFail();

            $testimonial = Testimonial::where('sub_katalog_id', $subKatalog->id)
                ->where('id', $id)
                ->with('subKatalog.katalog:id,nama,slug')
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'message' => 'Data testimonial berhasil diambil',
                'data' => $testimonial
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Testimonial tidak ditemukan',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Store a newly created testimonial.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'sub_katalog_id' => 'required|exists:sub_katalogs,id',
                'nama_project' => 'required|string|max:255',
                'nama_client' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'complete_hari' => 'required|integer|min:1',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $testimonial = new Testimonial();
            $testimonial->sub_katalog_id = $request->sub_katalog_id;
            $testimonial->nama_project = $request->nama_project;
            $testimonial->nama_client = $request->nama_client;
            $testimonial->deskripsi = $request->deskripsi;
            $testimonial->complete_hari = $request->complete_hari;

            // Handle image upload
            if ($request->hasFile('gambar')) {
                $image = $request->file('gambar');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('testimonials', $imageName, 'public');
                $testimonial->gambar = $imagePath;
            }

            $testimonial->save();

            return response()->json([
                'success' => true,
                'message' => 'Testimonial berhasil dibuat',
                'data' => $testimonial->load('subKatalog.katalog:id,nama,slug')
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
                'message' => 'Gagal membuat testimonial',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified testimonial.
     */
    public function update(Request $request, string $katalogSlug, string $subKatalogSlug, string $id): JsonResponse
    {
        try {
            $request->validate([
                'nama_project' => 'required|string|max:255',
                'nama_client' => 'required|string|max:255',
                'deskripsi' => 'required|string',
                'complete_hari' => 'required|integer|min:1',
                'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            $katalog = Katalog::where('slug', $katalogSlug)->firstOrFail();
            $subKatalog = SubKatalog::where('katalog_id', $katalog->id)
                ->where('slug', $subKatalogSlug)
                ->firstOrFail();

            $testimonial = Testimonial::where('sub_katalog_id', $subKatalog->id)
                ->where('id', $id)
                ->firstOrFail();

            $oldImage = $testimonial->gambar;

            $testimonial->nama_project = $request->nama_project;
            $testimonial->nama_client = $request->nama_client;
            $testimonial->deskripsi = $request->deskripsi;
            $testimonial->complete_hari = $request->complete_hari;

            // Handle image upload
            if ($request->hasFile('gambar')) {
                // Delete old image if exists
                if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                    Storage::disk('public')->delete($oldImage);
                }

                $image = $request->file('gambar');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('testimonials', $imageName, 'public');
                $testimonial->gambar = $imagePath;
            }

            $testimonial->save();

            return response()->json([
                'success' => true,
                'message' => 'Testimonial berhasil diupdate',
                'data' => $testimonial->load('subKatalog.katalog:id,nama,slug')
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
                'message' => 'Gagal mengupdate testimonial',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified testimonial.
     */
    public function destroy(string $katalogSlug, string $subKatalogSlug, string $id): JsonResponse
    {
        try {
            $katalog = Katalog::where('slug', $katalogSlug)->firstOrFail();
            $subKatalog = SubKatalog::where('katalog_id', $katalog->id)
                ->where('slug', $subKatalogSlug)
                ->firstOrFail();

            $testimonial = Testimonial::where('sub_katalog_id', $subKatalog->id)
                ->where('id', $id)
                ->firstOrFail();

            // Delete image if exists
            if ($testimonial->gambar && Storage::disk('public')->exists($testimonial->gambar)) {
                Storage::disk('public')->delete($testimonial->gambar);
            }

            $testimonial->delete();

            return response()->json([
                'success' => true,
                'message' => 'Testimonial berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus testimonial',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
