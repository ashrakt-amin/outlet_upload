<?php

namespace App\Http\Controllers\Api;

use App\Models\TermsAndCondition;
use App\Http\Controllers\Controller;
use App\Http\Requests\TermsAndConditionRequest;
use App\Http\Resources\TermsAndConditionResource;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Repository\TermsAndConditionRepositoryInterface;

class TermsAndConditionController extends Controller
{
    use TraitResponseTrait;
    private $termsAndConditionRepository;
    public function __construct(TermsAndConditionRepositoryInterface $termsAndConditionRepository)
    {
        $this->termsAndConditionRepository = $termsAndConditionRepository;
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
        return $this->sendResponse(TermsAndConditionResource::collection($this->termsAndConditionRepository->all()), "", 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\TermsAndConditionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TermsAndConditionRequest $request)
    {
        $termsAndCondition = $this->termsAndConditionRepository->create($request->validated());
        return $this->sendResponse(new TermsAndConditionResource($termsAndCondition), "تم تسجيل نصا جديدا للشروط والاحكام", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TermsAndCondition  $termsAndCondition
     * @return \Illuminate\Http\Response
     */
    public function show(TermsAndCondition $termsAndCondition)
    {
        return $this->sendResponse(new TermsAndConditionResource($termsAndCondition), "", 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\TermsAndConditionRequest  $request
     * @param  \App\Models\TermsAndCondition  $termsAndCondition
     * @return \Illuminate\Http\Response
     */
    public function update(TermsAndConditionRequest $request, TermsAndCondition $termsAndCondition)
    {
        $termsAndCondition = $this->termsAndConditionRepository->edit($termsAndCondition->id, $request->validated());
        return $this->sendResponse(new TermsAndConditionResource($termsAndCondition), "تم تعديل نص الشروط والاحكام", 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TermsAndCondition  $termsAndCondition
     * @return \Illuminate\Http\Response
     */
    public function destroy(TermsAndCondition $termsAndCondition)
    {
        if ($this->termsAndConditionRepository->delete($termsAndCondition->id)) return $this->sendResponse("", "تم حذف نص الشروط والاحكام");
    }

    /**
     * restore single row
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        return $this->sendResponse($this->termsAndConditionRepository->restore($id), "", 200);
    }

    /**
     * restore all rows
     *
     * @return \Illuminate\Http\Response
     */
    public function restoreAll()
    {
        return $this->sendResponse($this->termsAndConditionRepository->restoreAll(), "", 200);
    }

    /**
     * force delete single row
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id)
    {
        return $this->sendResponse($this->termsAndConditionRepository->forceDelete($id), "", 200);
    }

    /**
     * force delete all rows
     *
     * @return \Illuminate\Http\Response
     */
    public function forceDeleteAll()
    {
        return $this->sendResponse($this->termsAndConditionRepository->forceDeleteAll(), "", 200);
    }
}
