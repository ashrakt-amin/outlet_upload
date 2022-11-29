<?php

namespace App\Http\Controllers\Api;
use App\Models\Unit;

use App\Models\Level;
use App\Models\Project;
use App\Models\Construction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\ProjectCollection;

class MainPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $project = Project::where('id', 1)->with(['constructions','units', 'levels'])->get();

        return response()->json([
            "data" => ProjectResource::collection($project)
        ]);
    }
}
