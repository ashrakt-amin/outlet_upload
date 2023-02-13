<?php

namespace App\Http\Controllers\Api;
use App\Models\Project;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\NdProjectResource;
use App\Repository\ProjectRepositoryInterface;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Traits\AuthGuardTrait as TraitAuthGuardTrait;
use App\Http\Resources\Project\ProjectWithOutLevelsResource;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class ProjectController extends Controller
{
    use TraitAuthGuardTrait;
    use TraitImageProccessingTrait;
    use TraitResponseTrait;

    private $projectRepository;
    public function __construct(ProjectRepositoryInterface $projectRepository)
    {
        $this->projectRepository = $projectRepository;
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
        return $this->sendResponse(ProjectResource::collection($this->projectRepository->all()), "", 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function archived()
    {
        return $this->sendResponse(ProjectResource::collection($this->projectRepository->archived2()), "", 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allConditons(Request $request)
    {
        return $this->projectRepository->forAllConditionsReturn($request->all(), ProjectResource::class);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function streetsOffers()
    {
        return $this->sendResponse(ProjectResource::collection($this->projectRepository->all()), "", 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ProjectRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectRequest $request)
    {
        $project = $this->projectRepository->create($request->validated());
        return $this->sendResponse(new ProjectResource($project), "تم تسجيل مشروعا جديدا", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return $this->sendResponse(new NdProjectResource($this->projectRepository->find($project->id)),  "", 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\  $project
     * @return \Illuminate\Http\Response
     */
    public function ProjectWithOutLevels(Project $project)
    {
        return $this->sendResponse(new ProjectWithOutLevelsResource($this->projectRepository->find($project->id)),  "", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\ProjectRequest  $request
     * @param  \App\Models\  $project
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectRequest $request, Project $project)
    {
        return $this->sendResponse(new ProjectResource($this->projectRepository->edit($project->id, $request->validated())), "تم تعديل المشروع", 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        if (!count($project->levels)) {
            if ($this->projectRepository->delete($project->id)) return $this->sendResponse("", "تم حذف المشروع", 200);
        }
        return $this->sendError("لا يمكن حذف مشروعا له فروع", [], 200);
    }

    /**
     * restore single row
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        return $this->sendResponse($this->projectRepository->restore($id), "", 200);
    }

    /**
     * restore all rows
     *
     * @return \Illuminate\Http\Response
     */
    public function restoreAll()
    {
        return $this->sendResponse($this->projectRepository->restoreAll(), "", 200);
    }

    /**
     * force delete single row
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id)
    {
        return $this->sendResponse($this->projectRepository->forceDelete($id), "", 200);
    }

    /**
     * force delete all rows
     *
     * @return \Illuminate\Http\Response
     */
    public function forceDeleteAll()
    {
        return $this->sendResponse($this->projectRepository->forceDeleteAll(), "", 200);
    }
}
