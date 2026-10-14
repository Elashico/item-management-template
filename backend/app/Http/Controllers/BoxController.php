<?php

namespace App\Http\Controllers;

use App\Models\Box;
use Illuminate\Http\Request;

class BoxController extends Controller
{
    public function index()
    {
        return Box::withTrashed()
            ->with(['items' => fn ($q) => $q->withTrashed()])
            ->get();
    }

    public function show(Box $box)
    {
        return $box->load(['items' => fn ($q) => $q->withTrashed()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        return response()->json(Box::create($data), 201);
    }

    public function update(Request $request, Box $box)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        $box->update($data);
        return $box;
    }

    public function destroy(Box $box)
    {
        $box->delete(); // soft delete
        return response()->noContent();
    }

    public function restore($id)
    {
        $box = Box::onlyTrashed()->findOrFail($id);
        $box->restore();
        return $box;
    }

    public function forceDelete($id)
    {
        $box = Box::withTrashed()->findOrFail($id);
        $box->items()->withTrashed()->forceDelete();
        $box->forceDelete();

        return response()->json(null, 204);
    }
}
