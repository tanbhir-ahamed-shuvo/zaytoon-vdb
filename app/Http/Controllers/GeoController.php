<?php

namespace App\Http\Controllers;

use App\Models\GeoData;
use Illuminate\Http\Request;

class GeoController extends Controller
{
    public function districts(Request $request)
    {
        $districts = GeoData::where('division', $request->division)
            ->distinct()
            ->orderBy('district')
            ->pluck('district');

        return response()->json($districts);
    }

    public function thanas(Request $request)
    {
        $thanas = GeoData::where('division', $request->division)
            ->where('district', $request->district)
            ->distinct()
            ->orderBy('thana')
            ->pluck('thana');

        return response()->json($thanas);
    }

    public function unions(Request $request)
    {
        $unions = GeoData::where('division', $request->division)
            ->where('district', $request->district)
            ->where('thana', $request->thana)
            ->distinct()
            ->orderBy('union')
            ->pluck('union');

        return response()->json($unions);
    }
}
