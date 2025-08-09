<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Katalog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class KatalogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $katalogs = Katalog::orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'message' => 'Data katalog berhasil diambil',
                'data' => $katalogs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data katalog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display a limited listing for home page.
     */
    public function getForHome(): JsonResponse
    {
        try {
            $katalogs = Katalog::orderBy('created_at', 'desc')
                ->limit(4)
                ->get(['id', 'nama', 'deskripsi']);

            return response()->json([
                'success' => true,
                'message' => 'Data katalog untuk home berhasil diambil',
                'data' => $katalogs
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data katalog',
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
            $request->validate([
                'nama' => 'required|string|max:255',
                'deskripsi' => 'required|string'
            ]);

            $katalog = new Katalog();
            $katalog->nama = $request->nama;
            $katalog->slug = Str::slug($request->nama);
            $katalog->deskripsi = $request->deskripsi;
            $katalog->save();

            return response()->json([
                'success' => true,
                'message' => 'Katalog berhasil dibuat',
                'data' => $katalog
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
                'message' => 'Gagal membuat katalog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug): JsonResponse
    {
        try {
            $katalog = Katalog::where('slug', $slug)->firstOrFail();

            return response()->json([
                'success' => true,
                'message' => 'Data katalog berhasil diambil',
                'data' => $katalog
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $slug): JsonResponse
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'deskripsi' => 'required|string'
            ]);

            $katalog = Katalog::where('slug', $slug)->firstOrFail();
            $katalog->nama = $request->nama;
            $katalog->deskripsi = $request->deskripsi;
            $katalog->save();

            return response()->json([
                'success' => true,
                'message' => 'Katalog berhasil diupdate',
                'data' => $katalog
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
                'message' => 'Gagal mengupdate katalog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $slug): JsonResponse
    {
        try {
            $katalog = Katalog::where('slug', $slug)->firstOrFail();
            $katalog->delete();

            return response()->json([
                'success' => true,
                'message' => 'Katalog berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus katalog',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
