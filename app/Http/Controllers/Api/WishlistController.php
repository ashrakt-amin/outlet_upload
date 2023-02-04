<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WishlistResource;
use App\Repository\WishlistRepositoryInterface;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Resources\Wishlist\WishlistItemsCountResource;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;
use App\Http\Traits\ImageProccessingTrait as TraitImageProccessingTrait;

class WishlistController extends Controller
{
    use TraitResponseTrait;
    use TraitsAuthGuardTrait;
    use TraitImageProccessingTrait;

    private $wishlistRepository;

    public function __construct(WishlistRepositoryInterface $wishlistRepository)
    {
        $this->wishlistRepository = $wishlistRepository;

        if(request()->bearerToken() != null) {
            return $this->middleware('auth:sanctum');
        };
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->sendResponse(WishlistResource::collection($this->wishlistRepository->index($request->all())), $this->wishlistRepository->index($request->all())->count(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $wishlist = $this->wishlistRepository->toggleWishlist($request->all());
        return $this->sendResponse($wishlist->count(), "", 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wishlist  $wishlist
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($this->wishlistRepository->deleteAll($request->all)) return $this->sendResponse("", "تم الحذف من المفضلة", 200);
    }
}
