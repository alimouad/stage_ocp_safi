<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Substance;

class SubstanceController extends Controller
{
    public function index()
    {
        $substances = Substance::all();

        return response()->json($substances, 200);
    }
}
