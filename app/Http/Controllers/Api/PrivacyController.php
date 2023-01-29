<?php

namespace App\Http\Controllers\Api;

use App\Models\Privacy;
use App\Http\Controllers\Controller;
use App\Http\Requests\PrivacyRequest;
use App\Http\Resources\PrivacyResource;
use App\Repository\PrivacyRepositoryInterface;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;

class PrivacyController extends Controller
{
    use TraitResponseTrait;
    private $privacyRepository;
    public function __construct(PrivacyRepositoryInterface $privacyRepository)
    {
        $this->privacyRepository = $privacyRepository;
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
        return $this->sendResponse(PrivacyResource::collection($this->privacyRepository->all()), "", 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\PrivacyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PrivacyRequest $request)
    {
        $privacy = $this->privacyRepository->create($request->validated());
        return $this->sendResponse(new PrivacyResource($privacy), "تم تسجيل نصا جديدا للخصوصية", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Privacy  $privacy
     * @return \Illuminate\Http\Response
     */
    public function show(Privacy $privacy)
    {
        return $this->sendResponse(new PrivacyResource($privacy), "", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\PrivacyRequest  $request
     * @param  \App\Models\Privacy  $privacy
     * @return \Illuminate\Http\Response
     */
    public function update(PrivacyRequest $request, Privacy $privacy)
    {
        $privacy = $this->privacyRepository->edit($privacy->id, $request->validated());
        return $this->sendResponse(new PrivacyResource($privacy), "تم تعديل نص الخصوصية", 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Privacy  $privacy
     * @return \Illuminate\Http\Response
     */
    public function destroy(Privacy $privacy)
    {
        if ($this->privacyRepository->delete($privacy->id)) return $this->sendResponse("", "تم حذف نص الخصوصية", 204);
    }
}
