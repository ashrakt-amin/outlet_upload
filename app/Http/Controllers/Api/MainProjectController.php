<?php

namespace App\Http\Controllers\Api;
use App\Models\MainProject;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainProjectRequest;
use App\Http\Resources\MainProjectResource;
use App\Repository\MainProjectRepositoryInterface;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;

class MainProjectController extends Controller
{
    use TraitResponseTrait;
    private $mainProjectRepository;
    public function __construct(MainProjectRepositoryInterface $mainProjectRepository)
    {
        $this->mainProjectRepository = $mainProjectRepository;
        if(request()->bearerToken() != null) {
            return $this->middleware('auth:sanctum');
        };
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->sendResponse(mainProjectResource::collection($this->mainProjectRepository->all()), "", 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function offers()
    {
        $mainProjects = MainProject::with('projects')->get();
        return response()->json([
            "data" => MainProjectResource::collection($mainProjects)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\MainProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MainProjectRequest $request)
    {
        $mainProject = $this->mainProjectRepository->create($request->validated());
        return $this->sendResponse(new mainProjectResource($mainProject), "تم تسجيل مبنا جديدا", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\  $mainProject
     * @return \Illuminate\Http\Response
     */
    public function show(MainProject $mainProject)
    {
        return $this->sendResponse(new mainProjectResource($mainProject), "", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\MainProjectRequest  $request
     * @param  \App\Models\  $mainProject
     * @return \Illuminate\Http\Response
     */
    public function update(MainProjectRequest $request, MainProject $mainProject)
    {
        $mainProject = $this->mainProjectRepository->edit($mainProject->id, $request->validated());
        return $this->sendResponse(new mainProjectResource($mainProject), "تم تعديل المبنى");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\  $mainProject
     * @return \Illuminate\Http\Response
     */
    public function destroy(MainProject $mainProject)
    {
        if (!count($mainProject->projects)) {
            if ($this->mainProjectRepository->delete($mainProject->id)) return $this->sendResponse("", "تم حذف المبنى");
        }
        return $this->sendError("لا يمكن حذف نوع لديه مشاريع", [], 405);
    }
}
