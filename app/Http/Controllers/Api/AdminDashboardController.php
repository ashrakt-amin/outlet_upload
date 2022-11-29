<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index() {
        $projectController = new ProjectController();
        $project = $projectController->index();
        return response()->json([
            'data'=>$project]);
    }

}
