<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        return Item::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'box_id' => 'required|exists:boxes,id',
            'name'   => 'required|string|max:255',
        ]);

        return response()->json(Item::create($data), 201);
    }

    public function show(Item $item)
    {
        return $item;
    }

    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'box_id' => 'sometimes|exists:boxes,id',
            'name'   => 'sometimes|string|max:255',
        ]);

        $item->update($data);

        return $item;
    }

    public function destroy(Item $item)
    {
        $item->delete(); // soft delete
        return response()->noContent();
    }

    public function restore($id)
    {
        $item = Item::onlyTrashed()->findOrFail($id);
        $item->restore();

        return $item;
    }

    public function forceDelete($id)
    {
        Item::withTrashed()->findOrFail($id)->forceDelete();

        return response()->noContent();
    }
}
