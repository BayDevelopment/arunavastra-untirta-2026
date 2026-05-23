<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BoatLocation;
use Illuminate\Http\Request;

class BoatLocationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'speed' => ['nullable', 'numeric'],
            'altitude' => ['nullable', 'numeric'],
            'status' => ['nullable', 'string'],
        ]);

        $location = BoatLocation::create([
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'speed' => $validated['speed'] ?? null,
            'altitude' => $validated['altitude'] ?? null,
            'status' => $validated['status'] ?? 'online',
        ]);

        return response()->json([
            'message' => 'Data lokasi kapal berhasil disimpan.',
            'data' => $location,
        ], 201);
    }

    public function latest()
    {
        $location = BoatLocation::latest()->first();

        return response()->json([
            'message' => $location ? 'Data lokasi terbaru berhasil diambil.' : 'Belum ada data lokasi.',
            'data' => $location,
        ]);
    }

    public function history()
    {
        $locations = BoatLocation::latest()
            ->limit(20)
            ->get();

        return response()->json([
            'message' => 'Riwayat lokasi berhasil diambil.',
            'data' => $locations,
        ]);
    }
}
