<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tablet;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TabletController extends Controller
{
    // GET /api/tablets
    public function index()
    {
        return response()->json(Tablet::all(), Response::HTTP_OK);
    }

    // POST /api/tablets
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date' => 'required|date',
            'is_active' => 'boolean',
            'current_address' => 'required|string|max:255',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $tablet = Tablet::create($validated);

        return response()->json($tablet, Response::HTTP_CREATED);
    }

    // GET /api/tablets/{id}
    public function show($id)
    {
        $tablet = Tablet::find($id);
        return response()->json($tablet, Response::HTTP_OK);
    }

    // PUT /api/tablets/{id}
    public function update(Request $request, $id)
    {
        $tablet = Tablet::find($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'date' => 'sometimes|required|date',
            'is_active' => 'boolean',
            'current_address' => 'sometimes|required|string|max:255',
            'driver_id' => 'sometimes|required|exists:drivers,id',
        ]);

        $tablet->update($validated);

        return response()->json($tablet, Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $tablet = Tablet::find($id);
        $tablet->delete();

        return response()->json(['message' => 'Tablet deleted'], Response::HTTP_OK);
    }
}
