<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repository\ViewRepositoryInterface;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;

class ViewController extends Controller
{
    use TraitResponseTrait;

    private $viewRepository;
    public function __construct(ViewRepositoryInterface $viewRepository)
    {
        $this->viewRepository = $viewRepository;

        if(request()->bearerToken() != null) {
            return $this->middleware('auth:sanctum');
        };
    }

    /**
     * @param id $attributes
     * @return response
     */
    public function whatsAppClick($id)
    {
        return $this->sendResponse($this->viewRepository->whatsAppClick($id), "", 200);
    }
}
