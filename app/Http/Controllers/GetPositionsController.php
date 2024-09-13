<?php

namespace App\Http\Controllers;

use App\Models\Position;
use Illuminate\Http\Request;

class GetPositionsController extends Controller
{
    //
    public function allPositions()
    {
        // $allPositions = Position::select()->orderBy('id', 'desc')->get();
        $allPositions = Position::leftJoin('positions as reports_to_position', 'positions.reports_to', '=', 'reports_to_position.id')
            ->select('positions.*', 'reports_to_position.name as reports_to_name')
            ->orderBy('positions.id', 'desc')
            ->get();
        return view('home', compact('allPositions'));
    }
}
