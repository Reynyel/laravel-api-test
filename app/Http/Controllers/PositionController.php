<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:positions,name',
            'reports_to' => 'nullable|exists:positions,id',
        ]);

        $position = Position::create($validated);
        return response()->json($position, 201);
    }

    public function index(Request $request)
    {
        $positions = Position::leftJoin('positions as reports_to_position', 'positions.reports_to', '=', 'reports_to_position.id')
            ->select('positions.*', 'reports_to_position.name as reports_to_name')
            ->orderBy('positions.id', 'desc');

        if ($request->has('search')) {
            $positions->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('sort')) {
            $positions->orderBy('name', $request->sort);
        }

        return response()->json($positions->get());
    }

    public function update(Request $request, $id)
    {
        $position = Position::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|unique:positions,name,' . $id,
            'reports_to' => 'nullable|exists:positions,id',
        ]);

        $position->update($validated);
        return response()->json($position);
    }

    public function destroy($id)
    {
        $position = Position::findOrFail($id);
        $position->delete();

        return response()->json(['message' => 'Position deleted successfully']);
    }

    public function show($id)
    {
        $position = Position::with('reportsTo')->findOrFail($id);
        return response()->json($position);
    }
}
